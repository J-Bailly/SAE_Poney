<?php
session_start(); // Assure-toi que la session est démarrée pour accéder aux informations de l'utilisateur
?>

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
            <a href="Reservations.php">Mes Réservations</a>
        </nav>
        <div>
            <?php if (isset($_SESSION['user_id'])): // Si l'utilisateur est connecté ?>
                <span>Bienvenue, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
                <a href="logout.php"><button class="sign-up-button">Se déconnecter</button></a>
            <?php else: // Si l'utilisateur n'est pas connecté ?>
                <a href="Signup.php"><button class="sign-up-button">S'inscrire</button></a>
                <a href="Login.php"><button class="sign-up-button">Se connecter</button></a>
            <?php endif; ?>
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
    <div class="footer">
        <p>&copy; 2025 Poney Club Grand Galop | Tous droits réservés | Contactez-nous pour plus d'informations.</p>
    </div>
</body>

</html>
