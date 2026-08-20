<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Configuracao
{
    public static function getAll()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT `key`, `value` FROM configuracoes");
        $results = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        return $results;
    }

    public static function update($key, $value)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE configuracoes SET `value` = :value WHERE `key` = :key");
        $stmt->execute(['value' => $value, 'key' => $key]);
    }
}
