<?php
$pdo = new PDO('sqlite:' . __DIR__ . '/../../BD/BD.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cours = $_POST['cours'];
    $id_poney = $_POST['poney'];
    $nom_utilisateur = htmlspecialchars($_SESSION['utilisateur']);

    // Ajouter la réservation
    $stmt = $pdo->prepare('INSERT INTO RESERVATIONS (id_cours, id_poney, nom_utilisateur) VALUES (?, ?, ?)');
    $stmt->execute([$id_cours, $id_poney, $nom_utilisateur]);

    header('Location: Planning.php');
    exit();
}
