<?php

namespace App\Models;

use App\Core\Database;

class Massa
{
    private static function toMysqlDatetime($isoStr)
    {
        if (!$isoStr) return null;
        $time = strtotime($isoStr);
        return $time ? date('Y-m-d H:i:s', $time) : null;
    }

    private static function clamp($value)
    {
        $num = is_numeric($value) ? (float)$value : 0.0;
        return min(max($num, 0), 99999999.0);
    }

    public static function syncAll($operacaoId, $massData)
    {
        if (!is_array($massData)) return;
        $db = Database::getInstance()->getConnection();

        // 1. Sync Estoque de Massas (Stock)
        if (isset($massData['stockSaved']) && $massData['stockSaved'] && isset($massData['stock'])) {
            $stock = $massData['stock'];
            
            // Delete existing to act like an upsert since operacao_id doesn't have UNIQUE KEY constraint in schema
            $db->prepare("DELETE FROM estoque_massas WHERE operacao_id = ?")->execute([$operacaoId]);
            
            $stmtStock = $db->prepare("INSERT INTO estoque_massas 
                (operacao_id, flour_kg, sugar_g, salt_g, eggs, oil_ml, water_l, yeast_g, stock_saved) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                
            $stmtStock->execute([
                $operacaoId,
                self::clamp($stock['flourKg'] ?? 0),
                self::clamp($stock['sugarG'] ?? 0),
                self::clamp($stock['saltG'] ?? 0),
                self::clamp($stock['eggs'] ?? 0),
                self::clamp($stock['oilMl'] ?? 0),
                self::clamp($stock['waterL'] ?? 0),
                self::clamp($stock['yeastG'] ?? 0),
                $massData['stockSaved'] ? 1 : 0
            ]);
        }

        // 2. Sync Lotes de Massa (Batches)
        if (isset($massData['batches']) && is_array($massData['batches'])) {
            $grouped = [];
            foreach ($massData['batches'] as $batch) {
                $wId = $batch['workerId'] ?? '';
                if (!$wId) continue;
                if (!isset($grouped[$wId])) {
                    $grouped[$wId] = [
                        'count' => 0, 'flourKg' => 0, 'sugarG' => 0, 'saltG' => 0, 
                        'eggs' => 0, 'oilMl' => 0, 'waterL' => 0, 'yeastG' => 0
                    ];
                }
                $mat = $batch['materials'] ?? [];
                $grouped[$wId]['count'] += 1;
                $grouped[$wId]['flourKg'] += ($mat['flourKg'] ?? 0);
                $grouped[$wId]['sugarG'] += ($mat['sugarG'] ?? 0);
                $grouped[$wId]['saltG'] += ($mat['saltG'] ?? 0);
                $grouped[$wId]['eggs'] += ($mat['eggs'] ?? 0);
                $grouped[$wId]['oilMl'] += ($mat['oilMl'] ?? 0);
                $grouped[$wId]['waterL'] += ($mat['waterL'] ?? 0);
                $grouped[$wId]['yeastG'] += ($mat['yeastG'] ?? 0);
            }

            // Drop existing batches for this operation and re-insert the aggregated ones
            $db->prepare("DELETE FROM lotes_massa WHERE operacao_id = ?")->execute([$operacaoId]);
            
            if (!empty($grouped)) {
                $stmtBatch = $db->prepare("INSERT INTO lotes_massa 
                    (operacao_id, worker_id, batch_count, flour_kg, sugar_g, salt_g, eggs, oil_ml, water_l, yeast_g) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    
                foreach ($grouped as $wId => $data) {
                    $stmtBatch->execute([
                        $operacaoId,
                        $wId,
                        $data['count'],
                        self::clamp($data['flourKg']),
                        self::clamp($data['sugarG']),
                        self::clamp($data['saltG']),
                        self::clamp($data['eggs']),
                        self::clamp($data['oilMl']),
                        self::clamp($data['waterL']),
                        self::clamp($data['yeastG'])
                    ]);
                }
            }
        }
    }
}
