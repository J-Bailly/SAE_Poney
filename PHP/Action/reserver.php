<?php
session_start(); // Assurez-vous que la session est démarrée

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = new PDO('sqlite:' . __DIR__ . '/../../BD/BD.sqlite');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Récupérer les données du formulaire
        $id_cours = $_POST['cours'];
        $id_poney = $_POST['poney'];
        $date = $_POST['date'];  // Date de la réservation
        $user_id = $_SESSION['user_id'];  // Utiliser l'ID de l'utilisateur connecté

        // Insertion dans la table RESERVER
        $stmt = $pdo->prepare('INSERT INTO RESERVER (id_membre, id_poney, id_cours) VALUES (?, ?, ?)');
        $stmt->execute([$user_id, $id_poney, $id_cours]);

        // Redirection après l'ajout
        header('Location: Planning.php?mois=' . date('n') . '&annee=' . date('Y'));
        exit();
    } catch (PDOException $e) {
        die("Erreur lors de la réservation : " . $e->getMessage());
    }
}
?>
