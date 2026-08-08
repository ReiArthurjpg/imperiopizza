<?php
namespace App\Operations;
use App\Core\Repository;

final class OperationRepository extends Repository
{
    public function today(): array
    {
        $date = $_GET['date'] ?? date('Y-m-d');
        $this->pdo->prepare('INSERT IGNORE INTO operations(operation_date) VALUES (?)')->execute([$date]);
        $stmt = $this->pdo->prepare('SELECT * FROM operations WHERE operation_date = ?');
        $stmt->execute([$date]);
        return $stmt->fetch();
    }
    public function start(int $id): void { $this->pdo->prepare("UPDATE operations SET status='production_open', started_at=COALESCE(started_at,NOW()) WHERE id=?")->execute([$id]); }
    public function closeKitchen(int $id): void { $this->pdo->prepare("UPDATE operations SET status='kitchen_closed', kitchen_closed_at=COALESCE(kitchen_closed_at,NOW()) WHERE id=?")->execute([$id]); }
    public function finish(int $id): void { $this->pdo->prepare("UPDATE operations SET status='completed', completed_at=COALESCE(completed_at,NOW()) WHERE id=?")->execute([$id]); }
}
