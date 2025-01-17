<?php
namespace App;

use Database\DatabaseConnection;

class UserManager {
    private PDO $db;

    public function __construct() {
        $this->db = DatabaseConnection::getConnection();
    }

    public function registerUser(string $name, string $email, string $password): bool {
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
        ]);
    }

    public function authenticate(string $email, string $password): ?array {
        $stmt = $this->db->prepare("SELECT id, name, password FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }
}
?>
