<?php
$dbFile = 'score.sqlite';
try {
    $pdo = new PDO("sqlite:$dbFile");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "DROP TABLE IF EXISTS Score;";
    $pdo->exec($sql);

    echo "La table 'Score' a été supprimée avec succès !";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>