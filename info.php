<?php
try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/BD/BD.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connexion réussie à SQLite !";
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>