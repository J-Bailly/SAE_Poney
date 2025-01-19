<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - KavalKennyKlub</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
<?php require(__DIR__ . '/../Template/header.php'); ?>
<?php require(__DIR__ . '/../Classes/Form/Database.php'); ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $poids = floatval($_POST['poids']);
    $motDePasse = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
    $dateInscription = date('Y-m-d');

    try {
        $pdo = new PDO('sqlite:' . __DIR__ . '/../../BD/BD.sqlite');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db = new Database($pdo); // Initialisation correcte
        $resultat = $db->inscrireMembre($nom, $prenom, $email, $motDePasse, $poids, $dateInscription);

        if ($resultat) {
            echo "<p style='color: green;'>Membre inscrit avec succès !</p>";
        } else {
            echo "<p style='color: red;'>Une erreur est survenue lors de l'inscription. Veuillez réessayer.</p>";
        }
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}
?>

<h2>Inscription</h2>

<form method="POST" action="">
    <label for="nom">Nom :</label>
    <input type="text" id="nom" name="nom" required><br><br>

    <label for="prenom">Prénom :</label>
    <input type="text" id="prenom" name="prenom" required><br><br>

    <label for="email">Email :</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="poids">Poids (en kg) :</label>
    <input type="number" id="poids" name="poids" step="0.01" required><br><br>

    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="S'inscrire">
</form>

</body>
</html>
