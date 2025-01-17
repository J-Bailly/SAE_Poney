<?php

return [
    '/' => [\App\Controllers\HomeController::class, 'index'],
    '/login' => [\App\Controllers\AuthController::class, 'login'],
    '/signup' => [\App\Controllers\AuthController::class, 'signup'],
    '/planning' => [\App\Controllers\PlanningController::class, 'show'],
];
?>
