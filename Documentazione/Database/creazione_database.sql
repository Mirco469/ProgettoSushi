SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS Utente;

CREATE TABLE Utente(
	username      	VARCHAR(20) PRIMARY KEY,
    nome          	VARCHAR(20) NOT NULL,
    cognome       	VARCHAR(20) NOT NULL,
    password      	VARCHAR(20) NOT NULL,
	autorizzazione 	ENUM ('Utente','Admin') NOT NULL,
	numero_carta 	VARCHAR(16),
	intestatario	VARCHAR(40),
	scadenza		DATE
);

DROP TABLE IF EXISTS Recensione;

CREATE TABLE Recensione(
	id_recensione 	INT PRIMARY KEY,
	titolo		  	VARCHAR(30) NOT NULL,
	testo			VARCHAR(150) NOT NULL,
	data			DATE NOT NULL,
	utente 		VARCHAR(20) NOT NULL,
	FOREIGN KEY(utente) REFERENCES Utente(username) ON DELETE CASCADE
);

DROP TABLE IF EXISTS News;

CREATE TABLE News(
	id_news		 	INT PRIMARY KEY,
	titolo		  	VARCHAR(30) NOT NULL,
	descrizione		VARCHAR(150),
	data			DATE NOT NULL,
	utente 			VARCHAR(20) NOT NULL,
	FOREIGN KEY(utente) REFERENCES Utente(username) ON DELETE CASCADE
);

DROP TABLE IF EXISTS Destinazione;

CREATE TABLE Destinazione(
	id_destinazione		INT PRIMARY KEY,
	nome				VARCHAR(20) NOT NULL,
	cognome				VARCHAR(20) NOT NULL,
	numero_telefonico	VARCHAR(15),
	CAP					VARCHAR(5) NOT NULL,
	via 				VARCHAR(15) NOT NULL,
	numero_civico		SMALLINT NOT NULL,
	utente 				VARCHAR(20) NOT NULL,	
	FOREIGN KEY(utente) REFERENCES Utente(username) ON DELETE CASCADE
);

DROP TABLE IF EXISTS Ordine;

CREATE TABLE Ordine(
	id_ordine		INT PRIMARY KEY,
	data_ordine		DATE NOT NULL,
	data_consegna	DATE NOT NULL,
	totale			FLOAT NOT NULL,
	destinazione	INT NOT NULL,
	FOREIGN KEY(destinazione) REFERENCES Destinazione(id_destinazione) ON DELETE NO ACTION
);

DROP TABLE IF EXISTS Prodotto;

CREATE TABLE Prodotto(
	nome 			VARCHAR(30) PRIMARY KEY,
	categoria		ENUM('Antipasti','Primi Piatti','Teppanyako e tempure','Uramaki','Nigiri e Onigiri','Gunkan','Temaki','Hosomaki','Sashimi','Dessert') NOT NULL,
	pezzi			TINYINT NOT NULL,
	prezzo 			FLOAT NOT NULL
);

DROP TABLE IF EXISTS contiene;

CREATE TABLE contiene(
    id_ordine 		INT,
    nome 			VARCHAR(30),
    numero_porzioni TINYINT NOT NULL,
    PRIMARY KEY(id_ordine, nome),
	FOREIGN KEY(id_ordine) REFERENCES Ordine(id_ordine) ON DELETE CASCADE,
	FOREIGN KEY(nome) REFERENCES Prodotto(nome) ON DELETE NO ACTION
);

SET FOREIGN_KEY_CHECKS=1;