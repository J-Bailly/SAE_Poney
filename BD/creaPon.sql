-- CREATION DE LA BD

-- Création de la table log_reserver
CREATE TABLE log_reserver (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    message VARCHAR(255),
    date_heure TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Création de la table MONITEUR
CREATE TABLE MONITEUR (
    id_moniteur INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL
);

-- Création de la table PONEY
CREATE TABLE PONEY (
    id_poney INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    age INT NOT NULL,
    poids DECIMAL(5, 2) NOT NULL,
    disponible BOOLEAN DEFAULT TRUE,
    temps_repos INT DEFAULT 60, -- en minutes
    poids_max DECIMAL(5, 2) NOT NULL
);

-- Création de la table MEMBRE (anciennement ADHERENTS)
CREATE TABLE MEMBRE (
    id_membre INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    poids DECIMAL(5, 2) NOT NULL,
    date_inscription DATE NOT NULL,
    cotisation_valide BOOLEAN DEFAULT FALSE
);

-- Création de la table ANNEE
CREATE TABLE ANNEE (
    annee INT PRIMARY KEY
);

-- Création de la table COURS avec ajout du prix
CREATE TABLE COURS (
    id_cours INT PRIMARY KEY AUTO_INCREMENT,
    date DATE NOT NULL,
    heure_debut TIME NOT NULL,
    duree INT CHECK (duree IN (60, 120)), -- 1h = 60 minutes ou 2h = 120 minutes
    place_max INT CHECK (place_max <= 10), -- Limitation à 10 personnes maximum
    cours_collectif BOOLEAN DEFAULT FALSE,
    categorie VARCHAR(50),
    prix DECIMAL(8, 2) NOT NULL -- Nouveau champ prix pour les cours
);

-- Création de la table RESERVER pour gérer la réservation entre un membre, un poney et un cours
CREATE TABLE RESERVER (
    id_reservation INT PRIMARY KEY AUTO_INCREMENT,
    id_membre INT,
    id_poney INT,
    id_cours INT,
    FOREIGN KEY (id_membre) REFERENCES MEMBRE(id_membre),
    FOREIGN KEY (id_poney) REFERENCES PONEY(id_poney),
    FOREIGN KEY (id_cours) REFERENCES COURS(id_cours)
);

-- Création de la table PAYER pour gérer les paiements
CREATE TABLE PAYER (
    id_paiement INT PRIMARY KEY AUTO_INCREMENT,
    id_membre INT,
    annee INT,
    date_debut DATE NOT NULL, -- Champs déplacés ici
    date_fin DATE NOT NULL,   -- Champs déplacés ici
    FOREIGN KEY (id_membre) REFERENCES MEMBRE(id_membre),
    FOREIGN KEY (annee) REFERENCES ANNEE(annee)
);

-- Création de la table DIRIGER pour gérer l'association entre un moniteur et un cours
CREATE TABLE DIRIGER (
    id_moniteur INT,
    id_cours INT,
    PRIMARY KEY (id_moniteur, id_cours),
    FOREIGN KEY (id_moniteur) REFERENCES MONITEUR(id_moniteur),
    FOREIGN KEY (id_cours) REFERENCES COURS(id_cours)
);

-- Créer le trigger mis à jour
DELIMITER //

CREATE TRIGGER log_insert_reserver
AFTER INSERT ON RESERVER
FOR EACH ROW
BEGIN
    -- Déclarations des variables
    DECLARE poney_poids_max DECIMAL(5, 2);
    DECLARE membre_poids DECIMAL(5, 2);
    DECLARE poney_disponible BOOLEAN;
    DECLARE cours_duree INT;
    DECLARE dernier_cours_datetime DATETIME;
    DECLARE temps_repos INT;
    DECLARE nombre_reservations INT;
    DECLARE cours_date DATE;
    DECLARE cours_heure TIME;

    -- Récupérer le poids du membre, le poids maximal du poney, la disponibilité du poney et la durée du cours
    SELECT poids INTO membre_poids FROM MEMBRE WHERE id_membre = NEW.id_membre;
    SELECT poids_max INTO poney_poids_max FROM PONEY WHERE id_poney = NEW.id_poney;
    SELECT disponible INTO poney_disponible FROM PONEY WHERE id_poney = NEW.id_poney;
    SELECT duree, date, heure_debut INTO cours_duree, cours_date, cours_heure FROM COURS WHERE id_cours = NEW.id_cours;

    -- Vérifier si le membre dépasse le poids maximal du poney
    IF membre_poids > poney_poids_max THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Le poids du cavalier dépasse le poids maximal du poney.';
    END IF;

    -- Vérifier si le poney est disponible
    IF NOT poney_disponible THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Le poney n\'est pas disponible pour cette réservation.';
    END IF;

    -- Calculer le temps de repos nécessaire en fonction de la durée du cours
    IF cours_duree = 120 THEN
        SET temps_repos = 120; -- 2 heures de cours nécessitent 2 heures de repos
    ELSE
        SET temps_repos = 60; -- 1 heure de cours nécessite 1 heure de repos
    END IF;

    -- Vérifier le temps de repos entre deux réservations pour le même poney
    SELECT MAX(COALESCE( CONCAT(date, ' ', heure_debut), '1970-01-01 00:00:00')) INTO dernier_cours_datetime
    FROM RESERVER
    JOIN COURS ON RESERVER.id_cours = COURS.id_cours
    WHERE RESERVER.id_poney = NEW.id_poney
      AND COURS.date = cours_date -- Vérifier la même date
      AND COURS.heure_debut < cours_heure; -- Vérifier l'heure de début avant la réservation

    -- Vérifier si le poney a assez de temps de repos avant cette réservation
    IF dernier_cours_datetime != '1970-01-01 00:00:00' AND TIMESTAMPDIFF(MINUTE, dernier_cours_datetime, CONCAT(cours_date, ' ', cours_heure)) < temps_repos THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Le poney n\'a pas assez de temps de repos avant cette réservation.';
    END IF;

    -- Vérifier si le nombre de places du cours est atteint (pour les cours collectifs)
    IF cours_duree = 60 THEN
        SELECT COUNT(*) INTO nombre_reservations
        FROM RESERVER
        WHERE id_cours = NEW.id_cours;
        IF nombre_reservations >= 10 THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Le cours est déjà complet.';
        END IF;
    END IF;

    -- Insérer le message dans le log_reserver
    INSERT INTO log_reserver (message)
    VALUES (CONCAT('Nouvelle réservation : Membre ID = ', NEW.id_membre, ', Poney ID = ', NEW.id_poney, ', Cours ID = ', NEW.id_cours, ', Date/Heure = ', CONCAT(cours_date, ' ', cours_heure)));

END //

DELIMITER ;