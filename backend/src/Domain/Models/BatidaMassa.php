<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class BatidaMassa
{
    public static function create($data)
    {
        $db = Database::getInstance()->getConnection();

        // Extrai os campos ou usa valores default
        $operacao_id = $data['operacao_id'] ?? null;
        $worker_id = $data['worker_id'] ?? null;
        $flour_kg = $data['flour_kg'] ?? 0;
        $sugar_g = $data['sugar_g'] ?? 0;
        $salt_g = $data['salt_g'] ?? 0;
        $eggs = $data['eggs'] ?? 0;
        $oil_ml = $data['oil_ml'] ?? 0;
        $water_l = $data['water_l'] ?? 0;
        $yeast_g = $data['yeast_g'] ?? 0;
        $note = $data['note'] ?? null;

        if (!$operacao_id || !$worker_id) {
            throw new \Exception("operacao_id e worker_id são obrigatórios.");
        }

        // Insere a batida individual na nova tabela
        $stmtBatida = $db->prepare("
            INSERT INTO batidas_massa (
                operacao_id, worker_id, flour_kg, sugar_g, salt_g, 
                eggs, oil_ml, water_l, yeast_g, note
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmtBatida->execute([
            $operacao_id, $worker_id, $flour_kg, $sugar_g, $salt_g,
            $eggs, $oil_ml, $water_l, $yeast_g, $note
        ]);

        // Atualiza a tabela agregada lotes_massa
        $stmtLote = $db->prepare("
            INSERT INTO lotes_massa (
                operacao_id, worker_id, batch_count, flour_kg, sugar_g, salt_g, 
                eggs, oil_ml, water_l, yeast_g
            ) VALUES (?, ?, 1, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
                batch_count = batch_count + 1,
                flour_kg = flour_kg + VALUES(flour_kg),
                sugar_g = sugar_g + VALUES(sugar_g),
                salt_g = salt_g + VALUES(salt_g),
                eggs = eggs + VALUES(eggs),
                oil_ml = oil_ml + VALUES(oil_ml),
                water_l = water_l + VALUES(water_l),
                yeast_g = yeast_g + VALUES(yeast_g)
        ");

        $stmtLote->execute([
            $operacao_id, $worker_id, $flour_kg, $sugar_g, $salt_g,
            $eggs, $oil_ml, $water_l, $yeast_g
        ]);

        return $db->lastInsertId();
    }
}
