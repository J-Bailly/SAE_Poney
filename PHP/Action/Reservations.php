<?php require(__DIR__ . '/../Template/header.php'); ?>

<?php
session_start();

try {
    // Connexion à la base de données
    $pdo = new PDO('sqlite:' . __DIR__ . '/../../BD/BD.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// ID du membre (exemple avec un membre ID = 1)
$membre = 1;

// Préparation de la requête pour récupérer les réservations
$query = '
    SELECT
        M.nom AS membre_nom,
        M.prenom AS membre_prenom,
        P.nom AS poney_nom,
        C.date AS cours_date,
        C.heure_debut AS cours_heure,
        C.duree AS cours_duree,
        C.categorie AS cours_categorie,
        C.prix AS cours_prix
    FROM RESERVER R
    JOIN MEMBRE M ON R.id_membre = M.id_membre
    JOIN PONEY P ON R.id_poney = P.id_poney
    JOIN COURS C ON R.id_cours = C.id_cours
    WHERE R.id_membre = :id_membre
    ORDER BY C.date, C.heure_debut
';

$stmt = $pdo->prepare($query);
$stmt->execute([':id_membre' => $membre]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning Cours</title>
    <link rel="stylesheet" href="../../css/styles.css" />
</head>
<body>
    <section>
        <h1>Planning des cours réservés</h1>
        <div>
            <?php if (empty($reservations)) : ?>
                <p>Aucune réservation trouvée.</p>
            <?php else : ?>
                <table class="tableau_res">
                    <thead>
                        <tr>
                            <th>Membre</th>
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
                                <td><?= htmlspecialchars($reservation['membre_nom'] . ' ' . $reservation['membre_prenom']) ?></td>
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
        </div>
    </section>
</body>
</html>
