SET FOREIGN_KEY_CHECKS=0;

-- Crea la tabella Utente

DROP TABLE IF EXISTS Utente;

CREATE TABLE Utente(
	username      		VARCHAR(20) PRIMARY KEY,
    nome          	VARCHAR(20) NOT NULL,
    cognome       	VARCHAR(20) NOT NULL,
    password      	VARCHAR(20) NOT NULL,
	autorizzazione 		ENUM ('Utente','Admin') NOT NULL,
	numero_carta 			VARCHAR(16),
	intestatario			VARCHAR(40),
	scadenza					DATE
);

-- Inserimento dati nella tabella Utente

INSERT INTO Utente (username, nome, cognome, password, autorizzazione, numero_carta, intestatario, scadenza) VALUES (
('admin', 'Admin', 'Generico', 'admin', 'Admin', NULL, NULL, NULL),
('ammin1', 'Amministratore', 'Due', 'password', 'Admin', NULL, NULL, NULL),
('ammin2', 'Amministratore', 'Tre', 'password', 'Admin', NULL, NULL, NULL),
('user', 'Utente', 'Generico', 'user', 'Utente', '1111222233334444', 'Utente Generico', '2021-11-00'),
('user1', 'User', 'Uno', 'password', 'Utente', '5555222211116666', 'User Uno', '2024-01-00'),
('user2', 'User', 'Due', 'password', 'Utente', '4444333388880000', 'User Due', '2027-06-00'),
('user3', 'User', 'Tre', 'password', 'Utente', NULL, NULL, NULL),
('user4', 'User', 'Quattro', 'password', 'Utente', NULL, NULL, NULL),
('user5', 'User', 'Cinque', 'password', 'Utente', '2222111100009999', 'User Cinque', '2025-09-00')
)

-- Crea la tabella Recensione

DROP TABLE IF EXISTS Recensione;

CREATE TABLE Recensione(
	id_recensione 	INT PRIMARY KEY,
	titolo		  		VARCHAR(30) NOT NULL,
	testo						VARCHAR(150) NOT NULL,
	data						DATE NOT NULL,
	utente 					VARCHAR(20) NOT NULL,
	FOREIGN KEY (utente) REFERENCES Utente(username) ON DELETE CASCADE
);

-- Inserimento dati nella tabella Recensione

INSERT INTO Recensione (id_recensione, titolo, testo, data, utente) VALUES (
(001, 'Ristorante TOP', 'Sono un’appassionata di sushi e credo che questo ristorante possa vantare la migliore qualità e varietà della zona. Uramaki special strepitosi e servizio impeccabile.', '2018-09-11', 'user2'),
(002, 'Bella serata', 'Ogni volta che vengo a Padova mi fermo sempre a cena in questo locale, i ragazzi dello staff sono simpatici e molto professionali, sulla qualità del cibo semplicemente ottimo.', '2019-03-22', 'user1'),
(003, 'Consigliato', 'Bel ristorante in una zona molto accogliente di Padova. Non me ne intendo molto di sushi ma posso dire che lo consiglierò sicuramente ad amici', '2017-11-08', 'user3'),
(004, 'Ho provato di meglio', 'Scoperto l’anno scorso, siamo tornati anche quest’anno. La qualità è sempre ottima sia dei crudi che dei cotti, con molta scelta. Peccato per i tavoli un po’ piccoli, quando arrivano più di due piatti diventa difficile gestire gli spazi. Prezzi e servizio nella media.', '2019-06-27', 'user4')
)

-- Crea la tabella News

DROP TABLE IF EXISTS News;

CREATE TABLE News(
	id_news		 		INT PRIMARY KEY,
	titolo		  	VARCHAR(30) NOT NULL,
	descrizione		VARCHAR(150),
	data					DATE NOT NULL,
	utente 				VARCHAR(20) NOT NULL,
	FOREIGN KEY (utente) REFERENCES Utente(username) ON DELETE CASCADE
);

