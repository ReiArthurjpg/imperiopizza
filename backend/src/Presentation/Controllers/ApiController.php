<?php

namespace App\Back\Presentation\Controllers;

use App\Models\Comanda;
use App\Models\Operacao;
use App\Models\Equipe;
use App\Models\Configuracao;
use App\Models\BatidaMassa;
use App\Models\EstoqueMassa;

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

            // Sync database operations teams and metadata back to init data
            if (isset($data['operations']) && is_array($data['operations'])) {
                $db = \App\Core\Database::getInstance()->getConnection();
                $opStmt = $db->prepare("SELECT status, started_at, kitchen_closed_at, completed_at FROM operacoes WHERE id = ?");
                foreach ($data['operations'] as &$op) {
                    if (isset($op['id'])) {
                        $opStmt->execute([$op['id']]);
                        $dbOp = $opStmt->fetch(\PDO::FETCH_ASSOC);
                        if ($dbOp) {
                            $op['status'] = $dbOp['status'];
                            $op['startedAt'] = $dbOp['started_at'] ? (new \DateTime($dbOp['started_at']))->format(\DateTime::ATOM) : null;
                            $op['kitchenClosedAt'] = $dbOp['kitchen_closed_at'] ? (new \DateTime($dbOp['kitchen_closed_at']))->format(\DateTime::ATOM) : null;
                            $op['completedAt'] = $dbOp['completed_at'] ? (new \DateTime($dbOp['completed_at']))->format(\DateTime::ATOM) : null;
                        }

                        // Use DB team if available; otherwise keep team from state.json as fallback.
                        // This prevents wiping the attendance list when operacao_equipe is empty
                        // due to FK issues or when the team was saved before the DB was ready.
                        $dbTeam = \App\Models\Operacao::getEquipe($op['id']);
                        if (!empty($dbTeam)) {
                            $op['team'] = $dbTeam;
                        } elseif (!isset($op['team'])) {
                            $op['team'] = [];
                        }
                        // else: keep existing op['team'] from state.json
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

        // Guarantee the assembler exists in equipe table before assigning lotes.
        // Without this upsert, assignAssembler's INSERT IGNORE silently fails on FK violation
        // and the assembler never appears in the Top Montadores ranking.
        if (!empty($data['assembler_id'])) {
            try {
                $db = \App\Core\Database::getInstance()->getConnection();
                $name = $data['assembler_name'] ?? 'Desconhecido';
                $db->prepare(
                    "INSERT INTO equipe (id, nome, cargo) VALUES (?, ?, 'Montagem') "
                    . "ON DUPLICATE KEY UPDATE nome = VALUES(nome)"
                )->execute([$data['assembler_id'], $name]);
            } catch (\Exception $e) { /* non-fatal */ }
        }
        
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

    public function startOperacao()
    {
        $data = $this->getJsonInput();
        if (!isset($data['operacao_id'])) {
            $this->jsonResponse(['error' => 'Missing operacao_id'], 400);
        }

        try {
            \App\Models\Operacao::start($data['operacao_id'], $data['startedAt'] ?? null);
            $this->jsonResponse(['success' => true]);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function getEquipeOperacao()
    {
        $operacaoId = $_GET['operacao_id'] ?? null;
        if (!$operacaoId) {
            $this->jsonResponse(['error' => 'Missing operacao_id'], 400);
        }

        try {
            $team = \App\Models\Operacao::getEquipe($operacaoId);
            $this->jsonResponse(['success' => true, 'count' => count($team), 'team' => $team]);
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
        
        $roles = explode(',', $data['role']);
        if (count($roles) > 3) {
            $this->jsonResponse(['error' => 'Permitido no máximo 3 setores.'], 400);
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

    public function updateProfissional($id)
    {
        $data = $this->getJsonInput();
        
        if (!isset($data['name'], $data['role'])) {
            $this->jsonResponse(['error' => 'Invalid data'], 400);
        }
        
        $roles = explode(',', $data['role']);
        if (count($roles) > 3) {
            $this->jsonResponse(['error' => 'Permitido no máximo 3 setores.'], 400);
        }

        // Insert into database, using ON DUPLICATE KEY UPDATE in create()
        Equipe::create($id, $data['name'], $data['role']);

        // Update state.json to keep it in sync since frontend still uses it
        $file = __DIR__ . '/../../../../storage/state.json';
        if (file_exists($file)) {
            $jsonData = json_decode(file_get_contents($file), true);
            if (is_array($jsonData) && isset($jsonData['people'])) {
                foreach ($jsonData['people'] as &$p) {
                    if ($p['id'] == $id) {
                        $p['name'] = $data['name'];
                        $p['role'] = $data['role'];
                        break;
                    }
                }
                file_put_contents($file, json_encode($jsonData));
            }
        }

        $this->jsonResponse(['success' => true, 'profissional' => ['id' => $id, 'name' => $data['name'], 'role' => $data['role']]]);
    }

    public function deleteProfissional($id)
    {
        try {
            Equipe::delete($id);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => 'Erro ao excluir profissional do banco de dados: ' . $e->getMessage()], 500);
        }

        $file = __DIR__ . '/../../../../storage/state.json';
        if (file_exists($file)) {
            $jsonData = json_decode(file_get_contents($file), true);
            if (is_array($jsonData)) {
                if (isset($jsonData['people'])) {
                    $jsonData['people'] = array_values(array_filter($jsonData['people'], function($p) use ($id) {
                        return $p['id'] != $id;
                    }));
                }
                
                if (isset($jsonData['operations']) && is_array($jsonData['operations'])) {
                    foreach ($jsonData['operations'] as &$op) {
                        if (isset($op['team']) && is_array($op['team'])) {
                            $op['team'] = array_values(array_filter($op['team'], function($t) use ($id) {
                                return $t['personId'] != $id;
                            }));
                        }
                        if (isset($op['commands']) && is_array($op['commands'])) {
                            foreach ($op['commands'] as &$c) {
                                if (isset($c['assemblerId']) && $c['assemblerId'] == $id) {
                                    $c['assemblerId'] = '';
                                    $c['assemblerName'] = '';
                                }
                            }
                        }
                    }
                }
                
                file_put_contents($file, json_encode($jsonData));
            }
        }

        $this->jsonResponse(['success' => true]);
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

    public function createBatidaMassa()
    {
        $data = $this->getJsonInput();
        $op = Operacao::getCurrent();

        if (!$op || $op['status'] === 'draft') {
            $this->jsonResponse(['error' => 'Nenhuma operação ativa no momento.'], 400);
        }

        $data['operacao_id'] = $op['id'];

        try {
            $id = BatidaMassa::create($data);
            $this->jsonResponse(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function updateMassStock()
    {
        $data = $this->getJsonInput();
        $op = Operacao::getCurrent();

        if (!$op || $op['status'] === 'draft') {
            $this->jsonResponse(['error' => 'Nenhuma operação ativa no momento.'], 400);
        }

        $data['operacao_id'] = $op['id'];

        try {
            EstoqueMassa::save($data);
            $this->jsonResponse(['success' => true]);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function getMassKpis()
    {
        $op = Operacao::getCurrent();
        if (!$op || $op['status'] === 'draft') {
            $this->jsonResponse(['error' => 'Nenhuma operação ativa no momento.'], 400);
        }

        try {
            $kpis = BatidaMassa::getKpis($op['id']);
            $this->jsonResponse(['success' => true, 'data' => $kpis]);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function getMassStock()
    {
        $op = Operacao::getCurrent();
        if (!$op || $op['status'] === 'draft') {
            $this->jsonResponse(['error' => 'Nenhuma operação ativa no momento.'], 400);
        }

        try {
            $stock = EstoqueMassa::getStock($op['id']);
            $this->jsonResponse(['success' => true, 'data' => $stock]);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function getMassHistory()
    {
        $op = Operacao::getCurrent();
        if (!$op || $op['status'] === 'draft') {
            $this->jsonResponse(['error' => 'Nenhuma operação ativa no momento.'], 400);
        }

        try {
            $history = BatidaMassa::getHistory($op['id']);
            $this->jsonResponse(['success' => true, 'data' => $history]);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function getMassRecipe()
    {
        $recipe = [
            'flour_kg' => 10,
            'sugar_g' => 500,
            'salt_g' => 120,
            'eggs' => 10,
            'oil_ml' => 900,
            'water_l' => 3,
            'yeast_g' => 100
        ];
        $this->jsonResponse(['success' => true, 'data' => $recipe]);
    }
}
