-- SQLite Database Schema
-- Conversion du SQL MySQL vers SQLite

-- Table Joueur
CREATE TABLE IF NOT EXISTS Joueur (
    Id_Joueur INTEGER PRIMARY KEY AUTOINCREMENT,
    Num_Licence INTEGER NOT NULL UNIQUE,
    Nom VARCHAR(50),
    Prenom VARCHAR(50),
    DateNaissance DATE,
    Taille DECIMAL(15,2),
    Poids DECIMAL(15,2),
    Poste VARCHAR(50),
    Numero INTEGER,
    Statut VARCHAR(50)
);

-- Table Match_ (le nom est échappé car c'est un mot réservé en SQL)
CREATE TABLE IF NOT EXISTS `Match_` (
    Id_Match INTEGER PRIMARY KEY AUTOINCREMENT,
    Date_Rencontre DATE,
    Heure TIME,
    Nom_Equipe_Adverse VARCHAR(50),
    Lieu VARCHAR(50),
    Score_Nous INTEGER,
    Score_Adverse INTEGER,
    Resultat VARCHAR(50)
);

-- Table Commentaire
CREATE TABLE IF NOT EXISTS Commentaire (
    Id_Commentaire INTEGER PRIMARY KEY AUTOINCREMENT,
    Description TEXT,
    Date_ DATE,
    Id_Joueur INTEGER NOT NULL,
    FOREIGN KEY(Id_Joueur) REFERENCES Joueur(Id_Joueur) ON DELETE CASCADE
);

-- Table Participer (table de liaison entre Joueur et Match_)
CREATE TABLE IF NOT EXISTS Participer (
    Id_participation INTEGER PRIMARY KEY AUTOINCREMENT,
    Id_Joueur INTEGER NOT NULL,
    Id_Match INTEGER NOT NULL,
    Poste VARCHAR(50),
    Note INTEGER CHECK (Note >= 0 AND Note <= 5),
    Titulaire_ou_pas BOOLEAN,
    FOREIGN KEY(Id_Joueur) REFERENCES Joueur(Id_Joueur) ON DELETE CASCADE,
    FOREIGN KEY(Id_Match) REFERENCES `Match_`(Id_Match) ON DELETE CASCADE,
    UNIQUE(Id_Joueur, Id_Match)
);

-- Insertion de données de test
INSERT OR IGNORE INTO Joueur (Num_Licence, Nom, Prenom, DateNaissance, Taille, Poids, Poste, Numero, Statut) VALUES
(1001, 'Dupont', 'Jean', '1995-05-15', 1.80, 75.5, 'Attaquant', 7, 'Actif'),
(1002, 'Martin', 'Pierre', '1998-08-22', 1.75, 70.2, 'Milieu', 8, 'Actif'),
(1003, 'Durand', 'Luc', '2000-03-10', 1.85, 78.0, 'Défenseur', 4, 'Actif');

INSERT OR IGNORE INTO `Match_` (Date_Rencontre, Heure, Nom_Equipe_Adverse, Lieu, Score_Nous, Score_Adverse, Resultat) VALUES
('2025-12-20', '18:00:00', 'Les Champions FC', 'Stade Municipal', 3, 2, 'Victoire'),
('2025-12-27', '20:30:00', 'Les Titans', 'Stade Olympique', 1, 1, 'Nul');

INSERT OR IGNORE INTO Commentaire (Description, Date_, Id_Joueur) VALUES
('Très bon match, excellente performance offensive', '2025-11-21', 1),
('Doit travailler sa défense', '2025-11-21', 3);
