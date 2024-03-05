CREATE TABLE Clients(
   noClient INT,
   nom VARCHAR(62),
   prenom VARCHAR(62),
   email VARCHAR(90),
   mdp VARCHAR(50),
   PRIMARY KEY(noClient)
);

CREATE TABLE Aeroports(
   IATA_CODE VARCHAR(3),
   nomAeroport VARCHAR(77),
   ville VARCHAR(30),
   region VARCHAR(2),
   pays VARCHAR(3),
   latitude VARCHAR(8),
   longitude VARCHAR(10),
   PRIMARY KEY(IATA_CODE)
);

CREATE TABLE Compagnies(
   noCompagnies VARCHAR(120),
   nomCompagnies VARCHAR(120),
   PRIMARY KEY(noCompagnies)
);

CREATE TABLE Vols(
   idVol INT,
   numVol INT,
   dateVol DATE,
   jourSemaine DATE,
   heureDepart TIME NOT NULL,
   heureArrive TIME NOT NULL,
   distance DECIMAL(15,2),
   IATA_CODE VARCHAR(3) NOT NULL,
   IATA_CODE_1 VARCHAR(3) NOT NULL,
   noCompagnies VARCHAR(120) NOT NULL,
   PRIMARY KEY(idVol),
   FOREIGN KEY(IATA_CODE) REFERENCES Aeroports(IATA_CODE),
   FOREIGN KEY(IATA_CODE_1) REFERENCES Aeroports(IATA_CODE),
   FOREIGN KEY(noCompagnies) REFERENCES Compagnies(noCompagnies)
);

CREATE TABLE Billets(
   idBillet INT,
   dateAchat DATE,
   prix INT,
   noClient INT NOT NULL,
   idVol INT NOT NULL,
   PRIMARY KEY(idBillet),
   FOREIGN KEY(noClient) REFERENCES Clients(noClient),
   FOREIGN KEY(idVol) REFERENCES Vols(idVol)
);
