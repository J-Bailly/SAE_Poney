<?php require 'Template/template.php'; ?>

<html>
<head>
    <title>Démo planning dynamique</title>
    <link rel="stylesheet" href="test.css" />
</head>
<body>
<div id="planning">
<?php

include_once 'Planning.php';
include_once 'PlanningCellule.php';
include_once 'PlanningMapper.php';

$planning = new Planning(1, 6, 540, 1260, 30, $contenusCellules);
$planning->afficherHtmlTable();
?>
</div>
</body>
</html>

