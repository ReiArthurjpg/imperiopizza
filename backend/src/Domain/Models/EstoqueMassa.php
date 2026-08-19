<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class EstoqueMassa
{
    public static function save($data)
    {
        $db = Database::getInstance()->getConnection();

        $operacao_id = $data['operacao_id'] ?? null;
        if (!$operacao_id) {
            throw new \Exception("operacao_id é obrigatório.");
        }

        $flour_kg = $data['flour_kg'] ?? 0;
        $sugar_g = $data['sugar_g'] ?? 0;
        $salt_g = $data['salt_g'] ?? 0;
        $eggs = $data['eggs'] ?? 0;
        $oil_ml = $data['oil_ml'] ?? 0;
        $water_l = $data['water_l'] ?? 0;
        $yeast_g = $data['yeast_g'] ?? 0;

        $stmt = $db->prepare("SELECT id FROM estoque_massas WHERE operacao_id = ?");
        $stmt->execute([$operacao_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $stmtUpdate = $db->prepare("
                UPDATE estoque_massas SET 
                    flour_kg = ?, sugar_g = ?, salt_g = ?, eggs = ?, oil_ml = ?, water_l = ?, yeast_g = ?, stock_saved = 1
                WHERE id = ?
            ");
            $stmtUpdate->execute([$flour_kg, $sugar_g, $salt_g, $eggs, $oil_ml, $water_l, $yeast_g, $row['id']]);
        } else {
            $stmtInsert = $db->prepare("
                INSERT INTO estoque_massas (
                    operacao_id, flour_kg, sugar_g, salt_g, eggs, oil_ml, water_l, yeast_g, stock_saved
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)
            ");
            $stmtInsert->execute([$operacao_id, $flour_kg, $sugar_g, $salt_g, $eggs, $oil_ml, $water_l, $yeast_g]);
        }

        return true;
    }
}
