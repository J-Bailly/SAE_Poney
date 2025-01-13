<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header>
        <nav class="navigation">
            <a href="../index.php">Accueil</a>
            <a href="../planning.php">Planning</a>
            <a href="#">Nous Contacter</a>
        </nav>
    </header>

    <main>
        <h1>Inscription</h1>
        <form action="signup_process.php" method="POST">
            <div>
                <label for="name">Nom :</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <label for="confirm_password">Confirmer le mot de passe :</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div>
                <button type="submit">S'inscrire</button>
            </div>
        </form>
    </main>

    <div class="footer">
        <p>&copy; 2025 Poney Club Grand Galop | Tous droits réservés.</p>
    </div>
</body>
</html>