-- Inserimento dati nella tabella News

INSERT INTO News (id_news, titolo, descrizione, data, utente) VALUES (
(101, 'Ferragosto', 'Vi ricordiamo che dal 12 al 18 Agosto il ristorante rimarra chiuso per ferie.', '2019-08-08', 'ammin1'),
(102, 'Cercasi Cameriera', 'Cercasi cameriera per contratto part-time ', '2019-08-22', 'ammin2'),
(103, 'Black Friday Week', 'Dal 25 al 30 Novembre sconto del 10% su tutti gli ordini superiori ai 30€; Venerdì 29 sconto del 20%.', '2019-11-18', 'admin'),
(104, 'Buon Natale', 'Lo staff di Sushi Nakamura augura a voi ed alle vostre famiglie un felice Natale.', '2019-12-24', 'ammin1'),
(105, 'Felice Anno Nuovo', 'Per festeggiare il nuovo anno, sconto del 10% su tutti gli ordini superiori ai 20€ fino a Domenica 5.', '2020-01-02', 'ammin2')
)

-- Crea la tabella Destinazione

DROP TABLE IF EXISTS Destinazione;

CREATE TABLE Destinazione(
	id_destinazione			INT PRIMARY KEY,
	nome_cognome				VARCHAR(40) NOT NULL,
	numero_telefonico		VARCHAR(15),
	CAP									VARCHAR(5) NOT NULL,
	via 								VARCHAR(15) NOT NULL,
	numero_civico				SMALLINT NOT NULL,
	utente 							VARCHAR(20) NOT NULL,
	FOREIGN KEY (utente) REFERENCES Utente(username) ON DELETE CASCADE
);

-- Inserimento dati nella tabella Destinazione

INSERT INTO Destinazione (id_destinazione, nome_cognome, numero_telefonico, CAP, via, numero_civico, utente) VALUES (
(201, 'Utente Generico', '049XXXXXXX', '35100', 'Aldo Moro', 21, 'utente'),
(202, 'Utente Generico', '049XXXXXXX', '35100', 'Ugo Bassi', 17, 'utente'),
(203, 'User Uno', '346XXXXXXX', '35133', 'Don Stefani', 10, 'user1'),
(204, 'User Due', '339XXXXXXX', '35142', 'Monte Bianco', 15, 'user2'),
(205, 'User Tre', '333XXXXXXX', '35129', 'Andrea Palladio', 17, 'user3'),
(206, 'User Quattro', '340XXXXXXX', '35122', 'Antonio Canova', 11, 'user4'),
(207, 'User Cinque', '348XXXXXXX', '35100', 'Aldo Moro', 22, 'user5')
)

-- Crea la tabella Tabella Ordine

DROP TABLE IF EXISTS Ordine;

CREATE TABLE Ordine(
	id_ordine			INT PRIMARY KEY,
	data_ordine		DATETIME NOT NULL,
	data_consegna		DATETIME NOT NULL,
	totale				FLOAT NOT NULL,
	destinazione	INT NOT NULL,
	FOREIGN KEY (destinazione) REFERENCES Destinazione(id_destinazione) ON DELETE NO ACTION
);

-- Inserimento dati nella tabella Ordine

INSERT INTO Ordine (id_ordine, data_ordine, data_consegna, totale, destinazione) VALUES (
(301, '2019-02-19 12:30:00', '2019-02-19 13:30:00', 27.00, 201),
(302, '2019-03-22 20:00:00', '2019-03-22 21:00:00', 28.00, 202),
(303, '2019-04-11 13:30:00', '2019-04-11 14:30:00', 33.00, 203),
(304, '2019-05-09 19:00:00', '2019-05-09 20:00:00', 31.50, 204),
(305, '2019-05-07 20:30:00', '2019-05-07 21:30:00', 46.50, 205),
(306, '2019-06-12 12:45:00', '2019-06-12 13:45:00', 40.00, 206),
(307, '2019-07-23 13:15:00', '2019-07-23 14:15:00', 36.50, 207),
(308, '2019-08-29 12:30:00', '2019-08-29 13:30:00', 66.00, 201),
(309, '2019-10-16 13:00:00', '2019-10-16 14:00:00', 15.50, 202),
(310, '2020-01-10 20:15:00', '2020-01-10 21:15:00', 42.50, 203),
(311, '2020-01-14 14:00:00', '2020-01-14 15:00:00', 57.50, 204)
)

