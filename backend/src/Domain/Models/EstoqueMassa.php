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

    public static function getStock($operacao_id)
    {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("SELECT * FROM estoque_massas WHERE operacao_id = ?");
        $stmt->execute([$operacao_id]);
        $estoque_inicial = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$estoque_inicial) {
            return null;
        }

        $stmtUso = $db->prepare("
            SELECT 
                COALESCE(SUM(flour_kg), 0) as used_flour_kg,
                COALESCE(SUM(sugar_g), 0) as used_sugar_g,
                COALESCE(SUM(salt_g), 0) as used_salt_g,
                COALESCE(SUM(eggs), 0) as used_eggs,
                COALESCE(SUM(oil_ml), 0) as used_oil_ml,
                COALESCE(SUM(water_l), 0) as used_water_l,
                COALESCE(SUM(yeast_g), 0) as used_yeast_g
            FROM lotes_massa 
            WHERE operacao_id = ?
        ");
        $stmtUso->execute([$operacao_id]);
        $uso = $stmtUso->fetch(PDO::FETCH_ASSOC);

        return [
            'inicial' => [
                'flour_kg' => (float)$estoque_inicial['flour_kg'],
                'sugar_g' => (float)$estoque_inicial['sugar_g'],
                'salt_g' => (float)$estoque_inicial['salt_g'],
                'eggs' => (int)$estoque_inicial['eggs'],
                'oil_ml' => (float)$estoque_inicial['oil_ml'],
                'water_l' => (float)$estoque_inicial['water_l'],
                'yeast_g' => (float)$estoque_inicial['yeast_g']
            ],
            'atual' => [
                'flour_kg' => max(0, (float)$estoque_inicial['flour_kg'] - (float)$uso['used_flour_kg']),
                'sugar_g' => max(0, (float)$estoque_inicial['sugar_g'] - (float)$uso['used_sugar_g']),
                'salt_g' => max(0, (float)$estoque_inicial['salt_g'] - (float)$uso['used_salt_g']),
                'eggs' => max(0, (int)$estoque_inicial['eggs'] - (int)$uso['used_eggs']),
                'oil_ml' => max(0, (float)$estoque_inicial['oil_ml'] - (float)$uso['used_oil_ml']),
                'water_l' => max(0, (float)$estoque_inicial['water_l'] - (float)$uso['used_water_l']),
                'yeast_g' => max(0, (float)$estoque_inicial['yeast_g'] - (float)$uso['used_yeast_g'])
            ]
        ];
    }
}
