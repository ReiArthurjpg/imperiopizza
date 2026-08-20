<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Operacao
{
    public static function getCurrent()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM operacoes WHERE status != 'completed' ORDER BY created_at DESC LIMIT 1");
        return $stmt->fetch();
    }

    private static function toMysqlDatetime($isoStr)
    {
        if (!$isoStr) return null;
        $time = strtotime($isoStr);
        return $time ? date('Y-m-d H:i:s', $time) : null;
    }

    public static function syncAll($operations)
    {
        if (!is_array($operations)) return;
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("INSERT INTO operacoes (id, `date`, status, started_at, kitchen_closed_at, completed_at) 
            VALUES (:id, :date, :status, :started_at, :kitchen_closed_at, :completed_at) 
            ON DUPLICATE KEY UPDATE `date` = VALUES(`date`), status = VALUES(status), started_at = VALUES(started_at), kitchen_closed_at = VALUES(kitchen_closed_at), completed_at = VALUES(completed_at)");

        foreach ($operations as $op) {
            if (isset($op['id'])) {
                $stmt->execute([
                    'id' => $op['id'],
                    'date' => $op['date'] ?? null,
                    'status' => $op['status'] ?? 'draft',
                    'started_at' => self::toMysqlDatetime($op['startedAt'] ?? null),
                    'kitchen_closed_at' => self::toMysqlDatetime($op['kitchenClosedAt'] ?? null),
                    'completed_at' => self::toMysqlDatetime($op['completedAt'] ?? null)
                ]);

                if (isset($op['commands']) && is_array($op['commands'])) {
                    Comanda::syncAll($op['id'], $op['commands']);
                }

                if (isset($op['mass']) && is_array($op['mass'])) {
                    \App\Models\Massa::syncAll($op['id'], $op['mass']);
                }
            }
        }
    }

    public static function syncEquipe($operacaoId, $team)
    {
        $db = Database::getInstance()->getConnection();
        
        // Ensure table exists
        $db->exec("CREATE TABLE IF NOT EXISTS operacao_equipe (
            operacao_id VARCHAR(36) NOT NULL,
            equipe_id VARCHAR(36) NOT NULL,
            PRIMARY KEY (operacao_id, equipe_id),
            FOREIGN KEY (operacao_id) REFERENCES operacoes(id) ON DELETE CASCADE,
            FOREIGN KEY (equipe_id) REFERENCES equipe(id) ON DELETE CASCADE
        )");

        // Delete old team for this operation
        $stmt = $db->prepare("DELETE FROM operacao_equipe WHERE operacao_id = ?");
        $stmt->execute([$operacaoId]);

        if (is_array($team) && count($team) > 0) {
            // Upsert each person into equipe to guarantee FK integrity.
            // Without this, if the person doesn't exist in equipe table,
            // the INSERT IGNORE silently skips the row → attendance list disappears on reload.
            $upsertPerson = $db->prepare(
                "INSERT INTO equipe (id, nome, cargo) VALUES (?, ?, ?) " .
                "ON DUPLICATE KEY UPDATE nome = VALUES(nome), cargo = VALUES(cargo)"
            );
            $insertStmt = $db->prepare("INSERT IGNORE INTO operacao_equipe (operacao_id, equipe_id) VALUES (?, ?)");
            foreach ($team as $member) {
                // Front sends personId for the team member
                $personId = $member['personId'] ?? $member['id'] ?? null;
                $name     = $member['name'] ?? 'Desconhecido';
                $role     = $member['role'] ?? 'Outros';
                if ($personId) {
                    $upsertPerson->execute([$personId, $name, $role]);
                    $insertStmt->execute([$operacaoId, $personId]);
                }
            }
        }
    }

    public static function start($operacaoId, $startedAt = null)
    {
        $db = Database::getInstance()->getConnection();
        
        $startedAt = $startedAt ? date('Y-m-d H:i:s', strtotime($startedAt)) : date('Y-m-d H:i:s');

        $stmt = $db->prepare("UPDATE operacoes SET status = 'production_open', started_at = ? WHERE id = ?");
        $stmt->execute([$startedAt, $operacaoId]);
    }

    public static function getEquipe($operacaoId)
    {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("
            SELECT e.id as personId, e.nome as name, e.cargo as role 
            FROM operacao_equipe oe
            JOIN equipe e ON oe.equipe_id = e.id
            WHERE oe.operacao_id = ?
        ");
        $stmt->execute([$operacaoId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function finishDay($operacaoId)
    {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("UPDATE operacoes SET status = 'completed', completed_at = CURRENT_TIMESTAMP WHERE id = ?");
        $stmt->execute([$operacaoId]);
        
        $stmt = $db->prepare("SELECT * FROM operacoes WHERE id = ?");
        $stmt->execute([$operacaoId]);
        $op = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return [
            'id' => $op['id'],
            'date' => $op['date'],
            'status' => $op['status'],
            'completedAt' => $op['completed_at']
        ];
    }
}
