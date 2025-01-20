<?php


if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit;
}

try {
    // Connexion à la base de données
    $pdo = new PDO('sqlite:' . __DIR__ . '/../../BD/BD.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Récupérer l'ID utilisateur depuis la session
$user_id = $_SESSION['user_id'];

// Récupérer les réservations de l'utilisateur
$query = '
    SELECT
        P.nom AS poney_nom,
        C.date AS cours_date,
        C.heure_debut AS cours_heure,
        C.duree AS cours_duree,
        C.categorie AS cours_categorie,
        C.prix AS cours_prix
    FROM RESERVER R
    JOIN PONEY P ON R.id_poney = P.id_poney
    JOIN COURS C ON R.id_cours = C.id_cours
    WHERE R.id_membre = :user_id
    ORDER BY C.date, C.heure_debut
';

$stmt = $pdo->prepare($query);
$stmt->execute([':user_id' => $user_id]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réservations</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
    <section>
        <h1>Mes Réservations</h1>
        <?php if (empty($reservations)) : ?>
            <p>Vous n'avez aucune réservation pour le moment.</p>
        <?php else : ?>
            <table>
                <thead>
                    <tr>
                        <th>Poney</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Durée (min)</th>
                        <th>Catégorie</th>
                        <th>Prix (€)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation) : ?>
                        <tr>
                            <td><?= htmlspecialchars($reservation['poney_nom']) ?></td>
                            <td><?= htmlspecialchars($reservation['cours_date']) ?></td>
                            <td><?= htmlspecialchars($reservation['cours_heure']) ?></td>
                            <td><?= htmlspecialchars($reservation['cours_duree']) ?></td>
                            <td><?= htmlspecialchars($reservation['cours_categorie']) ?></td>
                            <td><?= htmlspecialchars($reservation['cours_prix']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
</body>
</html>
