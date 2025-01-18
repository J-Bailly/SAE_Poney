<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
<?php require('../Template/header.php');?>
    <main>
        <h1>Connexion</h1>
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
