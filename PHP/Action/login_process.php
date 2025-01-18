<?php
session_start();  // Démarre la session

require_once '../Classes/Form/Database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $email = $_POST['email'];
    $motDePasse = $_POST['password'];

    // Initialiser la connexion à la base de données
    try {
        $db = new \Classes\Form\Database('../../BD/BD.sqlite');
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }

    // Récupérer l'objet PDO via la méthode publique
    $pdo = $db->getPdo();

    // Vérifier si l'email existe dans la base de données
    $sql = "SELECT id_membre, password, nom, prenom FROM MEMBRE WHERE email = :email";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([':email' => $email]);
    } catch (PDOException $e) {
        die("Erreur d'exécution de la requête SQL : " . $e->getMessage());
    }

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérification du mot de passe
    if ($user && password_verify($motDePasse, $user['password'])) {
        // Mot de passe correct, démarrer la session
        $_SESSION['user_id'] = $user['id_membre'];
        $_SESSION['user_nom'] = $user['nom'];
        $_SESSION['user_prenom'] = $user['prenom'];

        // Rediriger vers la page d'accueil ou tableau de bord
        header("Location: dashboard.php");
        exit;
    } else {
        // Si l'email ou le mot de passe est incorrect
        $error_message = "Email ou mot de passe incorrect.";
    }
}
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
<?php require('../Template/header.php'); ?>

<main>
    <h1>Connexion</h1>

    <!-- Afficher les erreurs -->
    <?php if (isset($error_message)) { ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php } ?>

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
