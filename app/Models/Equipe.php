<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Equipe
{
    public static function getAll()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM equipe");
        return $stmt->fetchAll();
    }

    public static function create($id, $nome, $cargo)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO equipe (id, nome, cargo) VALUES (:id, :nome, :cargo) ON DUPLICATE KEY UPDATE nome = VALUES(nome), cargo = VALUES(cargo)");
        $stmt->execute(['id' => $id, 'nome' => $nome, 'cargo' => $cargo]);
    }

    public static function syncAll($people)
    {
        if (!is_array($people)) return;
        $db = Database::getInstance()->getConnection();

        $keepIds = array_column($people, 'id');
        if (!empty($keepIds)) {
            $inClause = implode(',', array_fill(0, count($keepIds), '?'));
            $stmt = $db->prepare("DELETE FROM equipe WHERE id NOT IN ($inClause)");
            try {
                $stmt->execute($keepIds);
            } catch (\Exception $e) {
                // Ignore if person is linked to operations or comandas via FK
            }
        } else {
            try {
                $db->exec("DELETE FROM equipe");
            } catch (\Exception $e) {}
        }

        $stmt = $db->prepare("INSERT INTO equipe (id, nome, cargo) VALUES (:id, :nome, :cargo) ON DUPLICATE KEY UPDATE nome = VALUES(nome), cargo = VALUES(cargo)");
        foreach ($people as $p) {
            if (isset($p['id'], $p['name'], $p['role'])) {
                $stmt->execute(['id' => $p['id'], 'nome' => $p['name'], 'cargo' => $p['role']]);
            }
        }
    }
}
