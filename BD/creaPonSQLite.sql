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