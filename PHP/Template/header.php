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
            <a href="../../index.php">Accueil</a>
            <a href="Planning.php">Planning</a>
            <a href="NousContacter.php">Nous Contacter</a>
        </nav>
        <div>
            <a href="Signup.php"><button class="sign-up-button">S'inscrire</button></a>
            <a href="Login.php"><button class="sign-up-button">Se connecter</button></a>
        </div>
    </header>
    <div class="design-entete">
        <img src="../img/Fleur.png" alt="fleur">
        <div class="logo">
            <img src="../img/logo_cheval.png" alt="KavalKenny Klub Logo">
            <h1>KavalKenny Klub</h1>
        </div>
        <div class="inverser">
            <img src="../img//Fleur.png" alt="fleur">
        </div>
    </div>
    <main>
        <?= isset($content) ? $content : '<p>Contenu non disponible.</p>' ?>
    </main>
    <div class="footer">
        <p>&copy; 2025 Poney Club Grand Galop | Tous droits réservés | Contactez-nous pour plus d'informations.</p>
    </div>
</body>

</html>
