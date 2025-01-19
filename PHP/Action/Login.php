<?php require(__DIR__ . '/../Template/header.php'); ?>
<?php
session_start();


try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/../../BD/BD.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérer le message d'erreur de la session s'il existe
$error = isset($_SESSION['error']) ? $_SESSION['error'] : null;

// Supprimer le message d'erreur après l'avoir affiché
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>


<main>
    <h1>Connexion</h1>

    <!-- Affichage du message d'erreur -->
    <?php if ($error): ?>
        <div class="error-message" style="color: red; margin-bottom: 20px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="login_process.php" method="POST">
        <div>
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div>
            <button type="submit">Se connecter</button>
        </div>
    </form>
</main>

<div class="footer">
    <p>&copy; 2025 Poney Club Grand Galop | Tous droits réservés.</p>
</div>
</body>
</html>
