<?php
namespace App\Team;
use App\Core\Repository;

final class PeopleRepository extends Repository
{
    public function all(): array { return $this->pdo->query('SELECT * FROM people WHERE active=1 ORDER BY role, name')->fetchAll(); }
    public function create(string $name, string $role): void { $this->pdo->prepare('INSERT INTO people(name, role) VALUES(?, ?)')->execute([$name, $role]); }
    public function syncTeam(int $operationId, array $personIds): void
    {
        $this->pdo->prepare('DELETE FROM operation_team WHERE operation_id=?')->execute([$operationId]);
        $stmt = $this->pdo->prepare('INSERT INTO operation_team(operation_id, person_id, role) SELECT ?, id, role FROM people WHERE id=?');
        foreach ($personIds as $personId) { $stmt->execute([$operationId, (int) $personId]); }
    }
    public function team(int $operationId): array
    {
        $stmt = $this->pdo->prepare('SELECT p.* FROM operation_team ot JOIN people p ON p.id=ot.person_id WHERE ot.operation_id=? ORDER BY p.role,p.name');
        $stmt->execute([$operationId]);
        return $stmt->fetchAll();
    }
}
