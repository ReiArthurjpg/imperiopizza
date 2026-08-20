<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Comanda
{
    public static function getAllByOperacao($operacao_id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT c.*, e.nome as assembler_name, l.assembler_id 
                              FROM comandas c 
                              LEFT JOIN comandas_lotes l ON c.operacao_id = l.operacao_id AND c.number >= l.comanda_inicio AND c.number <= l.comanda_fim
                              LEFT JOIN equipe e ON l.assembler_id = e.id 
                              WHERE c.operacao_id = ? 
                              ORDER BY c.created_at DESC");
        $stmt->execute([$operacao_id]);
        return $stmt->fetchAll();
    }

    private static function toMysqlDatetime($isoStr)
    {
        if (!$isoStr) return null;
        $time = strtotime($isoStr);
        return $time ? date('Y-m-d H:i:s', $time) : null;
    }

    public static function syncAll($operacaoId, $commands)
    {
        if (!is_array($commands)) return;
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("INSERT INTO comandas 
            (id, operacao_id, number, pizzas, volcano, esfiha, sweet, note, status, dispatch_status, beverage, `change`, change_amount, ketchup, mayonnaise, dispatch_note, created_at, updated_at, cozinha_time, forno_time, despacho_time) 
            VALUES 
            (:id, :operacao_id, :number, :pizzas, :volcano, :esfiha, :sweet, :note, :status, :dispatch_status, :beverage, :change, :change_amount, :ketchup, :mayonnaise, :dispatch_note, :created_at, :updated_at, :cozinha_time, :forno_time, :despacho_time) 
            ON DUPLICATE KEY UPDATE 
            number = VALUES(number), pizzas = VALUES(pizzas), volcano = VALUES(volcano), esfiha = VALUES(esfiha), sweet = VALUES(sweet), note = VALUES(note), status = VALUES(status), dispatch_status = VALUES(dispatch_status), beverage = VALUES(beverage), `change` = VALUES(`change`), change_amount = VALUES(change_amount), ketchup = VALUES(ketchup), mayonnaise = VALUES(mayonnaise), dispatch_note = VALUES(dispatch_note), updated_at = VALUES(updated_at), cozinha_time = VALUES(cozinha_time), forno_time = VALUES(forno_time), despacho_time = VALUES(despacho_time)");

        $validCommands = [];
        foreach ($commands as $c) {
            if (isset($c['id'], $c['number'])) {
                $disp = $c['dispatch'] ?? [];
                $stTimes = $c['statusTimes'] ?? [];
                $stmt->execute([
                    'id' => $c['id'],
                    'operacao_id' => $operacaoId,
                    'number' => $c['number'],
                    'pizzas' => $c['pizzas'] ?? 1,
                    'volcano' => $c['volcano'] ?? 0,
                    'esfiha' => $c['esfiha'] ?? 0,
                    'sweet' => $c['sweet'] ?? 0,
                    'note' => $c['note'] ?? '',
                    'status' => $c['status'] ?? 'cozinha',
                    'dispatch_status' => $disp['status'] ?? 'aguardando',
                    'beverage' => !empty($disp['beverage']) ? 1 : 0,
                    'change' => !empty($disp['change']) ? 1 : 0,
                    'change_amount' => $disp['changeAmount'] ?? '',
                    'ketchup' => !empty($disp['ketchup']) ? 1 : 0,
                    'mayonnaise' => !empty($disp['mayonnaise']) ? 1 : 0,
                    'dispatch_note' => $disp['note'] ?? '',
                    'created_at' => self::toMysqlDatetime($c['createdAt'] ?? null),
                    'updated_at' => self::toMysqlDatetime($c['updatedAt'] ?? null),
                    'cozinha_time' => self::toMysqlDatetime($stTimes['cozinha'] ?? null),
                    'forno_time' => self::toMysqlDatetime($stTimes['forno'] ?? null),
                    'despacho_time' => self::toMysqlDatetime($stTimes['despacho'] ?? null)
                ]);
                
                if (!empty($c['assemblerId'])) {
                    $validCommands[] = [
                        'number' => (int)$c['number'], 
                        'assemblerId' => $c['assemblerId'],
                        'assemblerName' => $c['assemblerName'] ?? 'Desconhecido'
                    ];
                }
            }
        }
        
        // Calculate and save lotes
        usort($validCommands, function($a, $b) { return $a['number'] <=> $b['number']; });
        $lotes = [];
        $currentLote = null;
        
        foreach ($validCommands as $c) {
            if ($currentLote === null) {
                $currentLote = ['assemblerId' => $c['assemblerId'], 'assemblerName' => $c['assemblerName'], 'inicio' => $c['number'], 'fim' => $c['number']];
            } else {
                if ($currentLote['assemblerId'] === $c['assemblerId'] && $c['number'] === $currentLote['fim'] + 1) {
                    $currentLote['fim'] = $c['number'];
                } else if ($currentLote['assemblerId'] === $c['assemblerId'] && $c['number'] === $currentLote['fim']) {
                    // Same number, ignore
                } else {
                    $lotes[] = $currentLote;
                    $currentLote = ['assemblerId' => $c['assemblerId'], 'assemblerName' => $c['assemblerName'], 'inicio' => $c['number'], 'fim' => $c['number']];
                }
            }
        }
        if ($currentLote !== null) {
            $lotes[] = $currentLote;
        }

        if (!empty($lotes)) {
            $db->prepare("DELETE FROM comandas_lotes WHERE operacao_id = ?")->execute([$operacaoId]);
            $stmtLote = $db->prepare("INSERT INTO comandas_lotes (operacao_id, assembler_id, comanda_inicio, comanda_fim) VALUES (?, ?, ?, ?)");
            $stmtEquipe = $db->prepare("INSERT INTO equipe (id, nome, cargo) VALUES (?, ?, 'Montagem') ON DUPLICATE KEY UPDATE nome = VALUES(nome)");
            
            foreach ($lotes as $l) {
                try {
                    $stmtEquipe->execute([$l['assemblerId'], $l['assemblerName']]);
                } catch (\Exception $e) { /* ignore */ }
                
                try {
                    $stmtLote->execute([$operacaoId, $l['assemblerId'], $l['inicio'], $l['fim']]);
                } catch (\Exception $e) { /* ignore */ }
            }
        }
    }

    public static function assignAssembler($operacao_id, $number, $assembler_id)
    {
        $db = Database::getInstance()->getConnection();
        
        // 1. Remove $number from any existing lot
        $stmtFind = $db->prepare("SELECT id, assembler_id, comanda_inicio, comanda_fim FROM comandas_lotes WHERE operacao_id = ? AND ? >= comanda_inicio AND ? <= comanda_fim");
        $stmtFind->execute([$operacao_id, $number, $number]);
        $existing = $stmtFind->fetch();

        if ($existing) {
            if ($existing['assembler_id'] === $assembler_id) {
                return; // Already correctly assigned
            }
            
            $id = $existing['id'];
            $inicio = (int)$existing['comanda_inicio'];
            $fim = (int)$existing['comanda_fim'];
            
            if ($inicio === $number && $fim === $number) {
                $db->prepare("DELETE FROM comandas_lotes WHERE id = ?")->execute([$id]);
            } else if ($inicio === $number) {
                $db->prepare("UPDATE comandas_lotes SET comanda_inicio = ? WHERE id = ?")->execute([$number + 1, $id]);
            } else if ($fim === $number) {
                $db->prepare("UPDATE comandas_lotes SET comanda_fim = ? WHERE id = ?")->execute([$number - 1, $id]);
            } else {
                $db->prepare("UPDATE comandas_lotes SET comanda_fim = ? WHERE id = ?")->execute([$number - 1, $id]);
                $db->prepare("INSERT INTO comandas_lotes (operacao_id, assembler_id, comanda_inicio, comanda_fim) VALUES (?, ?, ?, ?)")
                   ->execute([$operacao_id, $existing['assembler_id'], $number + 1, $fim]);
            }
        }
        
        // 2. Add $number to $assembler_id
        $stmtCheckFim = $db->prepare("SELECT id, comanda_inicio FROM comandas_lotes WHERE operacao_id = ? AND assembler_id = ? AND comanda_fim = ? LIMIT 1");
        $stmtCheckFim->execute([$operacao_id, $assembler_id, $number - 1]);
        $lotePrev = $stmtCheckFim->fetch();

        $stmtCheckInicio = $db->prepare("SELECT id, comanda_fim FROM comandas_lotes WHERE operacao_id = ? AND assembler_id = ? AND comanda_inicio = ? LIMIT 1");
        $stmtCheckInicio->execute([$operacao_id, $assembler_id, $number + 1]);
        $loteNext = $stmtCheckInicio->fetch();

        if ($lotePrev && $loteNext) {
            // Bridge: merge Next into Prev, then delete Next
            $db->prepare("UPDATE comandas_lotes SET comanda_fim = ? WHERE id = ?")->execute([$loteNext['comanda_fim'], $lotePrev['id']]);
            $db->prepare("DELETE FROM comandas_lotes WHERE id = ?")->execute([$loteNext['id']]);
        } else if ($lotePrev) {
            $db->prepare("UPDATE comandas_lotes SET comanda_fim = ? WHERE id = ?")->execute([$number, $lotePrev['id']]);
        } else if ($loteNext) {
            $db->prepare("UPDATE comandas_lotes SET comanda_inicio = ? WHERE id = ?")->execute([$number, $loteNext['id']]);
        } else {
            $db->prepare("INSERT INTO comandas_lotes (operacao_id, assembler_id, comanda_inicio, comanda_fim) VALUES (?, ?, ?, ?)")
               ->execute([$operacao_id, $assembler_id, $number, $number]);
        }
    }

    public static function create($data)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO comandas (id, operacao_id, number, pizzas, volcano, esfiha, sweet, note) VALUES (:id, :operacao_id, :number, :pizzas, :volcano, :esfiha, :sweet, :note) ON DUPLICATE KEY UPDATE number = VALUES(number)");
        $stmt->execute([
            'id' => $data['id'],
            'operacao_id' => $data['operacao_id'],
            'number' => $data['number'],
            'pizzas' => $data['pizzas'],
            'volcano' => $data['volcano'] ?? 0,
            'esfiha' => $data['esfiha'] ?? 0,
            'sweet' => $data['sweet'] ?? 0,
            'note' => $data['note'] ?? ''
        ]);
        
        if (!empty($data['assembler_id'])) {
            self::assignAssembler($data['operacao_id'], (int)$data['number'], $data['assembler_id']);
        }
    }

    public static function updateStatus($id, $status)
    {
        $db = Database::getInstance()->getConnection();
        
        $timeField = '';
        if ($status === 'forno') $timeField = ', forno_time = CURRENT_TIMESTAMP';
        if ($status === 'despacho') $timeField = ', despacho_time = CURRENT_TIMESTAMP';
        
        $stmt = $db->prepare("UPDATE comandas SET status = :status $timeField WHERE id = :id");
        $stmt->execute(['status' => $status, 'id' => $id]);
    }

    public static function getKpisByOperacao($operacao_id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT 
            COUNT(*) as comandas,
            COALESCE(SUM(pizzas), 0) as pizzas
            FROM comandas WHERE operacao_id = ?");
        $stmt->execute([$operacao_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getKpisByPeriod($startDate, $endDate)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT 
            COUNT(*) as comandas,
            COALESCE(SUM(pizzas), 0) as pizzas
            FROM comandas WHERE DATE(created_at) BETWEEN ? AND ?");
        $stmt->execute([$startDate, $endDate]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * KPIs da operação ativa do dia.
     * Retorna null se não houver nenhuma operação em andamento.
     */
    public static function getKpisDia($operacao_id)
    {
        $db = Database::getInstance()->getConnection();

        // Totais gerais da operação
        $stmt = $db->prepare("
            SELECT
                COUNT(*) AS total_comandas,
                COALESCE(SUM(pizzas), 0) AS total_pizzas,
                COALESCE(SUM(esfiha), 0) AS total_esfihas,
                COALESCE(SUM(volcano), 0) AS total_vulcoes,
                COALESCE(SUM(sweet), 0) AS total_doces,
                SUM(CASE WHEN status = 'cozinha' THEN 1 ELSE 0 END) AS em_cozinha,
                SUM(CASE WHEN status = 'forno' THEN 1 ELSE 0 END) AS em_forno,
                SUM(CASE WHEN status = 'despacho' THEN 1 ELSE 0 END) AS despachadas
            FROM comandas
            WHERE operacao_id = ?
        ");
        $stmt->execute([$operacao_id]);
        $totais = $stmt->fetch(PDO::FETCH_ASSOC);

        // Top montadores da operação atual
        $stmtTop = $db->prepare("
            SELECT
                e.nome AS name,
                COUNT(c.id) AS comandas,
                COALESCE(SUM(c.pizzas), 0) AS pizzas
            FROM comandas c
            JOIN comandas_lotes l ON c.operacao_id = l.operacao_id
                AND c.number >= l.comanda_inicio
                AND c.number <= l.comanda_fim
            JOIN equipe e ON l.assembler_id = e.id
            WHERE c.operacao_id = ?
            GROUP BY e.id, e.nome
            ORDER BY pizzas DESC, comandas DESC
            LIMIT 5
        ");
        $stmtTop->execute([$operacao_id]);
        $topMontadores = $stmtTop->fetchAll(PDO::FETCH_ASSOC);

        return [
            'comandas'       => (int)$totais['total_comandas'],
            'pizzas'         => (int)$totais['total_pizzas'],
            'esfihas'        => (int)$totais['total_esfihas'],
            'vulcoes'        => (int)$totais['total_vulcoes'],
            'doces'          => (int)$totais['total_doces'],
            'em_cozinha'     => (int)$totais['em_cozinha'],
            'em_forno'       => (int)$totais['em_forno'],
            'despachadas'    => (int)$totais['despachadas'],
            'top_montadores' => $topMontadores,
        ];
    }

    public static function getTopAssemblersMensal($year = null, $month = null)
    {
        $db = Database::getInstance()->getConnection();
        
        $y = $year ? intval($year) : intval(date('Y'));
        $m = $month ? intval($month) : intval(date('m'));

        $stmt = $db->prepare("SELECT 
            e.nome as name,
            COUNT(c.id) as comandas,
            COALESCE(SUM(c.pizzas), 0) as pizzas
            FROM comandas c
            JOIN comandas_lotes l ON c.operacao_id = l.operacao_id AND c.number >= l.comanda_inicio AND c.number <= l.comanda_fim
            JOIN equipe e ON l.assembler_id = e.id
            WHERE YEAR(c.created_at) = ?
              AND MONTH(c.created_at) = ?
            GROUP BY e.id, e.nome
            ORDER BY pizzas DESC, comandas DESC, name ASC
            LIMIT 5");
        $stmt->execute([$y, $m]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Movimentações recentes da operação ativa.
     * Retorna as últimas $limit comandas atualizadas, com nome do montador e status.
     */
    public static function getMovimentacoesRecentes($operacao_id, $limit = 10)
    {
        $db = Database::getInstance()->getConnection();
        $limit = max(1, min(50, (int)$limit)); // entre 1 e 50

        $stmt = $db->prepare("
            SELECT
                c.id,
                c.number,
                c.pizzas,
                c.esfiha,
                c.volcano,
                c.sweet,
                c.status,
                c.dispatch_status,
                c.note,
                c.created_at,
                c.updated_at,
                c.cozinha_time,
                c.forno_time,
                c.despacho_time,
                COALESCE(e.nome, 'N/A') AS montador
            FROM comandas c
            LEFT JOIN comandas_lotes l
                ON c.operacao_id = l.operacao_id
                AND c.number >= l.comanda_inicio
                AND c.number <= l.comanda_fim
            LEFT JOIN equipe e ON l.assembler_id = e.id
            WHERE c.operacao_id = ?
            ORDER BY c.updated_at DESC, c.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$operacao_id, $limit]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Formata os dados para o frontend
        $result = [];
        foreach ($rows as $r) {
            // Calcula "equivalentes" (pizzas físicas + equivalentes de volcano/esfiha)
            $equiv = (int)$r['pizzas'] + (int)$r['volcano'] + (int)$r['esfiha'];

            $result[] = [
                'id'             => $r['id'],
                'numero'         => (int)$r['number'],
                'montador'       => $r['montador'],
                'status'         => $r['status'],
                'dispatch_status'=> $r['dispatch_status'],
                'pizzas'         => (int)$r['pizzas'],
                'esfihas'        => (int)$r['esfiha'],
                'vulcoes'        => (int)$r['volcano'],
                'doces'          => (int)$r['sweet'],
                'equivalentes'   => $equiv,
                'nota'           => $r['note'] ?? '',
                'criada_em'      => $r['created_at'],
                'atualizada_em'  => $r['updated_at'],
                'tempo_cozinha'  => $r['cozinha_time'],
                'tempo_forno'    => $r['forno_time'],
                'tempo_despacho' => $r['despacho_time'],
            ];
        }
        return $result;
    }

    public static function getDispatchQueue($operacaoId, $search = null, $status = null)
    {
        $db = Database::getInstance()->getConnection();
        
        $query = "SELECT c.*, e.nome as assembler_name 
                  FROM comandas c 
                  LEFT JOIN comandas_lotes l ON c.operacao_id = l.operacao_id AND c.number >= l.comanda_inicio AND c.number <= l.comanda_fim
                  LEFT JOIN equipe e ON l.assembler_id = e.id 
                  WHERE c.operacao_id = :op_id AND c.status = 'despacho'";
                  
        $params = [':op_id' => $operacaoId];
        
        if ($status) {
            $query .= " AND c.dispatch_status = :status";
            $params[':status'] = $status;
        }
        
        if ($search) {
            $query .= " AND (c.number LIKE :search OR e.nome LIKE :search2)";
            $params[':search'] = "%$search%";
            $params[':search2'] = "%$search%";
        }
        
        $query .= " ORDER BY c.despacho_time ASC";
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $result = [];
        foreach ($rows as $r) {
            $equiv = (int)$r['pizzas'] + (int)$r['volcano'] + (int)$r['esfiha'];
            $result[] = [
                'id' => $r['id'],
                'number' => (int)$r['number'],
                'pizzas' => (int)$r['pizzas'],
                'equivalentPizzas' => $equiv,
                'assemblerName' => $r['assembler_name'] ?? 'Desconhecido',
                'status' => $r['status'],
                'statusTimes' => [
                    'despacho' => $r['despacho_time']
                ],
                'dispatch' => [
                    'status' => $r['dispatch_status'],
                    'beverage' => (bool)$r['beverage'],
                    'change' => (bool)$r['change'],
                    'changeAmount' => $r['change_amount'],
                    'ketchup' => (bool)$r['ketchup'],
                    'mayonnaise' => (bool)$r['mayonnaise'],
                    'note' => $r['dispatch_note'],
                    'receivedAt' => $r['despacho_time'],
                    'checkedAt' => null, // Would add to schema if strictly needed, using updated_at for now
                    'deliveryAt' => null // Would add to schema if strictly needed, using updated_at for now
                ]
            ];
        }
        
        return $result;
    }

    public static function getDispatchStats($operacaoId)
    {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("
            SELECT
                SUM(CASE WHEN status = 'despacho' THEN 1 ELSE 0 END) AS total,
                SUM(CASE WHEN status = 'despacho' AND dispatch_status = 'aguardando' THEN 1 ELSE 0 END) AS aguardando,
                SUM(CASE WHEN status = 'despacho' AND dispatch_status = 'conferido' THEN 1 ELSE 0 END) AS conferido,
                SUM(CASE WHEN status = 'despacho' AND dispatch_status = 'entrega' THEN 1 ELSE 0 END) AS entrega,
                SUM(CASE WHEN status != 'completed' AND status != 'despacho' THEN 1 ELSE 0 END) AS pendingToFinish
            FROM comandas
            WHERE operacao_id = ?
        ");
        $stmt->execute([$operacaoId]);
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return [
            'total' => (int)($stats['total'] ?? 0),
            'aguardando' => (int)($stats['aguardando'] ?? 0),
            'conferido' => (int)($stats['conferido'] ?? 0),
            'entrega' => (int)($stats['entrega'] ?? 0),
            'pendingToFinish' => (int)($stats['pendingToFinish'] ?? 0)
        ];
    }

    public static function getOvenQueue($operacaoId)
    {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("SELECT c.id, c.number, c.pizzas, c.forno_time as readySince, e.nome as assemblerName 
                  FROM comandas c 
                  LEFT JOIN comandas_lotes l ON c.operacao_id = l.operacao_id AND c.number >= l.comanda_inicio AND c.number <= l.comanda_fim
                  LEFT JOIN equipe e ON l.assembler_id = e.id 
                  WHERE c.operacao_id = ? AND c.status = 'pronto'
                  ORDER BY c.forno_time ASC");
                  
        $stmt->execute([$operacaoId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $result = [];
        foreach ($rows as $r) {
            $result[] = [
                'id' => $r['id'],
                'number' => (int)$r['number'],
                'pizzas' => (int)$r['pizzas'],
                'assemblerName' => $r['assemblerName'] ?? 'Desconhecido',
                'readySince' => $r['readySince']
            ];
        }
        
        return $result;
    }

    public static function pullFromOven($operacaoId, $cmdId)
    {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("UPDATE comandas SET status = 'despacho', dispatch_status = 'aguardando', despacho_time = CURRENT_TIMESTAMP WHERE operacao_id = ? AND id = ?");
        $stmt->execute([$operacaoId, $cmdId]);
        
        return ['id' => $cmdId, 'status' => 'despacho'];
    }

    public static function checkDispatch($operacaoId, $cmdId, $data)
    {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("UPDATE comandas SET 
            dispatch_status = 'conferido',
            beverage = :beverage,
            `change` = :change,
            change_amount = :change_amount,
            ketchup = :ketchup,
            mayonnaise = :mayonnaise,
            dispatch_note = :dispatch_note,
            updated_at = CURRENT_TIMESTAMP
            WHERE operacao_id = :op_id AND id = :id");
            
        $stmt->execute([
            'beverage' => !empty($data['beverage']) ? 1 : 0,
            'change' => !empty($data['change']) ? 1 : 0,
            'change_amount' => $data['changeAmount'] ?? '',
            'ketchup' => !empty($data['ketchup']) ? 1 : 0,
            'mayonnaise' => !empty($data['mayonnaise']) ? 1 : 0,
            'dispatch_note' => $data['note'] ?? '',
            'op_id' => $operacaoId,
            'id' => $cmdId
        ]);
        
        return [
            'status' => 'conferido',
            'checkedAt' => date('Y-m-d H:i:s')
        ];
    }

    public static function sendDelivery($operacaoId, $cmdId, $deliveryAt)
    {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("UPDATE comandas SET dispatch_status = 'entrega', updated_at = CURRENT_TIMESTAMP WHERE operacao_id = ? AND id = ?");
        $stmt->execute([$operacaoId, $cmdId]);
        
        return [
            'status' => 'entrega',
            'deliveryAt' => $deliveryAt ?? date('Y-m-d H:i:s')
        ];
    }

    public static function revertDispatch($operacaoId, $cmdId, $reason)
    {
        $db = Database::getInstance()->getConnection();
        
        // Reason is currently just discarded as we don't have a history log for dispatch, 
        // but status is reverted back to aguardando.
        $stmt = $db->prepare("UPDATE comandas SET dispatch_status = 'aguardando', updated_at = CURRENT_TIMESTAMP WHERE operacao_id = ? AND id = ?");
        $stmt->execute([$operacaoId, $cmdId]);
    }

    public static function updateDispatch($operacaoId, $cmdId, $data)
    {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("UPDATE comandas SET 
            beverage = :beverage,
            `change` = :change,
            change_amount = :change_amount,
            ketchup = :ketchup,
            mayonnaise = :mayonnaise,
            dispatch_note = :dispatch_note,
            updated_at = CURRENT_TIMESTAMP
            WHERE operacao_id = :op_id AND id = :id");
            
        $stmt->execute([
            'beverage' => !empty($data['beverage']) ? 1 : 0,
            'change' => !empty($data['change']) ? 1 : 0,
            'change_amount' => $data['changeAmount'] ?? '',
            'ketchup' => !empty($data['ketchup']) ? 1 : 0,
            'mayonnaise' => !empty($data['mayonnaise']) ? 1 : 0,
            'dispatch_note' => $data['note'] ?? '',
            'op_id' => $operacaoId,
            'id' => $cmdId
        ]);
        
        return date('Y-m-d H:i:s');
    }
}
