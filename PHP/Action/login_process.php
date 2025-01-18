<?php
session_start(); // Démarrage de la session

// Connexion à la base de données
$dsn = 'mysql:host=localhost;dbname=equitaction;charset=utf8';
$username = 'root';
$password = '';
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}

// Vérification si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Vérification des champs
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = 'Veuillez remplir tous les champs.';
        header('Location: login.php');
        exit();
    }

    // Requête pour récupérer l'utilisateur
    $stmt = $pdo->prepare('SELECT id, email, password FROM membre WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Vérification du mot de passe
        if (password_verify($password, $user['password'])) {
            // Stocker les informations utilisateur dans la session
            $_SESSION['utilisateur'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            // Redirection vers la page d'accueil ou une autre page sécurisée
            header('Location: ../Template/dashboard.php');
            exit();
        } else {
            $_SESSION['error'] = 'Mot de passe incorrect.';
        }
    } else {
        $_SESSION['error'] = 'Utilisateur introuvable.';
    }

    // Redirection en cas d'erreur
    header('Location: login.php');
    exit();
}
