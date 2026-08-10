<?php
namespace App\Commands;
use App\Core\Repository;

final class CommandRepository extends Repository
{
    public function create(array $data): void
    {
        $sql = 'INSERT INTO commands(operation_id, command_number, assembler_id, pizzas, volcano_qty, esfiha_qty, sweet_qty, note, status) VALUES(?,?,?,?,?,?,?,?,?)';
        $this->pdo->prepare($sql)->execute([$data['operation_id'], $data['command_number'], $data['assembler_id'], $data['pizzas'], $data['volcano_qty'], $data['esfiha_qty'], $data['sweet_qty'], $data['note'], $data['status']]);
    }
    public function move(int $id, string $status): void
    {
        $this->pdo->prepare('UPDATE commands SET status=? WHERE id=?')->execute([$status, $id]);
        if ($status === 'despacho') $this->pdo->prepare("INSERT IGNORE INTO dispatches(command_id, status, received_at) VALUES(?, 'aguardando', NOW())")->execute([$id]);
    }
    public function byOperation(int $operationId): array
    {
        $stmt = $this->pdo->prepare('SELECT c.*, p.name assembler_name, d.status dispatch_status FROM commands c JOIN people p ON p.id=c.assembler_id LEFT JOIN dispatches d ON d.command_id=c.id WHERE operation_id=? ORDER BY c.updated_at DESC');
        $stmt->execute([$operationId]);
        return $stmt->fetchAll();
    }
}
