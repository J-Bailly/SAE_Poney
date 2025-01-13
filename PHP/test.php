<html>
<head>
    <title>Démo planning dynamique</title>
    <link rel="stylesheet" href="test.css" />
</head>
<body>
<div id="planning">
<?php
include('Planning.php');
include('PlanningCellule.php');
include('PlanningMapper.php');

$contenusCellules[] = new PlanningCellule(1, '17:30:00', '19:00:00', '#008000', '<b>Zeus</b><br />B1/1');
$contenusCellules[] = new PlanningCellule(2, '12:30:00', '14:00:00', '#7CCAF4', '<b>Ryle</b><br />A2/2');
$contenusCellules[] = new PlanningCellule(2, '14:00:00', '15:30:00', '#7CCAF4', '<b>Mère-Térésa</b><br />A2/2');
$contenusCellules[] = new PlanningCellule(2, '16:00:00', '17:30:00', '#7CCAF4', '<b>Truc</b><br />A2/1');
$contenusCellules[] = new PlanningCellule(3, '10:30:00', '12:00:00', '#318CE7', '<b>Alta</b><br />A1/1');
$contenusCellules[] = new PlanningCellule(3, '12:30:00', '14:00:00', '#318CE7', '<b>Calimero</b><br />B2/1');
$contenusCellules[] = new PlanningCellule(3, '14:00:00', '15:30:00', '#008000', '<b>Damiens</b><br />A2/1');
$contenusCellules[] = new PlanningCellule(3, '16:00:00', '17:30:00', '#008000', '<b>Cbaye</b><br />A2/2');
$contenusCellules[] = new PlanningCellule(3, '17:30:00', '19:00:00', '#008000', '<b>Moosh</b><br />B1/1');
$contenusCellules[] = new PlanningCellule(3, '18:00:00', '19:30:00', '#008000', '<b>Arthur</b><br />B2/1');
$contenusCellules[] = new PlanningCellule(3, '14:00:00', '15:30:00', '#7CCAF4', '<b>Ab</b><br />A2/1');
$contenusCellules[] = new PlanningCellule(3, '16:00:00', '17:30:00', '#008000', '<b>Ryle</b><br />B1/2');
$contenusCellules[] = new PlanningCellule(4, '10:30:00', '12:00:00', '#318CE7', '<b>AB</b><br />A1/2');
$contenusCellules[] = new PlanningCellule(4, '12:30:00', '14:00:00', '#318CE7', '<b>Hyvan</b><br />B2/2');
$contenusCellules[] = new PlanningCellule(4, '14:00:00', '15:30:00', '#008000', '<b>Senkito</b><br />A2/1');
$contenusCellules[] = new PlanningCellule(4, '16:00:00', '17:30:00', '#008000', '<b>Genova</b><br />B2/2');
$contenusCellules[] = new PlanningCellule(5, '12:30:00', '14:00:00', '#318CE7', '<b>Nagi</b><br />A1/2');
$contenusCellules[] = new PlanningCellule(5, '14:00:00', '15:30:00', '#008000', '<b>Fabb</b><br />B2/1');
$contenusCellules[] = new PlanningCellule(6, '10:30:00', '12:00:00', '#008000', '<b>Naholry</b><br />B1/2');

$planning = new Planning(1, 6, 540, 1260, 30, $contenusCellules);
$planning->afficherHtmlTable();
?>
</div>
</body>
</html>
