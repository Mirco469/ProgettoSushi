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

INSERT INTO Utente (username, nome, cognome, password, autorizzazione, numero_carta, intestatario, scadenza) VALUES 
('admin', 'Admin', 'Generico', 'admin', 'Admin', NULL, NULL, NULL),
('ammin1', 'Amministratore', 'Due', 'password', 'Admin', NULL, NULL, NULL),
('ammin2', 'Amministratore', 'Tre', 'password', 'Admin', NULL, NULL, NULL),
('user', 'Utente', 'Generico', 'user', 'Utente', '1111222233334444', 'Utente Generico', '2021-11-01'),
('user1', 'User', 'Uno', 'password', 'Utente', '5555222211116666', 'User Uno', '2024-01-01'),
('user2', 'User', 'Due', 'password', 'Utente', '4444333388880000', 'User Due', '2027-06-01'),
('user3', 'User', 'Tre', 'password', 'Utente', NULL, NULL, NULL),
('user4', 'User', 'Quattro', 'password', 'Utente', NULL, NULL, NULL),
('user5', 'User', 'Cinque', 'password', 'Utente', '2222111100009999', 'User Cinque', '2025-09-01');

-- Crea la tabella Recensione

DROP TABLE IF EXISTS Recensione;

CREATE TABLE Recensione(
	id_recensione 	INT PRIMARY KEY AUTO_INCREMENT,
	titolo		  		VARCHAR(30) NOT NULL,
	testo						VARCHAR(200) NOT NULL,
	data						DATE NOT NULL,
	utente 					VARCHAR(20) NOT NULL,
	FOREIGN KEY (utente) REFERENCES Utente(username) ON DELETE CASCADE
);

-- Inserimento dati nella tabella Recensione

INSERT INTO Recensione (id_recensione, titolo, testo, data, utente) VALUES 
(001, 'Ristorante TOP', 'Sono un’appassionata di sushi e credo che questo ristorante possa vantare la migliore qualità e varietà della zona. Uramaki special strepitosi e servizio impeccabile.', '2018-09-11', 'user2'),
(002, 'Bella serata', 'Ogni volta che vengo a Padova mi fermo sempre a cena in questo locale, i ragazzi dello staff sono simpatici e molto professionali, sulla qualità del cibo semplicemente ottimo.', '2019-03-22', 'user1'),
(003, 'Consigliato', 'Bel ristorante in una zona molto accogliente di Padova. Non me ne intendo molto di sushi ma posso dire che lo consiglierò sicuramente ad amici.', '2017-11-08', 'user3'),
(004, 'Ho provato di meglio', 'La qualità è sempre ottima sia dei crudi che dei cotti. Peccato per i tavoli un po’ piccoli, quando arrivano più di due piatti diventa difficile gestire gli spazi. Prezzi e servizio nella media.', '2019-06-27', 'user4');

-- Crea la tabella News

DROP TABLE IF EXISTS News;

CREATE TABLE News(
	id_news		 		INT PRIMARY KEY AUTO_INCREMENT,
	titolo		  	VARCHAR(30) NOT NULL,
	descrizione		VARCHAR(150),
	data					DATE NOT NULL,
	utente 				VARCHAR(20) NOT NULL,
	FOREIGN KEY (utente) REFERENCES Utente(username) ON DELETE CASCADE
);


-- Inserimento dati nella tabella News

INSERT INTO News (id_news, titolo, descrizione, data, utente) VALUES 

(1,'Ferragosto', 'Vi ricordiamo che dal 12 al 18 Agosto il ristorante rimarra chiuso per ferie.', '2019-08-08', 'ammin1'),
(2,'Cercasi Cameriera', 'Cercasi cameriera per contratto part-time ', '2019-08-22', 'ammin2'),
(3,'Black Friday Week', 'Dal 25 al 30 Novembre sconto del 10% su tutti gli ordini superiori ai 30€; Venerdì 29 sconto del 20%.', '2019-11-18', 'admin'),
(4,'Buon Natale', 'Lo staff di Sushi Nakamura augura a voi ed alle vostre famiglie un felice Natale.', '2019-12-24', 'ammin1'),
(5,'Felice Anno Nuovo', 'Per festeggiare il nuovo anno, sconto del 10% su tutti gli ordini superiori ai 20€ fino a Domenica 5.', '2020-01-02', 'ammin2');


