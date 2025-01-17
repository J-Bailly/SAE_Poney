<?php
namespace Database;

use PDO;

class DatabaseConnection {
    private static ?PDO $pdo = null;

    public static function getConnection(): PDO {
        if (self::$pdo === null) {
            self::$pdo = new PDO('mysql:host=localhost;dbname=equitaction;charset=utf8', 'root', '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        }
        return self::$pdo;
    }

    private function __construct() {}
}
?>
