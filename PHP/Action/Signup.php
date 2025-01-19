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

<?php
require_once(__DIR__ .'/../Classes/Form/Database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $poids = $_POST['poids'];
    $motDePasse = $_POST['password']; // Récupérer le mot de passe
    $dateInscription = date('Y-m-d');  // Utilisation de la date actuelle

    // Initialiser la connexion à la base de données
    $db = new \Classes\Form\Database('../../BD/BD.sqlite');

    // Appel de la méthode inscrireMembre pour enregistrer le membre
    $resultat = $db->inscrireMembre($nom, $prenom, $email, $motDePasse, $poids, $dateInscription);

    // Message de confirmation ou d'erreur
    if ($resultat) {
        echo "<p>Membre inscrit avec succès!</p>";
    } else {
        echo "<p>Une erreur est survenue lors de l'inscription. Veuillez réessayer.</p>";
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

    <!-- Nouveau champ pour le mot de passe -->
    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="S'inscrire">
</form>

</body>
</html>