-- Crea la tabella Destinazione

DROP TABLE IF EXISTS Destinazione;

CREATE TABLE Destinazione(
	id_destinazione			INT PRIMARY KEY AUTO_INCREMENT,
	nome_cognome				VARCHAR(40) NOT NULL,
	numero_telefonico		VARCHAR(15),
	CAP									VARCHAR(5) NOT NULL,
	via 								VARCHAR(15) NOT NULL,
	numero_civico				VARCHAR(10) NOT NULL,
	utente 							VARCHAR(20) NOT NULL,
	FOREIGN KEY (utente) REFERENCES Utente(username) ON DELETE CASCADE
);

-- Inserimento dati nella tabella Destinazione

INSERT INTO Destinazione (id_destinazione, nome_cognome, numero_telefonico, CAP, via, numero_civico, utente) VALUES 

(1,'Utente Generico', '049XXXXXXX', '35100', 'Aldo Moro', '21', 'user'),
(2,'Utente Generico', '049XXXXXXX', '35100', 'Ugo Bassi', '17', 'user'),
(3,'User Uno', '346XXXXXXX', '35133', 'Don Stefani', '10', 'user1'),
(4,'User Due', '339XXXXXXX', '35142', 'Monte Bianco', '15a', 'user2'),
(5,'User Tre', '333XXXXXXX', '35129', 'Andrea Palladio', '17', 'user3'),
(6,'User Quattro', '340XXXXXXX', '35122', 'Antonio Canova', '11', 'user4'),
(7,'User Cinque', '348XXXXXXX', '35100', 'Aldo Moro', '22', 'user5');


-- Crea la tabella Tabella Ordine

DROP TABLE IF EXISTS Ordine;

CREATE TABLE Ordine(
	id_ordine			INT PRIMARY KEY AUTO_INCREMENT,
	data_ordine		DATETIME NOT NULL,
	data_consegna		DATETIME NOT NULL,
	totale				FLOAT NOT NULL,
	destinazione	INT,
	FOREIGN KEY (destinazione) REFERENCES Destinazione(id_destinazione) ON DELETE SET NULL
);

-- Inserimento dati nella tabella Ordine

INSERT INTO Ordine (id_ordine, data_ordine, data_consegna, totale, destinazione) VALUES 
(301, '2019-02-19 12:30:00', '2019-02-19 13:30:00', 27.00, 1),
(302, '2019-03-22 20:00:00', '2019-03-22 21:00:00', 28.00, 2),
(303, '2019-04-11 13:30:00', '2019-04-11 14:30:00', 33.00, 3),
(304, '2019-05-09 19:00:00', '2019-05-09 20:00:00', 31.50, 4),
(305, '2019-05-07 20:30:00', '2019-05-07 21:30:00', 46.50, 5),
(306, '2019-06-12 12:45:00', '2019-06-12 13:45:00', 40.00, 6),
(307, '2019-07-23 13:15:00', '2019-07-23 14:15:00', 36.50, 7),
(308, '2019-08-29 12:30:00', '2019-08-29 13:30:00', 66.00, 1),
(309, '2019-10-16 13:00:00', '2019-10-16 14:00:00', 15.50, 2),
(310, '2020-01-10 20:15:00', '2020-01-10 21:15:00', 42.50, 3),
(311, '2020-01-14 14:00:00', '2020-01-14 15:00:00', 57.50, 4);

-- Crea la tabella Tabella Prodotto

DROP TABLE IF EXISTS Prodotto;

CREATE TABLE Prodotto(
	nome 				VARCHAR(30) PRIMARY KEY,
	categoria		ENUM('Antipasti','Primi Piatti','Teppanyako e Tempure','Uramaki','Nigiri ed Onigiri','Gunkan','Temaki','Hosomaki','Sashimi','Dessert') NOT NULL,
	pezzi				TINYINT NOT NULL,
	prezzo 			FLOAT NOT NULL,
	descrizione		VARCHAR(150)
);

