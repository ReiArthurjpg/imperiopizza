<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Comanda
{
    public static function getAllByOperacao($operacao_id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT c.*, e.nome as assembler_name FROM comandas c LEFT JOIN equipe e ON c.assembler_id = e.id WHERE operacao_id = ? ORDER BY c.created_at DESC");
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
            (id, operacao_id, assembler_id, number, pizzas, volcano, esfiha, sweet, note, status, dispatch_status, beverage, `change`, change_amount, ketchup, mayonnaise, dispatch_note, created_at, updated_at, cozinha_time, forno_time, despacho_time) 
            VALUES 
            (:id, :operacao_id, :assembler_id, :number, :pizzas, :volcano, :esfiha, :sweet, :note, :status, :dispatch_status, :beverage, :change, :change_amount, :ketchup, :mayonnaise, :dispatch_note, :created_at, :updated_at, :cozinha_time, :forno_time, :despacho_time) 
            ON DUPLICATE KEY UPDATE 
            assembler_id = VALUES(assembler_id), number = VALUES(number), pizzas = VALUES(pizzas), volcano = VALUES(volcano), esfiha = VALUES(esfiha), sweet = VALUES(sweet), note = VALUES(note), status = VALUES(status), dispatch_status = VALUES(dispatch_status), beverage = VALUES(beverage), `change` = VALUES(`change`), change_amount = VALUES(change_amount), ketchup = VALUES(ketchup), mayonnaise = VALUES(mayonnaise), dispatch_note = VALUES(dispatch_note), updated_at = VALUES(updated_at), cozinha_time = VALUES(cozinha_time), forno_time = VALUES(forno_time), despacho_time = VALUES(despacho_time)");

        foreach ($commands as $c) {
            if (isset($c['id'], $c['number'])) {
                $disp = $c['dispatch'] ?? [];
                $stTimes = $c['statusTimes'] ?? [];
                $stmt->execute([
                    'id' => $c['id'],
                    'operacao_id' => $operacaoId,
                    'assembler_id' => $c['assemblerId'] ?? '',
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
            }
        }
    }

    public static function create($data)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO comandas (id, operacao_id, assembler_id, number, pizzas, volcano, esfiha, sweet, note) VALUES (:id, :operacao_id, :assembler_id, :number, :pizzas, :volcano, :esfiha, :sweet, :note)");
        $stmt->execute([
            'id' => $data['id'],
            'operacao_id' => $data['operacao_id'],
            'assembler_id' => $data['assembler_id'],
            'number' => $data['number'],
            'pizzas' => $data['pizzas'],
            'volcano' => $data['volcano'] ?? 0,
            'esfiha' => $data['esfiha'] ?? 0,
            'sweet' => $data['sweet'] ?? 0,
            'note' => $data['note'] ?? ''
        ]);
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
}
