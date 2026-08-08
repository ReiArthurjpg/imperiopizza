<?php
namespace App\Core;
use PDO;

final class Database
{
    private static ?PDO $pdo = null;
    public static function pdo(): PDO
    {
        if (self::$pdo === null) {
            $config = require __DIR__ . '/../../config/database.php';
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $config['host'], $config['database']);
            self::$pdo = new PDO($dsn, $config['user'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }
        return self::$pdo;
    }
}
