<?php
namespace App\Core;
use PDO;

abstract class Repository
{
    protected PDO $pdo;
    public function __construct() { $this->pdo = Database::pdo(); }
}
