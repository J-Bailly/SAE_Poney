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
            <a href="test_planning.php">Planning</a>
            <a href="#">Nous Contacter</a>
        </nav>
        <div>
            <a href="Template/signup.php"><button class="sign-up-button">S'inscrire</button></a>
            <a href="Template/login.php"><button class="sign-up-button">Se connecter</button></a>
        </div>
    </header>
    <div class="design-entete">
        <img src="Fleur.png" alt="fleur">
        <div class="logo">
            <img src="logo_cheval.png" alt="KavalKenny Klub Logo">
            <h1>KavalKenny Klub</h1>
        </div>
        <div class="inverser">
            <img src="Fleur.png" alt="fleur">
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
