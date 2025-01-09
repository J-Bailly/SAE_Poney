<?php
$dbFile = 'score.sqlite';
try {
    $pdo = new PDO("sqlite:$dbFile");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "
        CREATE TABLE IF NOT EXISTS Score (
            IdEtu INT PRIMARY KEY NOT NULL,
            NomEtu TEXT NOT NULL,
            PrenomEtu TEXT NOT NULL,
            Score REAL
        );
    ";
    $pdo->exec($sql);

    echo "La base de données et la table 'Score' ont été créées avec succès !";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
