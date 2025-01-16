<?php
// Connexion à la base de données
require('BD/cra.php'); // Inclure votre fichier de configuration de base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Vérifier que les champs ne sont pas vides
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "Veuillez remplir tous les champs.";
        exit;
    }

    // Vérifier que les mots de passe correspondent
    if ($password !== $confirm_password) {
        echo "Les mots de passe ne correspondent pas.";
        exit;
    }

    // Hashage du mot de passe
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        echo "Cet email est déjà utilisé.";
        exit;
    }

    // Insérer le nouvel utilisateur
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);

    if ($stmt->execute()) {
        echo "Inscription réussie. Vous pouvez maintenant vous connecter.";
        header("Location: login.php");
        exit;
    } else {
        echo "Une erreur est survenue lors de l'inscription.";
    }
} else {
    echo "Méthode non autorisée.";
}
?>
