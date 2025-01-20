<?php require(__DIR__ . '/../Template/header.php'); ?>

<?php
session_start();

try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/../../BD/BD.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$membre = 1;
$stmt_poneys = $pdo->prepare('SELECT * FROM RESERVER NATURAL JOIN COURS NATURAL JOIN MEMBRE WHERE id_membre = :id_membre');
$stmt_poneys->execute([':id_membre' => $membre]);
$liste_reservation = $stmt_poneys->fetchAll(PDO::FETCH_ASSOC);

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
        <div>
            <?php
            foreach ($liste_reservation as $reservation) {
                // Remplacez ceci par un affichage des données dans un format lisible.
                echo "<p>$reservation</p>";
            }
            ?>
        </div>
    </section>
</body>