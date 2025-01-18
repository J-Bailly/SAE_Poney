<?php
namespace Classes\Form;

use PDO;
use PDOException;

class Database {
    private $pdo;

    public function __construct($dbPath) {
        try {
            $this->pdo = new PDO("sqlite:" . $dbPath);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->initializeDatabase();
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    private function initializeDatabase() {
        // Définir les requêtes de création de tables et de trigger
        $sql = "
        -- Création de la table log_reserver
        CREATE TABLE IF NOT EXISTS log_reserver (
            id_log INTEGER PRIMARY KEY AUTOINCREMENT,
            message VARCHAR(255),
            date_heure TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
        
        -- Création de la table MONITEUR
        CREATE TABLE IF NOT EXISTS MONITEUR (
            id_moniteur INTEGER PRIMARY KEY AUTOINCREMENT,
            nom VARCHAR(50) NOT NULL,
            prenom VARCHAR(50) NOT NULL,
            email VARCHAR(100) NOT NULL
        );
    
        -- Création de la table PONEY
        CREATE TABLE IF NOT EXISTS PONEY (
            id_poney INTEGER PRIMARY KEY AUTOINCREMENT,
            nom VARCHAR(50) NOT NULL,
            age INTEGER NOT NULL,
            poids DECIMAL(5, 2) NOT NULL,
            disponible BOOLEAN DEFAULT TRUE,
            temps_repos INTEGER DEFAULT 60, -- en minutes
            poids_max DECIMAL(5, 2) NOT NULL
        );
    
        -- Création de la table MEMBRE avec ajout du mot de passe
        CREATE TABLE IF NOT EXISTS MEMBRE (
            id_membre INTEGER PRIMARY KEY AUTOINCREMENT,
            nom VARCHAR(50) NOT NULL,
            prenom VARCHAR(50) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,  -- Contrainte UNIQUE sur email
            password VARCHAR(255) NOT NULL,      -- Mot de passe hashé
            poids DECIMAL(5, 2) NOT NULL,
            date_inscription DATE NOT NULL,
            cotisation_valide BOOLEAN DEFAULT FALSE
        );
        
        -- Trigger pour empêcher l'insertion de doublons d'email
        CREATE TRIGGER IF NOT EXISTS check_email_unique
        BEFORE INSERT ON MEMBRE
        FOR EACH ROW
        BEGIN
            SELECT CASE
                WHEN (SELECT COUNT(*) FROM MEMBRE WHERE email = NEW.email) > 0 THEN
                    RAISE (ABORT, 'Cet email est déjà utilisé.')
            END;
        END;
    
        -- Création de la table ANNEE
        CREATE TABLE IF NOT EXISTS ANNEE (
            annee INTEGER PRIMARY KEY
        );
    
        -- Création de la table COURS
        CREATE TABLE IF NOT EXISTS COURS (
            id_cours INTEGER PRIMARY KEY AUTOINCREMENT,
            date DATE NOT NULL,
            heure_debut TIME NOT NULL,
            duree INTEGER CHECK (duree IN (60, 120)),
            place_max INTEGER CHECK (place_max <= 10),
            cours_collectif BOOLEAN DEFAULT FALSE,
            categorie VARCHAR(50),
            prix DECIMAL(8, 2) NOT NULL
        );
    
        -- Création de la table RESERVER
        CREATE TABLE IF NOT EXISTS RESERVER (
            id_reservation INTEGER PRIMARY KEY AUTOINCREMENT,
            id_membre INTEGER,
            id_poney INTEGER,
            id_cours INTEGER,
            FOREIGN KEY (id_membre) REFERENCES MEMBRE(id_membre),
            FOREIGN KEY (id_poney) REFERENCES PONEY(id_poney),
            FOREIGN KEY (id_cours) REFERENCES COURS(id_cours)
        );
    
        -- Création de la table PAYER
        CREATE TABLE IF NOT EXISTS PAYER (
            id_paiement INTEGER PRIMARY KEY AUTOINCREMENT,
            id_membre INTEGER,
            annee INTEGER,
            date_debut DATE NOT NULL,
            date_fin DATE NOT NULL,
            FOREIGN KEY (id_membre) REFERENCES MEMBRE(id_membre),
            FOREIGN KEY (annee) REFERENCES ANNEE(annee)
        );
    
        -- Création de la table DIRIGER
        CREATE TABLE IF NOT EXISTS DIRIGER (
            id_moniteur INTEGER,
            id_cours INTEGER,
            PRIMARY KEY (id_moniteur, id_cours),
            FOREIGN KEY (id_moniteur) REFERENCES MONITEUR(id_moniteur),
            FOREIGN KEY (id_cours) REFERENCES COURS(id_cours)
        );
        ";
    
        // Exécution des requêtes pour créer les tables
        try {
            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }    

    public function inscrireMembre($nom, $prenom, $email, $password, $poids, $dateInscription) {
        // Hash du mot de passe avant de l'enregistrer
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO MEMBRE (nom, prenom, email, password, poids, date_inscription, cotisation_valide)
                VALUES (:nom, :prenom, :email, :password, :poids, :dateInscription, FALSE)";  // Cotisation non valide par défaut
    
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':password' => $hashedPassword,
                ':poids' => $poids,
                ':dateInscription' => $dateInscription
            ]);
            return $this->pdo->lastInsertId();  // Renvoie l'ID du membre inscrit
        } catch (PDOException $e) {
            if ($e->getCode() == '23505') {  // Code d'erreur pour violation de contrainte UNIQUE
                echo 'Erreur : Cet email est déjà utilisé.';
            } else {
                echo 'Erreur lors de l\'inscription : ' . $e->getMessage();
            }
            return false;
        }
    }
    
    
    public function inscrireCours($idMembre, $idPoney, $idCours) {
        // Vérifier si la cotisation est valide pour le membre
        if (!$this->cotisationValide($idMembre)) {
            echo 'Erreur : La cotisation du membre n\'est pas valide.';
            return false;
        }
    
        $sql = "INSERT INTO RESERVER (id_membre, id_poney, id_cours) 
                VALUES (:idMembre, :idPoney, :idCours)";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':idMembre' => $idMembre,
                ':idPoney' => $idPoney,
                ':idCours' => $idCours
            ]);
            return true;
        } catch (PDOException $e) {
            echo 'Erreur lors de l\'inscription au cours : ' . $e->getMessage();
            return false;
        }
    }

    public function validerCotisation($idMembre, $annee, $dateDebut, $dateFin) {
        $sql = "INSERT INTO PAYER (id_membre, annee, date_debut, date_fin) 
                VALUES (:idMembre, :annee, :dateDebut, :dateFin)";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':idMembre' => $idMembre,
                ':annee' => $annee,
                ':dateDebut' => $dateDebut,
                ':dateFin' => $dateFin
            ]);
    
            // Mettre à jour la cotisation du membre dans la table MEMBRE
            $this->updateCotisationMembre($idMembre, true);
            return true;
        } catch (PDOException $e) {
            echo 'Erreur lors de la validation de la cotisation : ' . $e->getMessage();
            return false;
        }
    }
    
    private function updateCotisationMembre($idMembre, $status) {
        $sql = "UPDATE MEMBRE SET cotisation_valide = :status WHERE id_membre = :idMembre";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':status' => $status,
                ':idMembre' => $idMembre
            ]);
            return true;
        } catch (PDOException $e) {
            echo 'Erreur lors de la mise à jour de la cotisation : ' . $e->getMessage();
            return false;
        }
    }

    public function cotisationValide($idMembre) {
        $sql = "SELECT cotisation_valide FROM MEMBRE WHERE id_membre = :idMembre";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':idMembre' => $idMembre]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['cotisation_valide'];  // Retourne TRUE ou FALSE
        } catch (PDOException $e) {
            echo 'Erreur lors de la vérification de la cotisation : ' . $e->getMessage();
            return false;
        }
    }

    public function dirigerCours($idMoniteur, $idCours) {
        $sql = "INSERT INTO DIRIGER (id_moniteur, id_cours) 
                VALUES (:idMoniteur, :idCours)";
    
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':idMoniteur' => $idMoniteur,
                ':idCours' => $idCours
            ]);
            return true;
        } catch (PDOException $e) {
            echo 'Erreur lors de l\'inscription du moniteur au cours : ' . $e->getMessage();
            return false;
        }
    }

    public function getCoursMembre($idMembre) {
        $sql = "SELECT COURS.* FROM RESERVER
                JOIN COURS ON RESERVER.id_cours = COURS.id_cours
                WHERE RESERVER.id_membre = :idMembre";
    
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':idMembre' => $idMembre]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Erreur lors de la récupération des cours : ' . $e->getMessage();
            return false;
        }
    }    
}
?>
