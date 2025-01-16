<?php
// Démarrer une session
session_start();

// Connexion à la base de données
require('db_config.php'); // Inclure votre fichier de configuration de base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Vérifier que les champs ne sont pas vides
    if (empty($email) || empty($password)) {
        echo "Veuillez remplir tous les champs.";
        exit;
    }

    // Préparer la requête
    $stmt = $pdo->prepare("SELECT id, name, password FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Stocker les informations utilisateur dans la session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        // Redirection vers une page protégée
        header("Location: ../test_planning.php");
        exit;
    } else {
        echo "Email ou mot de passe incorrect.";
    }
} else {
    echo "Méthode non autorisée.";
}
?>
