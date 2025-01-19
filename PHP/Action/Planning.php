<?php require(__DIR__ . '/../Template/header.php'); ?>
<?php
session_start();

try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/../../BD/BD.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérer le mois et l'année
$mois = isset($_GET['mois']) ? (int)$_GET['mois'] : date('n');
$annee = isset($_GET['annee']) ? (int)$_GET['annee'] : date('Y');
$jours_dans_mois = cal_days_in_month(CAL_GREGORIAN, $mois, $annee);
$premier_jour_mois = date('w', strtotime("$annee-$mois-01"));
$jours_semaines = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

// Récupérer les cours pour le mois courant
$date_debut = sprintf('%04d-%02d-01', $annee, $mois);
$date_fin = sprintf('%04d-%02d-%02d', $annee, $mois, $jours_dans_mois);
$stmt = $pdo->prepare('SELECT * FROM COURS WHERE date BETWEEN :date_debut AND :date_fin');
$stmt->execute([':date_debut' => $date_debut, ':date_fin' => $date_fin]);
$cours_mois = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organiser les cours par jour
$cours_par_jour = [];
foreach ($cours_mois as $cours) {
    $date_cours = $cours['date'];
    if (!isset($cours_par_jour[$date_cours])) {
        $cours_par_jour[$date_cours] = [];
    }
    $cours_par_jour[$date_cours][] = $cours;
}

// Récupérer la liste des poneys
$stmt_poneys = $pdo->query('SELECT * FROM PONEY');
$liste_poneys = $stmt_poneys->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning Cours</title>
    <link rel="stylesheet" href="../../css/styles.css" />
    <link rel="stylesheet" href="../../css/planning.css" />
    <style>
        /* Conteneur principal */
        .page-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .calendar-container {
            flex: 1;
        }

        .site-footer {
            background-color: #A5B68D;
            color: white;
            text-align: center;
            padding: 10px 0;
            margin-top: 20px;
        }

        /* Grille du calendrier */
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            margin-top: 20px;
        }

        .day {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            border: 1px solid #ccc;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
            min-height: 100px;
        }

        .inactive {
            background-color: #eaeaea;
        }

        .course-count {
            margin-top: 8px;
            font-size: 0.9rem;
            color: #007BFF;
        }

        /* Modals */
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            width: 90%;
            max-width: 500px;
        }

        .modal:target {
            display: block;
        }

        .modal-content {
            display: flex;
            flex-direction: column;
        }

        .modal-content select {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .modal-content button {
            background-color: #f4a261;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
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
    <div class="page-container">
        <main class="calendar-container">
            <div class="calendar">
                <div class="calendar-header">
                    <h1><?php echo date('F Y', strtotime("$annee-$mois-01")); ?></h1>
                    <div>
                        <a href="?mois=<?php 
                            if (($mois - 1) <= 0) {
                                $prev_mois = 12; 
                                $prev_annee = $annee - 1; 
                            } else {
                                $prev_mois = $mois - 1; 
                                $prev_annee = $annee; 
                            }
                            echo $prev_mois; 
                        ?>&annee=<?php echo $prev_annee; ?>">&lt; Mois Précédent</a> |

                        <a href="?mois=<?php 
                            if (($mois + 1) >= 13) {
                                $next_mois = 1; 
                                $next_annee = $annee + 1; 
                            } else {
                                $next_mois = $mois + 1; 
                                $next_annee = $annee; 
                            }
                            echo $next_mois; 
                        ?>&annee=<?php echo $next_annee; ?>">Mois Suivant &gt;</a>
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
                        echo "<div class='day'>";
                        echo "<div class='day-number'>$jour</div>";

                        // Afficher le nombre de cours sous le jour
                        if (isset($cours_par_jour[$date])) {
                            $nombre_cours = count($cours_par_jour[$date]);
                            echo "<div class='course-count'>$nombre_cours cours</div>";
                        }

                        echo "</div>";
                        echo "</a>";

                        // Créer la modal
                        echo "
                        <div id='modal-$jour' class='modal'>
                            <div class='modal-content'>
                                <a href='#' class='close'>&times;</a>
                                <h2>Cours disponibles pour le $date</h2>
                                <form>
                                    <label for='cours-$jour'>Sélectionnez un cours :</label>
                                    <select id='cours-$jour' name='cours'>";
                        if (isset($cours_par_jour[$date])) {
                            foreach ($cours_par_jour[$date] as $cours) {
                                echo "<option value='{$cours['id_cours']}'>Cours ID {$cours['id_cours']} - {$cours['categorie']} ({$cours['heure_debut']})</option>";
                            }
                        } else {
                            echo "<option value=''>Aucun cours disponible</option>";
                        }
                        echo "          </select>

                                    <label for='poney-$jour'>Sélectionnez un poney :</label>
                                    <select id='poney-$jour' name='poney'>";
                        foreach ($liste_poneys as $poney) {
                            echo "<option value='{$poney['id_poney']}'>{$poney['nom']}</option>";
                        }
                        echo "          </select>
                                    <button type='submit'>Valider</button>
                                </form>
                            </div>
                        </div>";
                    }

                    // Ajouter des cases vides après la fin du mois pour compléter la grille
                    $cases_restantes = (7 - ($jours_dans_mois + $premier_jour_mois) % 7) % 7;
                    for ($i = 0; $i < $cases_restantes; $i++) {
                        echo "<div class='day inactive'></div>";
                    }
                    ?>
                </div>
            </div>
        </main>

        <footer class="site-footer">
            <p>&copy; 2025 Poney Club Grand Galop | Tous droits réservés.</p>
        </footer>
    </div>
</body>
</html>
