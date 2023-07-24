CREATE DATABASE IF NOT EXISTS robotTransporteur;
USE robotTransporteur;

-- Création des tables --

-- Table Trajet --
CREATE TABLE trajet (
  idTrajet int(10) NOT NULL AUTO_INCREMENT,
  zoneDepart int NOT NULL,
  zoneArrivee int NOT NULL,
  autonomieDepart int NOT NULL,
  autonomieArrivee int NOT NULL,
  etatTrajet int NOT NULL,
  horodatage time NOT NULL,
  PRIMARY KEY (idTrajet)
);

-- Table Commande --
CREATE TABLE commande (
  idCommande int(10) NOT NULL AUTO_INCREMENT,
  nbCommande int NOT NULL,
  type_commande VARCHAR(255) NOT NULL,
  PRIMARY KEY (idCommande)
);

-- Table Robot --
CREATE TABLE robot (
  idRobot int(10) NOT NULL AUTO_INCREMENT,
  nom varchar(255) DEFAULT NULL,
  autonomie_batterie int NOT NULL,
  etatRobot int NOT NULL,
  PRIMARY KEY (idRobot)
);

-- Table Chemins --
CREATE TABLE chemins (
  idChemins int(10) NOT NULL AUTO_INCREMENT,
  etape_chemin VARCHAR(255) NOT NULL,
  autonomieDepart int DEFAULT NULL,
  PRIMARY KEY (idChemins)
);

-- Insertion des données --
INSERT INTO trajet (idTrajet, zoneDepart, zoneArrivee, autonomieDepart, autonomieArrivee, etatTrajet, horodatage) VALUES
(1, 0010, 0011, 50, 20, 0, NOW()),
(2, 0011, 0010, 70, 30, 1, NOW());

INSERT INTO commande (idCommande, nbCommande, type_commande) VALUES
(1,2 , 'Livraison express'),
(2,3 , 'Retrait de colis'),
(3,5 , 'Livraison standard');

INSERT INTO robot (idRobot, nom, autonomie_batterie, etatRobot) VALUES
(1, 'robot1', 100, 0),
(2, 'robot2', 80, 1),
(3, 'robot3', 90, 0);

INSERT INTO chemins (idChemins, etape_chemin) VALUES
(1, '001090000900019000290011'),
(2, '001090000900039000290011'),
(3, '001190002900039000090010'),
(4, '001190002900019000090010');

