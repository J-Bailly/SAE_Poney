<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poney Club Grand Galop</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <header>
    <nav class="navigation">
            <a href="index.php">Accueil</a>
            <?php
            // Démarre la session pour vérifier si l'utilisateur est connecté
            session_start();
            // Vérifie si l'utilisateur est connecté
            $is_logged_in = isset($_SESSION['user_id']);
            ?>
            <a href="quiz.php">Faire le Quiz</a> <!-- Accessible seulement si l'utilisateur est connecté -->
            <a href="classement.php">Score</a>
        </nav>
        <div>
            <?php if (!$is_logged_in): ?>
                <a href="Template/signup.php"><button class="sign-up-button">S'inscrire</button></a>
                <a href="Template/login.php"><button class="sign-up-button">Se connecter</button></a>
            <?php else: ?>
                <span>Bienvenue, <?= $_SESSION['user_name'] ?> !</span> <!-- Affichage du nom de l'utilisateur connecté -->
            <?php endif; ?>
        </div>
    </header>
    <div class="design-entete">
        <img src="Template/Fleur.png" alt="fleur">
        <div class="logo">
            <img src="Template/logo_cheval.png" alt="KavalKenny Klub Logo">
            <h1>KavalKenny Klub</h1>
        </div>
        <div class="inverser">
            <img src="Template/Fleur.png" alt="fleur">
        </div>
    </div>
    <main class='content'>
        <?= $content ?>
    </main>
    <div class="footer">
        <p>&copy; 2025 Poney Club Grand Galop | Tous droits réservés | Contactez-nous pour plus d'informations.</p>
    </div>
</body>

</html>