-- Crea la tabella Tabella Prodotto

DROP TABLE IF EXISTS Prodotto;

CREATE TABLE Prodotto(
	nome 				VARCHAR(30) PRIMARY KEY,
	categoria		ENUM('Antipasti','Primi Piatti','Teppanyako e tempure','Uramaki','Nigiri ed Onigiri','Gunkan','Temaki','Hosomaki','Sashimi','Dessert') NOT NULL,
	pezzi				TINYINT NOT NULL,
	prezzo 			FLOAT NOT NULL
);

-- Inserimento dati nella tabella Prodotto

INSERT INTO Prodotto (nome, categoria, pezzi, prezzo) VALUES (
('Tartara di tonno', 'Antipasti', 1, 6.00),
('Takosu', 'Antipasti', 1, 5.00),
('Goma Wakame', 'Antipasti', 1, 4.00),
('Edamame', 'Antipasti', 1, 3.00),
('Takoyaki', 'Antipasti', 4, 4.00),
('Domò Harumaki', 'Antipasti', 2, 2.00),
('Niku Harumaki', 'Antipasti', 2, 3.00),
('Yasai Gyoza', 'Antipasti', 4, 3.50),

('Ramen Chasumiso', 'Primi Piatti', 1, 7.00),
('Katsu Ramen', 'Primi Piatti', 1, 7.00),
('Black Yakimeshi', 'Primi Piatti', 1, 8.00),
('Shakeyakidon', 'Primi Piatti', 1, 7.50),
('Gyuyaki Noodles', 'Primi Piatti', 1, 7.00),
('Kaisen Udon', 'Primi Piatti', 1, 8.00),

('Yakitori', 'Teppanyako e tempure', 2, 6.00),
('Salmon Kushiyaki', 'Teppanyako e tempure', 2, 5.00),
('Ebi Yaki', 'Teppanyako e tempure', 2, 6.00),
('Salmon Tataki', 'Teppanyako e tempure', 1, 5.50),
('Maguro Tataki', 'Teppanyako e tempure', 1, 5.50),
('Gyu Tataki', 'Teppanyako e tempure', 4, 5.00),
('Yasai Teppanyaki', 'Teppanyako e tempure', 1, 5.00),
('Ebi Tempura', 'Teppanyako e tempure', 3, 6.00),

('Chips Roll', 'Uramaki', 4, 5.00),
('Salmon Philadelphia', 'Uramaki', 4, 4.00),
('Black Ebiten', 'Uramaki', 4, 4.00),
('Black California', 'Uramaki', 4, 4.00),
('Ichigo Hosomaki', 'Uramaki', 6, 4.00),
('California', 'Uramaki', 4, 4.50),
('Domò Roll', 'Uramaki', 4, 5.00),
('Ebiten', 'Uramaki', 4, 4.50),
('Shake Fry Tobiko', 'Uramaki', 4, 4.00),
('Asparago Roll', 'Uramaki', 4, 4.50),

('Nigiri Salmone', 'Nigiri ed Onigiri', 2, 2.00),
('Nigiri Tonno', 'Nigiri ed Onigiri', 2, 2.00),
('Nigiri Branziono', 'Nigiri ed Onigiri', 2, 2.00),
('Onigiri Salmone', 'Nigiri ed Onigiri', 1, 2.00),
('Ebiten Onigiri', 'Nigiri ed Onigiri', 1, 2.00),

('Spicy Tuna', 'Gunkan', 2, 5.00),
('Tobiko', 'Gunkan', 2, 4.50),
('Mango Suzuki', 'Gunkan', 2, 5.00),
('Salmon Philadelphia', 'Gunkan', 2, 4.00),

('Salmon Avocado Temaki', 'Temaki', 1, 4.00),
('Tartara Temaki', 'Temaki', 1, 3.00),
('Tuna Temaki', 'Temaki', 1, 3.00),
('Ebiten Temaki', 'Temaki', 1, 4.00),

('Hosomaki Salmone', 'Hosomaki', 6, 3.00),
('Hosomaki Tonno', 'Hosomaki', 6, 3.00),
('Hosomaki Cetriolo', 'Hosomaki', 6, 2.00),
('Hosomaki Avocado', 'Hosomaki', 6, 2.50),

('Sashimi Salmone', 'Sashimi', 4, 6.00),
('Sashimi Tonno', 'Sashimi', 4, 5.00),
('Sashimi Branzino', 'Sashimi', 4, 6.00),

('Profiteroles', 'Dessert', 1, 6.00),
('Matcha Tiramisù', 'Dessert', 1, 6.50),
('Red Cheesecake', 'Dessert', 1, 5.50),
('Semisfera al Mango', 'Dessert', 1, 6.00),
('Semisfera ai Lamponi', 'Dessert', 1, 5.00)
)

