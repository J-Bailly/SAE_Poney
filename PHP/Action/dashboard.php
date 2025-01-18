<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Rediriger vers la page de connexion
    exit;
}

echo "Bienvenue, " . $_SESSION['user_nom'] . " " . $_SESSION['user_prenom'] . "!";
?>
