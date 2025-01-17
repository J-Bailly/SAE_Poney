<?php
require_once '../Classes/Autoloader.php';
Autoloader::register();

use App\UserManager;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $manager = new UserManager();
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        echo "Les mots de passe ne correspondent pas.";
        exit;
    }

    if ($manager->registerUser($name, $email, $password)) {
        header('Location: login.php');
        exit;
    } else {
        echo "Une erreur est survenue lors de l'inscription.";
    }
}
?>