-- Crea la tabella Tabella Contiene

DROP TABLE IF EXISTS Contiene;

CREATE TABLE Contiene(
    id_ordine 			INT,
    nome 						VARCHAR(30),
    numero_porzioni TINYINT NOT NULL,
    PRIMARY KEY (id_ordine, nome),
	FOREIGN KEY (id_ordine) REFERENCES Ordine(id_ordine) ON DELETE CASCADE,
	FOREIGN KEY (nome) REFERENCES Prodotto(nome) ON DELETE NO ACTION
);

-- Inserimento dati nella tabella Contiene

INSERT INTO Contiene (id_ordine, nome, numero_porzioni) VALUES (
(301, 'Tartara di tonno', 1),
(301, 'Kaisen Udon', 1),
(301, 'Salmon Philadelphia', 2),
(301, 'Spicy Tuna', 1),

(302, 'Katsu Ramen', 2),
(302, 'Shake Fry Tobiko', 2),
(302, 'Nigiri Branziono', 3),

(303, 'Ebiten Onigiri', 2),
(303, 'Tuna Temaki', 3),
(303, 'Sashimi Tonno', 2),
(303, 'Semisfera ai Lamponi', 2),

(304, 'Hosomaki Avocado', 3),
(304, 'Sashimi Salmone', 2),
(304, 'Profiteroles', 2),

(305, 'Goma Wakame', 3),
(305, 'Ramen Chasumiso', 2),
(305, 'Salmon Tataki', 1),
(305, 'Yasai Teppanyaki', 3),

(306, 'Domò Harumaki', 4),
(306, 'Ebi Yaki', 4),
(306, 'Ebiten Onigiri', 4),

(307, 'Edamame', 3),
(307, 'Maguro Tataki', 3),
(307, 'Asparago Roll', 2),
(307, 'Onigiri Salmone', 6),

(308, 'Ebi Yaki', 4),
(308, 'Sashimi Salmone', 3),
(308, 'Tuna Temaki', 4),
(308, 'Tartara di tonno', 2),

(309, 'Salmon Philadelphia', 1),
(309, 'Hosomaki Salmone', 2),
(309, 'Red Cheesecake', 1),

(310, 'Spicy Tuna', 2),
(310, 'Sashimi Salmone', 3),
(310, 'Shake Fry Tobiko', 2),
(310, 'Matcha Tiramisù', 2),

(311, 'Hosomaki Avocado', 3),
(311, 'Sashimi Tonno', 4),
(311, 'Profiteroles', 5)
)

SET FOREIGN_KEY_CHECKS=1;
