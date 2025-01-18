<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - KavalKennyKlub</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<?php ob_start(); ?>
    <section class="services">
        <h2>Nos services</h2>
        <div class="service-item">
            <h3>Cours d'équitation</h3>
            <p>Nous proposons des cours individuels et collectifs (jusqu'à 10 personnes) pour tous les niveaux, de l’initiation au perfectionnement.</p>
        </div>
        <div class="service-item">
            <img src="https://images.unsplash.com/photo-1599067148286-d04f163c6a5b" alt="Balades à cheval">
            <h3>Balades en pleine nature</h3>
            <p>Découvrez la beauté de la Sologne à cheval lors de nos balades encadrées, adaptées aux débutants et cavaliers confirmés.</p>
        </div>
        <div class="service-item">
            <img src="https://images.unsplash.com/photo-1559599236-02390e0027d3" alt="Soins aux chevaux">
            <h3>Soins aux chevaux</h3>
            <p>Apprenez à prendre soin de nos poneys et chevaux avec des ateliers sur les soins et le bien-être des équidés.</p>
        </div>
    </section>

    <section class="regles">
        <h2>Règles de fonctionnement</h2>
        <ul>
            <li>Les cours sont réservables à l’avance, en individuel ou en groupe (maximum 10 personnes).</li>
            <li>Les poneys doivent avoir une heure de repos après deux heures de cours.</li>
            <li>Un poney ne peut porter un cavalier que si celui-ci respecte une limite de poids définie pour chaque animal.</li>
            <li>Les cotisations annuelles sont à régler en début d’année et chaque cours réservé est facturé individuellement.</li>
        </ul>
    </section>
<?php 
$content = ob_get_clean(); 
require('PHP/Template/header_home.php');
?>
</body>
</html>