<?php
session_start();

try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/../../BD/BD.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = 'Veuillez remplir tous les champs.';
        header('Location: Login.php');
        exit();
    }

    // Vérifier si l'utilisateur existe
    $stmt = $pdo->prepare('SELECT id_membre, email, password FROM MEMBRE WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['utilisateur'] = $user['id_membre'];
            $_SESSION['email'] = $user['email'];
            header('Location: dashboard.php');
            exit();
        } else {
            // Mot de passe incorrect
            $_SESSION['error'] = 'Mot de passe incorrect.';
        }
    } else {
        // Email non trouvé
        $_SESSION['error'] = 'Aucun compte associé à cet email.';
    }

    // Redirection vers Login.php avec un message d'erreur
    header('Location: Login.php');
    exit();
}