-- Inserimento dati nella tabella Prodotto

INSERT INTO Prodotto (nome, categoria, pezzi, prezzo, descrizione) VALUES 
('Tartara di tonno', 'Antipasti', 1, 6.00, 'Salmone, avocado, mango, tobiko, olio, menta e limone.'),
('Takosu', 'Antipasti', 1, 5.00, 'Carpaccio di polpo in salsa ponzu, limone e wakame.'),
('Goma Wakame', 'Antipasti', 1, 4.00, 'Alghe giapponesi marinate.'),
('Edamame', 'Antipasti', 1, 3.00, 'Fagioli di soia.'),
('Takoyaki', 'Antipasti', 4, 4.00, 'Polpette di Osaka con polpo e fiocchi di tonno disidratato.'),
('Domo Harumaki', 'Antipasti', 2, 2.00, 'Spinaci con fiocchi di tonno disidratato e teriyaki balsamica.'),
('Niku Harumaki', 'Antipasti', 2, 3.00, 'Stracotto di manzo con carote e patate con salsa black pepper.'),
('Yasai Gyoza', 'Antipasti', 4, 3.50, 'Ravioli di verdure in sfoglia di grano e riso.'),

('Ramen Chasumiso', 'Primi Piatti', 1, 7.00, 'Ramen in brodo di maialino chasu, cipollotto, spinaci e alga nori.'),
('Katsu Ramen', 'Primi Piatti', 1, 7.00, 'Ramen in brodo di miso con cotoletta, cipollotto e alga nori.'),
('Black Yakimeshi', 'Primi Piatti', 1, 8.00, 'Riso venere ai frutti di mare, uova tamago e fiocchi di tonno disidratato.'),
('Shakeyakidon', 'Primi Piatti', 1, 7.50, 'Filetto di salmone in salsa teriyaki su letto di riso teppanyaki.'),
('Gyuyaki Noodles', 'Primi Piatti', 1, 7.00, 'Tagliolini di riso con straccetti, fagiolini e sbriciolata di noci.'),
('Kaisen Udon', 'Primi Piatti', 1, 8.00, 'Udon ai frutti di mare con mazzancolle, verdure e fiocchi di tonno disidratato.'),

('Yakitori', 'Teppanyako e Tempure', 2, 6.00, 'Coscia di pollo in cottura teriyaki.'),
('Salmon Kushiyaki', 'Teppanyako e Tempure', 2, 5.00, 'Impanatura di salmone in cottura teriyaki.'),
('Ebi Yaki', 'Teppanyako e Tempure', 2, 6.00, 'Teppanyaki di gamberi marinati.'),
('Salmon Tataki', 'Teppanyako e Tempure', 1, 5.50, 'Filetto di salmone scottato.'),
('Maguro Tataki', 'Teppanyako e Tempure', 1, 5.50, 'Filetto di tonno scottato.'),
('Gyu Tataki', 'Teppanyako e Tempure', 4, 5.00, 'Filetto scottato di manzo marinato.'),
('Yasai Teppanyaki', 'Teppanyako e Tempure', 1, 5.00, 'Wok di verdure miste.'),
('Ebi Tempura', 'Teppanyako e Tempure', 3, 6.00, 'Mazzancolle in frittura giapponese.'),

('Chips Roll', 'Uramaki', 4, 5.00, 'Mazzancolle in tempura, cetriolo e salmone alla fiamma.'),
('Black Ebiten', 'Uramaki', 4, 4.00, 'Ebiten, avocado, sesamo.'),
('Black California', 'Uramaki', 4, 4.00, 'Polpa di granchio e goma wakame.'),
('Ichigo Hosomaki', 'Uramaki', 6, 4.00, 'Hosomaki in tempura con philadelphia e fragole.'),
('California', 'Uramaki', 4, 4.50, 'Polpa di granchio e avocado.'),
('Domò Roll', 'Uramaki', 4, 5.00, 'Salmone, avocado, tartare e tanuki.'),
('Ebiten', 'Uramaki', 4, 4.50, 'Mazzancolle in tempura, cetrioli e salsa teriyaki.'),
('Shake Fry Tobiko', 'Uramaki', 4, 4.00, 'Salmone in tempura, avocado e tobiko.'),
('Asparago Roll', 'Uramaki', 4, 4.50, 'Asparagi in tempura ricoperti da salmone alla fiamma.'),

