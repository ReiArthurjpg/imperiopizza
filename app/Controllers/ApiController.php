<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Comanda;
use App\Models\Operacao;
use App\Models\Equipe;
use App\Models\Configuracao;

class ApiController extends Controller
{
    private function jsonResponse($data, $status = 200)
    {
        header("Content-Type: application/json");
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    private function getJsonInput()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    public function getInitData()
    {
        $file = __DIR__ . '/../../storage/state.json';
        $data = ['people' => [], 'operations' => []];
        if (file_exists($file)) {
            $jsonData = json_decode(file_get_contents($file), true);
            if (is_array($jsonData)) $data = $jsonData;
        }

        try {
            $dbPeople = Equipe::getAll();
            if (!empty($dbPeople)) {
                $existingIds = array_column($data['people'] ?? [], 'id');
                foreach ($dbPeople as $dp) {
                    if (!in_array($dp['id'], $existingIds)) {
                        $data['people'][] = [
                            'id' => $dp['id'],
                            'name' => $dp['nome'],
                            'role' => $dp['cargo']
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            // Silence DB errors if DB is initializing
        }

        $this->jsonResponse($data);
    }

    public function syncState()
    {
        $data = $this->getJsonInput();
        $file = __DIR__ . '/../../storage/state.json';
        if (!is_dir(dirname($file))) {
            mkdir(dirname($file), 0777, true);
        }
        file_put_contents($file, json_encode($data));

        if (isset($data['people']) && is_array($data['people'])) {
            Equipe::syncAll($data['people']);
        }

        if (isset($data['operations']) && is_array($data['operations'])) {
            Operacao::syncAll($data['operations']);
        }

        $this->jsonResponse(['success' => true]);
    }

    public function createComanda()
    {
        $data = $this->getJsonInput();
        $op = Operacao::getCurrent();
        
        $data['operacao_id'] = $op['id'];
        if (!isset($data['id'])) $data['id'] = uniqid();
        if (!isset($data['volcano'])) $data['volcano'] = 0;
        if (!isset($data['esfiha'])) $data['esfiha'] = 0;
        if (!isset($data['sweet'])) $data['sweet'] = 0;
        
        Comanda::create($data);
        
        $this->jsonResponse(['success' => true, 'comanda' => $data]);
    }

    public function updateComandaStatus()
    {
        $data = $this->getJsonInput();
        Comanda::updateStatus($data['id'], $data['status']);
        $this->jsonResponse(['success' => true]);
    }

    public function syncEquipe()
    {
        // TODO: Sync team to database
        $this->jsonResponse(['success' => true]);
    }
}
