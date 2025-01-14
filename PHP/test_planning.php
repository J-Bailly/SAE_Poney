<?php
// Connexion à la base de données
$dsn = 'mysql:host=localhost;dbname=equitaction;charset=utf8';
$username = 'root';
$password = '';
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];


// Récupérer le mois et l'année
$mois = isset($_GET['mois']) ? (int)$_GET['mois'] : date('n');
$annee = isset($_GET['annee']) ? (int)$_GET['annee'] : date('Y');
$jours_dans_mois = cal_days_in_month(CAL_GREGORIAN, $mois, $annee);
$premier_jour_mois = date('w', strtotime("$annee-$mois-01")); // 0 = Dimanche, 6 = Samedi
$jours_semaines = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];    


// Récupérer la date sélectionnée (si une case est cliquée)
$date_selectionnee = isset($_GET['date']) ? $_GET['date'] : null;

// Gestion de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $poney_id = $_POST['poney'];
    $nom_utilisateur = htmlspecialchars($_POST['nom_utilisateur']);

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
    <title>Planning Mensuel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fcfaee;
            color: #333;
        }
        .calendar {
            width: 90%;
            max-width: 1200px;
            margin: auto;
            margin-top: 50px;
        }
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #A5B68D;
            padding: 20px;
            color: white;
        }
        .calendar-header h1 {
            margin: 0;
        }
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            background-color: #d7e3d4;
        }
        .day {
            background-color: #d1e7ff;
            padding: 20px;
            text-align: center;
            border: 1px solid #ccc;
            cursor: pointer;
            height: 100px;
        }
        .day.inactive {
            background-color: #f2f2f2;
            color: #666;
            cursor: default;
        }
        .day a {
            color: inherit;
            text-decoration: none;
        }

        /* Style pour la pop-up */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal:target {
            display: flex;
        }
        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            width: 90%;
            max-width: 400px;
        }
        .modal-content h2 {
            margin: 0 0 20px;
        }
        .modal-content form {
            display: flex;
            flex-direction: column;
        }
        .modal-content select,
        .modal-content input,
        .modal-content button {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .modal-content button {
            background-color: #f4a261;
            color: white;
            cursor: pointer;
        }
        .modal-content button:hover {
            background-color: #e76f51;
        }
        .close {
            position: absolute;
            top: 10px;
            right: 10px;
            text-decoration: none;
            font-size: 20px;
            color: #000;
        }
    </style>
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
                echo "<div class='day'>";
                echo "<a href='#modal-$jour'>$jour</a>";
                echo "</div>";

                // Créer la pop-up pour chaque jour
                echo "
                <div id='modal-$jour' class='modal'>
                    <div class='modal-content'>
                        <a href='#' class='close'>&times;</a>
                        <h2>Réserver pour le $date</h2>
                        <form method='POST' action=''>
                            <input type='hidden' name='date' value='$date'>
                            <select name='poney' required>
                ";
                foreach ($liste_poneys as $poney) {
                    echo "<option value='{$poney['id']}'>{$poney['nom']}</option>";
                }
                echo "
                            </select>
                            <input type='text' name='nom_utilisateur' placeholder='Votre nom' required>
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
</body>
</html>