('Nigiri Salmone', 'Nigiri ed Onigiri', 2, 2.00,''),
('Nigiri Tonno', 'Nigiri ed Onigiri', 2, 2.00,''),
('Nigiri Branziono', 'Nigiri ed Onigiri', 2, 2.00,''),
('Onigiri Salmone', 'Nigiri ed Onigiri', 1, 2.00, 'Onigiri con cuore di salmone grigliato'),
('Ebiten Onigiri', 'Nigiri ed Onigiri', 1, 2.00, 'Onigiri con cuore di mazzancolla in tempura.'),

('Spicy Tuna', 'Gunkan', 2, 5.00, 'Tartara di tonno piccante.'),
('Tobiko', 'Gunkan', 2, 4.50, 'Uova di pesce tobiko.'),
('Mango Suzuki', 'Gunkan', 2, 5.00, 'Tartara di branzino, mango e tobiko.'),
('Salmon Philadelphia', 'Gunkan', 2, 4.00, 'Riso, salmone, philadelphia.'),

('Salmon Temaki', 'Temaki', 1, 4.00, 'Salmone e avocado.'),
('Tartara Temaki', 'Temaki', 1, 3.00, 'Tartara di salmone con tobiko.'),
('Tuna Temaki', 'Temaki', 1, 3.00, 'Tonno e avocado.'),
('Ebiten Temaki', 'Temaki', 1, 4.00, 'Mazzancolla in tempura e salsa teriyaki.'),

('Hosomaki Salmone', 'Hosomaki', 6, 3.00,''),
('Hosomaki Tonno', 'Hosomaki', 6, 3.00,''),
('Hosomaki Cetriolo', 'Hosomaki', 6, 2.00,''),
('Hosomaki Avocado', 'Hosomaki', 6, 2.50,''),

('Sashimi Salmone', 'Sashimi', 4, 6.00,''),
('Sashimi Tonno', 'Sashimi', 4, 5.00,''),
('Sashimi Branzino', 'Sashimi', 4, 6.00,''),

('Profiteroles', 'Dessert', 1, 6.00, 'Profiteroles ricoperti con glassa di cacao e granella di nocciola.'),
('Matcha Tiramisù', 'Dessert', 1, 6.50, 'Tiramisù al tè matcha e cioccolato bianco.'),
('Red Cheesecake', 'Dessert', 1, 5.50, 'Cheesecake ai frutti di bosco e purea di lamponi.'),
('Semisfera al Mango', 'Dessert', 1, 6.00, 'Mousse al cioccolato bianco con glassa al mango e biscotti.'),
('Semisfera ai Lamponi', 'Dessert', 1, 5.00, 'Mousse al caramello e crema di lamponi.');

-- Crea la tabella Tabella Contiene

DROP TABLE IF EXISTS Contiene;

CREATE TABLE Contiene(
    id_ordine 			INT AUTO_INCREMENT,
    nome 						VARCHAR(30),
    numero_porzioni TINYINT NOT NULL,
    PRIMARY KEY (id_ordine, nome),
	FOREIGN KEY (id_ordine) REFERENCES Ordine(id_ordine) ON DELETE CASCADE,
	FOREIGN KEY (nome) REFERENCES Prodotto(nome) ON DELETE CASCADE
);

-- Inserimento dati nella tabella Contiene

INSERT INTO Contiene (id_ordine, nome, numero_porzioni) VALUES 
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
(311, 'Profiteroles', 5);

SET FOREIGN_KEY_CHECKS=1;
