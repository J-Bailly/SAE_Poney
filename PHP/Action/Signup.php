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

try {
    // Connexion à la base de données
    $pdo = new PDO('sqlite:' . __DIR__ . '/../../BD/BD.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("<p style='color:red;'>Erreur de connexion à la base de données : " . htmlspecialchars($e->getMessage()) . "</p>");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer et sécuriser les données soumises
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $poids = floatval($_POST['poids']);
    $motDePasse = trim($_POST['password']);
    $dateInscription = date('Y-m-d');

    // Validation des données
    $errors = [];
    if (empty($nom)) $errors[] = "Le nom est requis.";
    if (empty($prenom)) $errors[] = "Le prénom est requis.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "L'adresse email est invalide.";
    if ($poids <= 0) $errors[] = "Le poids doit être un nombre positif.";
    if (strlen($motDePasse) < 6) $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";

    if (empty($errors)) {
        try {
            // Vérifier si l'email existe déjà
            $query = $pdo->prepare("SELECT COUNT(*) FROM MEMBRE WHERE email = :email");
            $query->execute(['email' => $email]);
            if ($query->fetchColumn() > 0) {
                echo "<p style='color: red;'>Cet email est déjà utilisé. Veuillez en choisir un autre.</p>";
            } else {
                // Insérer l'utilisateur
                $motDePasseHash = password_hash($motDePasse, PASSWORD_BCRYPT);
                $query = $pdo->prepare("
                    INSERT INTO MEMBRE (nom, prenom, email, poids, password, date_inscription)
                    VALUES (:nom, :prenom, :email, :poids, :motDePasse, :dateInscription)
                ");
                $query->execute([
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'poids' => $poids,
                    'motDePasse' => $motDePasseHash,
                    'dateInscription' => $dateInscription,
                ]);

                // Connexion automatique
                $user_id = $pdo->lastInsertId();
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $prenom . ' ' . $nom;
                header("Location: Reservations.php"); // Redirection après connexion
                exit;
            }
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Erreur lors de l'inscription : " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } else {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>" . htmlspecialchars($error) . "</p>";
        }
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
