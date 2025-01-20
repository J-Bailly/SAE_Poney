<?php
session_start();

try {
    // Connexion à la base de données
    $pdo = new PDO('sqlite:' . __DIR__ . '/../../BD/BD.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Vérifier si l'email et le mot de passe sont envoyés
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Récupérer l'utilisateur correspondant à l'email
    $query = "SELECT * FROM MEMBRE WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Vérifier le mot de passe
        if (password_verify($password, $user['password'])) { // Assurez-vous d'utiliser password_hash lors de l'inscription
            // Connexion réussie : stocker les infos de l'utilisateur dans la session
            $_SESSION['user_id'] = $user['id_membre'];
            $_SESSION['user_name'] = $user['prenom'] . ' ' . $user['nom'];
            header("Location: dashboard.php"); // Rediriger vers le tableau de bord ou une autre page sécurisée
            exit;
        } else {
            // Mauvais mot de passe
            $_SESSION['error'] = "Mot de passe incorrect.";
            header("Location: login.php");
            exit;
        }
    } else {
        // Email non trouvé
        $_SESSION['error'] = "Aucun utilisateur trouvé avec cet email.";
        header("Location: login.php");
        exit;
    }
} else {
    // Accès non autorisé ou données manquantes
    $_SESSION['error'] = "Veuillez remplir tous les champs.";
    header("Location: login.php");
    exit;
}
