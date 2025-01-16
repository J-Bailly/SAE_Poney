<?php
// Informations de connexion
$servername = "servinfo-maria";
$username = "deflandre";
$password = "David.15112005";
$dbname = "DBdeflandre";

try {
    // Connexion à la base de données avec PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    // Configuration du mode d'erreur PDO pour générer des exceptions
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion réussie!<br>";

    // Préparation de l'insertion SQL
    $sql = "INSERT INTO RESERVER (id_membre, id_poney, id_cours) VALUES (:id_membre, :id_poney, :id_cours)";

    // Préparer la requête
    $stmt = $conn->prepare($sql);

    // Lier les paramètres
    $stmt->bindParam(':id_membre', $id_membre);
    $stmt->bindParam(':id_poney', $id_poney);
    $stmt->bindParam(':id_cours', $id_cours);

    // Définir les valeurs des paramètres
    $id_membre = 3;
    $id_poney = 1;
    $id_cours = 1;

    // Exécuter la requête
    $stmt->execute();
    echo "Nouvelle réservation ajoutée avec succès!";
    
} catch(PDOException $e) {
    // Gestion des erreurs de connexion ou d'insertion
    echo "Erreur : " . $e->getMessage();
}

// Fermer la connexion
$conn = null;
?>