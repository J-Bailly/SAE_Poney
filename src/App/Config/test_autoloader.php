<?php
require_once 'Autoloader.php';

Autoloader::register();

use Provider\DataLoaderJson;

// Testez la classe
try {
    $loader = new DataLoaderJson(__DIR__ . '/../../../model.json');
    echo "Autoloader fonctionne, classe chargée.";
} catch (Exception $e) {
    echo $e->getMessage();
}
