<?php

namespace App\Back\Presentation\Controllers;

use App\Models\Comanda;
use App\Models\Operacao;
use App\Models\Equipe;
use App\Models\Configuracao;

class ApiController
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
        $file = __DIR__ . '/../../../../storage/state.json';
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
        $file = __DIR__ . '/../../../../storage/state.json';
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
        $data = $this->getJsonInput();
        if (!isset($data['operacao_id']) || !isset($data['team'])) {
            $this->jsonResponse(['error' => 'Missing operacao_id or team'], 400);
        }

        try {
            \App\Models\Operacao::syncEquipe($data['operacao_id'], $data['team']);
            $this->jsonResponse(['success' => true]);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function createProfissional()
    {
        $data = $this->getJsonInput();
        
        if (!isset($data['id'], $data['name'], $data['role'])) {
            $this->jsonResponse(['error' => 'Invalid data'], 400);
        }

        // Insert into database
        Equipe::create($data['id'], $data['name'], $data['role']);

        // Update state.json to keep it in sync since frontend still uses it
        $file = __DIR__ . '/../../../../storage/state.json';
        if (file_exists($file)) {
            $jsonData = json_decode(file_get_contents($file), true);
            if (is_array($jsonData)) {
                if (!isset($jsonData['people'])) {
                    $jsonData['people'] = [];
                }
                
                // Check if person exists to update or insert
                $found = false;
                foreach ($jsonData['people'] as &$p) {
                    if ($p['id'] == $data['id']) {
                        $p['name'] = $data['name'];
                        $p['role'] = $data['role'];
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $jsonData['people'][] = [
                        'id' => $data['id'],
                        'name' => $data['name'],
                        'role' => $data['role'],
                        'createdAt' => $data['createdAt'] ?? date('c')
                    ];
                }
                
                file_put_contents($file, json_encode($jsonData));
            }
        }

        $this->jsonResponse(['success' => true, 'profissional' => $data]);
    }

    public function getProfissionais()
    {
        try {
            $dbPeople = Equipe::getAll();
            $people = [];
            foreach ($dbPeople as $dp) {
                $people[] = [
                    'id' => $dp['id'],
                    'name' => $dp['nome'],
                    'role' => $dp['cargo']
                ];
            }
            $this->jsonResponse(['success' => true, 'profissionais' => $people]);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function getDashboardKpis()
    {
        try {
            $startDate = $_GET['start_date'] ?? null;
            $endDate = $_GET['end_date'] ?? null;

            if ($startDate && $endDate) {
                $kpis = Comanda::getKpisByPeriod($startDate, $endDate);
            } else {
                $op = Operacao::getCurrent();
                if (!$op) {
                    $this->jsonResponse([
                        'comandas' => 0,
                        'pizzas'   => 0,
                    ]);
                }
                $kpis = Comanda::getKpisByOperacao($op['id']);
            }
            
            $this->jsonResponse([
                'comandas' => (int)($kpis['comandas'] ?? 0),
                'pizzas'   => (int)($kpis['pizzas'] ?? 0),
            ]);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
     }

    public function getTopAssemblersMensal()
    {
        try {
            $year = isset($_GET['ano']) ? $_GET['ano'] : null;
            $month = isset($_GET['mes']) ? $_GET['mes'] : null;
            $ranking = Comanda::getTopAssemblersMensal($year, $month);
            $this->jsonResponse($ranking);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function getKpisDia()
    {
        try {
            $op = Operacao::getCurrent();

            // Sem operação ativa ou operação ainda em rascunho (não iniciada)
            if (!$op || $op['status'] === 'draft') {
                $this->jsonResponse([
                    'operacao_ativa' => false,
                    'status'         => 'inativa',
                    'mensagem'       => 'Nenhuma operação ativa no momento.',
                ]);
            }

            $kpis = Comanda::getKpisDia($op['id']);

            $this->jsonResponse([
                'operacao_ativa'  => true,
                'operacao_id'     => $op['id'],
                'operacao_status' => $op['status'],   // production_open | kitchen_closed
                'data'            => $op['date'],
                'iniciada_em'     => $op['started_at'],
                'finalizada_em'   => $op['completed_at'],
                'kpis'            => $kpis,
            ]);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function getMovimentacoesRecentes()
    {
        try {
            $op = Operacao::getCurrent();

            // Só funciona com operação ativa (não draft, não completed)
            if (!$op || $op['status'] === 'draft') {
                $this->jsonResponse([
                    'operacao_ativa' => false,
                    'status'         => 'inativa',
                    'mensagem'       => 'Nenhuma operação ativa no momento.',
                    'movimentacoes'  => [],
                ]);
            }

            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            $movimentacoes = Comanda::getMovimentacoesRecentes($op['id'], $limit);

            $this->jsonResponse([
                'operacao_ativa'  => true,
                'operacao_id'     => $op['id'],
                'operacao_status' => $op['status'],
                'data'            => $op['date'],
                'total'           => count($movimentacoes),
                'movimentacoes'   => $movimentacoes,
            ]);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function getEquipeOperacao()
    {
        try {
            $op = Operacao::getCurrent();

            if (!$op || $op['status'] === 'draft') {
                $this->jsonResponse([
                    'operacao_ativa' => false,
                    'status'         => 'inativa',
                    'mensagem'       => 'Nenhuma operação ativa no momento.',
                    'equipe'         => [],
                ]);
            }

            // Busca a equipe da operação atual no state.json
            $file = __DIR__ . '/../../../../storage/state.json';
            $team = [];
            if (file_exists($file)) {
                $jsonData = json_decode(file_get_contents($file), true);
                if (isset($jsonData['operations']) && is_array($jsonData['operations'])) {
                    foreach ($jsonData['operations'] as $stateOp) {
                        if (isset($stateOp['id']) && $stateOp['id'] === $op['id']) {
                            $team = $stateOp['team'] ?? [];
                            break;
                        }
                    }
                }
            }

            $this->jsonResponse([
                'operacao_ativa'  => true,
                'operacao_id'     => $op['id'],
                'operacao_status' => $op['status'],
                'data'            => $op['date'],
                'equipe'          => $team,
            ]);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
