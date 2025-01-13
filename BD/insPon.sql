-- Test des insertions dans les tables

-- Vérification de la table log_reserver après une réservation

-- Insérer un membre de test
INSERT INTO MEMBRE (nom, prenom, email, poids, date_inscription, cotisation_valide) 
VALUES ('Test', 'Membre', 'test.membre@example.com', 120.0, '2023-11-18', TRUE);

-- Insérer un poney de test
INSERT INTO PONEY (nom, age, poids, disponible, temps_repos, poids_max) 
VALUES ('Tempête', 6, 280.5, TRUE, 60, 110.0);

-- Insérer un cours de test
INSERT INTO COURS (date, heure_debut, duree, place_max, cours_collectif, categorie, prix) 
VALUES ('2023-11-25', '09:00:00', 60, 10, TRUE, 'Collectif', 20.00);

-- Insérer une réservation valide (suffisamment de temps de repos)
INSERT INTO RESERVER (id_membre, id_poney, id_cours) 
VALUES (1, 1, 1); -- Test, membre 1 réserve Tempête pour le cours collectif du 25 novembre

-- Vérifier la table log_reserver pour s'assurer que l'enregistrement est bien inséré
SELECT * FROM log_reserver;

-- Insérer un autre membre, poney et cours
INSERT INTO MEMBRE (nom, prenom, email, poids, date_inscription, cotisation_valide) 
VALUES ('Julie', 'Cavalier', 'julie.cavalier@example.com', 65.0, '2023-11-18', TRUE);

INSERT INTO PONEY (nom, age, poids, disponible, temps_repos, poids_max) 
VALUES ('Aigle', 5, 290.5, TRUE, 60, 115.0);

INSERT INTO COURS (date, heure_debut, duree, place_max, cours_collectif, categorie, prix) 
VALUES ('2023-11-26', '14:00:00', 120, 1, FALSE, 'Particulier', 40.00);

-- Insérer une réservation valide pour Aigle (suffisamment de temps de repos)
INSERT INTO RESERVER (id_membre, id_poney, id_cours) 
VALUES (2, 2, 2); -- Julie réserve Aigle pour le cours particulier du 26 novembre

-- Vérifier la table log_reserver après les nouvelles réservations
SELECT * FROM log_reserver;

-- Insérer une nouvelle réservation pour Tempête le 26 novembre, qui devrait échouer
-- Cette réservation échouera car le poney n'a pas assez de temps de repos (moins de 60 minutes)
INSERT INTO RESERVER (id_membre, id_poney, id_cours) 
VALUES (1, 1, 2); -- Test, membre 1 réserve Tempête pour le cours particulier du 26 novembre

-- Vérifier de nouveau la table log_reserver après la tentative de réservation invalide
SELECT * FROM log_reserver;