<?php require(__DIR__ . '/../Template/header.php'); ?>
<?php

try {
    // Connexion à la base de données
    $pdo = new PDO('sqlite:' . __DIR__ . '/../../BD/BD.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Empêcher les utilisateurs déjà connectés d'accéder à cette page
if (isset($_SESSION['user_id'])) {
    header("Location: /../../index.php");
    exit;
}

// Initialiser un message d'erreur vide
$errorMessage = "";

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Vérification des champs
    if (empty($email) || empty($password)) {
        $errorMessage = "Veuillez remplir tous les champs.";
    } else {
        // Recherche de l'utilisateur dans la base de données
        $query = "SELECT * FROM MEMBRE WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Vérification du mot de passe
            if (password_verify($password, $user['password'])) {
                // Connexion réussie
                $_SESSION['user_id'] = $user['id_membre'];
                $_SESSION['user_name'] = $user['prenom'] . ' ' . $user['nom'];
                header("Location: Planning.php");
                exit;
            } else {
                // Mauvais mot de passe
                $errorMessage = "Mot de passe incorrect.";
            }
        } else {
            // Aucun utilisateur trouvé
            $errorMessage = "Aucun utilisateur trouvé avec cet email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
    <h1>Connexion</h1>

    <!-- Afficher le message d'erreur si nécessaire -->
    <?php if (!empty($errorMessage)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($errorMessage); ?></p>
    <?php endif; ?>

    <!-- Formulaire de connexion -->
    <form method="POST" action="Login.php">
        <label for="email">Email :</label>
        <input type="email" name="email" id="email" required>
        <br>
        <label for="password">Mot de passe :</label>
        <input type="password" name="password" id="password" required>
        <br>
        <p>Si vous n'avez pas encore de compte chez nous, <a href="Signup.php">Inscrivez vous</a>!</p>
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>
