DROP TABLE IF EXISTS COTISATION;
DROP TABLE IF EXISTS RESERVATION;
DROP TABLE IF EXISTS COURS;
DROP TABLE IF EXISTS ADHERENTS;
DROP TABLE IF EXISTS PONEY;
DROP TABLE IF EXISTS MONITEUR;

-- Table PONEY
CREATE TABLE PONEY (
    id_p int PRIMARY KEY,
    nom_p VARCHAR(100),
    age_p int,
    poids_p int,
    disponible boolean,
    temps_repos time,
    capacite_max int
);

-- Table MONITEUR
CREATE TABLE MONITEUR (
    id_m int PRIMARY KEY,
    nom_m VARCHAR(100),
    prenom_m VARCHAR(100),
    email_m VARCHAR(100)
);

-- Table ADHERENTS
CREATE TABLE ADHERENTS (
    id_a int PRIMARY KEY,
    nom_a VARCHAR(100),
    prenom_a VARCHAR(100),
    email_a VARCHAR(100),
    poids_a int,
    date_inscription date,
    cotisation_valide boolean
);

-- Table COTISATION avec FK vers ADHERENTS
CREATE TABLE COTISATION (
    id_c int PRIMARY KEY,
    adherent_id int,
    date_debut date,
    date_fin date,
    montant int,
    CONSTRAINT fk_adherent FOREIGN KEY (adherent_id) REFERENCES ADHERENTS(id_a) ON DELETE CASCADE
);

-- Table COURS avec FK vers MONITEUR
CREATE TABLE COURS (
    id_cours int PRIMARY KEY,
    date_cours date,
    heure_debut time,
    duree int,
    places_max int,
    moniteur_id int,
    cours_collectif boolean,
    categorie VARCHAR(100),
    CONSTRAINT fk_moniteur FOREIGN KEY (moniteur_id) REFERENCES MONITEUR(id_m) ON DELETE SET NULL
);

-- Table RESERVATION avec FK vers ADHERENTS, COURS, et PONEY
CREATE TABLE RESERVATION (
    id_r int PRIMARY KEY,
    adherent_id int,
    cours_id int,
    poney_id int,
    date_reservation date,
    CONSTRAINT fk_adherent_reservation FOREIGN KEY (adherent_id) REFERENCES ADHERENTS(id_a) ON DELETE CASCADE,
    CONSTRAINT fk_cours_reservation FOREIGN KEY (cours_id) REFERENCES COURS(id_cours) ON DELETE CASCADE,
    CONSTRAINT fk_poney_reservation FOREIGN KEY (poney_id) REFERENCES PONEY(id_p) ON DELETE SET NULL
);

--Insertions
INSERT INTO PONEY (id_p, nom_p, age_p, poids_p, disponible, temps_repos, capacite_max)
VALUES 
(1, 'Thunder', 7, 300, TRUE, '01:00:00', 100),
(2, 'Lightning', 5, 320, TRUE, '01:30:00', 120);

INSERT INTO MONITEUR (id_m, nom_m, prenom_m, email_m)
VALUES 
(1, 'Dupont', 'Jean', 'jean.dupont@example.com'),
(2, 'Martin', 'Sophie', 'sophie.martin@example.com');

INSERT INTO ADHERENTS (id_a, nom_a, prenom_a, email_a, poids_a, date_inscription, cotisation_valide)
VALUES 
(1, 'Durand', 'Pierre', 'pierre.durand@example.com', 80, '2023-09-01', TRUE),
(2, 'Lemoine', 'Claire', 'claire.lemoine@example.com', 65, '2023-09-01', TRUE);

INSERT INTO COTISATION (id_c, adherent_id, date_debut, date_fin, montant)
VALUES 
(1, 1, '2023-09-01', '2024-08-31', 200),
(2, 2, '2023-09-01', '2024-08-31', 200);

INSERT INTO COURS (id_cours, date_cours, heure_debut, duree, places_max, moniteur_id, cours_collectif, categorie)
VALUES 
(1, '2024-09-20', '10:00:00', 1, 10, 1, TRUE, 'Débutant'),
(2, '2024-09-21', '14:00:00', 2, 1, 2, FALSE, 'Intermédiaire');

INSERT INTO RESERVATION (id_r, adherent_id, cours_id, poney_id, date_reservation)
VALUES 
(1, 1, 1, 1, '2024-09-15'), -- Réservation par Pierre Durand pour un cours collectif dirigé par Jean Dupont avec Thunder
(2, 2, 2, 2, '2024-09-15'); -- Réservation par Claire Lemoine pour un cours particulier avec Sophie Martin et Lightning

--tests

-- Cette requête devrait supprimer l'adhérent 1 et aussi ses cotisations et réservations associées
DELETE FROM ADHERENTS WHERE id_a = 1;

-- Cette requête devrait mettre la colonne poney_id à NULL dans les réservations associées au poney 2
DELETE FROM PONEY WHERE id_p = 2;

-- Cette requête mettra moniteur_id à NULL pour les cours associés à Sophie Martin (id_m = 2)
DELETE FROM MONITEUR WHERE id_m = 2;