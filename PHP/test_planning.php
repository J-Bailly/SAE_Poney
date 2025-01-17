<?php require 'Template/template.php'; ?>

<?php
session_start(); // Démarrer la session pour gérer la connexion des utilisateurs

// Connexion à la base de données
$dsn = 'mysql:host=localhost;dbname=equitaction;charset=utf8';
$username = 'root';
$password = '';
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];


// Vérification de la connexion de l'utilisateur
//if (!isset($_SESSION['utilisateur'])) {
    // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
//    header('Location: Template/login.php');
//    exit();
//}

// Récupérer le mois et l'année
$mois = isset($_GET['mois']) ? (int)$_GET['mois'] : date('n');
$annee = isset($_GET['annee']) ? (int)$_GET['annee'] : date('Y');
$jours_dans_mois = cal_days_in_month(CAL_GREGORIAN, $mois, $annee);
$premier_jour_mois = date('w', strtotime("$annee-$mois-01")); // 0 = Dimanche, 6 = Samedi
$jours_semaines = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

// Récupérer la date sélectionnée
$date_selectionnee = isset($_GET['date']) ? $_GET['date'] : null;

// Récupérer la liste des poneys
//$stmt = $pdo->query('SELECT id, nom FROM poneys');
//$liste_poneys = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gestion de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $poney_id = $_POST['poney'];
    $nom_utilisateur = htmlspecialchars($_SESSION['utilisateur']); // Utilisateur connecté

    // Enregistrer la réservation dans la base
    $stmt = $pdo->prepare('INSERT INTO reservations (date, poney_id, nom_utilisateur) VALUES (?, ?, ?)');
    $stmt->execute([$date, $poney_id, $nom_utilisateur]);

    echo "<p>Réservation effectuée pour le $date avec le poney ID $poney_id.</p>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning Cours</title>
    <link rel="stylesheet" href="css/planning.css" />
</head>
<body>
    <div class="calendar">
        <div class="calendar-header">
            <h1><?php echo date('F Y', strtotime("$annee-$mois-01")); ?></h1>
            <div>
                <a href="?mois=<?php echo $mois - 1; ?>&annee=<?php echo $annee; ?>" style="color: white;">&lt; Mois Précédent</a> |
                <a href="?mois=<?php echo $mois + 1; ?>&annee=<?php echo $annee; ?>" style="color: white;">Mois Suivant &gt;</a>
            </div>
        </div>
        <div class="calendar-grid">
            <?php
            // Afficher les noms des jours
            foreach ($jours_semaines as $jour) {
                echo "<div class='day'><strong>$jour</strong></div>";
            }

            // Ajouter les jours vides avant le 1er du mois
            for ($i = 0; $i < $premier_jour_mois; $i++) {
                echo "<div class='day inactive'></div>";
            }

            // Ajouter les jours du mois
            for ($jour = 1; $jour <= $jours_dans_mois; $jour++) {
                $date = sprintf('%04d-%02d-%02d', $annee, $mois, $jour);
                echo "<a href='#modal-$jour' class='day-link'>";
                echo "<div class='day'>$jour</div>";
                echo "</a>";

                // Récupérer les cours disponibles pour ce jour
                //$stmt = $pdo->prepare('SELECT * FROM cours WHERE date = ?');
                //$stmt->execute([$date]);
                //$cours = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Créer la pop-up pour chaque jour
                echo "
                <div id='modal-$jour' class='modal'>
                    <div class='modal-content'>
                        <a href='#' class='close'>&times;</a>
                        <h2>Réserver pour le $date</h2>
                        
                        <h3>Liste des cours disponibles :</h3>
                        <ul>
                ";
                foreach ($cours as $cours_dispo) {
                    echo "<li>{$cours_dispo['titre']} ({$cours_dispo['heure_debut']} - {$cours_dispo['heure_fin']})</li>";
                }
                echo "</ul>";

                echo "
                        <form method='POST' action=''>
                            <input type='hidden' name='date' value='$date'>
                            <select name='poney' required>
                ";
                foreach ($liste_poneys as $poney) {
                    echo "<option value='{$poney['id']}'>{$poney['nom']}</option>";
                }
                echo "
                            </select>
                            <button type='submit'>Réserver</button>
                        </form>
                    </div>
                </div>
                ";
            }


            // Ajouter des cases vides après la fin du mois pour compléter la grille
            $cases_restantes = (7 - ($jours_dans_mois + $premier_jour_mois) % 7) % 7;
            for ($i = 0; $i < $cases_restantes; $i++) {
                echo "<div class='day inactive'></div>";
            }
            ?>
        </div>
    </div>
    <script>
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            // Récupère toutes les modales ouvertes
            const modals = document.querySelectorAll('.modal:target');
            modals.forEach(function (modal) {
                window.location.hash = ''; // Réinitialise l'ancre pour fermer la pop-up
            });
        }
    });
    </script>
</body>
</html>

