<?php

    class DBAccess
	{
        const HOST_DB = 'localhost';
        const USERNAME = 'root';
        const PASSWORD = '';
        const DATABASE_NAME = 'Sushi'; //Ogni utente ha un database già creato con nome uguale alla propria login (scritto sulle slide)

        public $connection = null;
        public function openDBConnection()
        {
            $this->connection = mysqli_connect(static::HOST_DB,static::USERNAME, static::PASSWORD, static::DATABASE_NAME);
            return $this->connection;
        }

        /* FUNZIONI PER CONTROLLARE LO STATO DEL DATABASE */

        //Funzione per controllare le credenziali: ritorna null se non esiste alcuna corrispondenza altrimenti ritorna il suo livello di autorizzazione
        public function checkLogin($username,$password)
        {
            $query = $this->connection->prepare('SELECT * FROM utente WHERE username= ? AND password= ?');
            $query->bind_param('ss', $username,$password);
            $query->execute();
            $queryResult = $query->get_result();

            if(mysqli_num_rows($queryResult) == 0)
            {
                return null;
            }
            else
            {
                $row = $queryResult->fetch_assoc();
                return $row['autorizzazione'];
            }
        }

        //Funzione che controlla se l'username è già esistente: ritorna true se esiste già false altrimenti
        public function  alreadyExistsUsername($username)
        {
            $query = $this->connection->prepare('SELECT * FROM utente WHERE username= ?');
            $query->bind_param('s', $username);
            $query->execute();
            $queryResult = $query->get_result();
            if(mysqli_num_rows($queryResult) == 0)
            {
                return false;
            }
            else
            {
                return true;
            }
        }

        //Funzione che controlla se il prodotto è già esistente: ritorna true se esiste già false altrimenti
        public function  alreadyExistsProdotto($prodotto)
        {
            $query = $this->connection->prepare('SELECT * FROM prodotto WHERE nome= ?');
            $query->bind_param('s', $prodotto);
            $query->execute();
            $queryResult = $query->get_result();
            if(mysqli_num_rows($queryResult) == 0)
            {
                return false;
            }
            else
            {
                return true;
            }
        }

        /* FUNZIONI PER AGGIUNGERE DATI AL DATABASE */

        public function addAccount($username,$nome,$cognome,$password)
        {
            $query = $this->connection->prepare('INSERT INTO utente(username,nome,cognome,password,autorizzazione) VALUES (?,?,?,?,"Utente")');
            $query->bind_param('ssss', $username,$nome,$cognome,$password);
            if($query->execute())
            {
                redirectHome("Utente");
            }
            else
            {
                header("Location: /errore500.php"); /*CONTROLLARE SE LA PAGINA E' GIUSTA*/
            }
        }

        //Aggiunge un prodotto al database: ritorna true se ha successo, reindirzzia alla pagina di errore 500 altrimenti.
        public function addProdotto($nome,$categoria,$pezzi,$prezzo,$descrizione)
        {
            $prezzo = str_replace(",",".",$prezzo);
            $query = $this->connection->prepare('INSERT INTO prodotto(nome,categoria,pezzi,prezzo,descrizione) VALUES (?,?,?,?,?)');
            $query->bind_param('sssss', $nome,$categoria,$pezzi,$prezzo,$descrizione);
            if($query->execute())
            {
                return true;
            }
            else
            {
                header("Location: /errore500.php"); /*CONTROLLARE SE LA PAGINA E' GIUSTA*/
            }
        }

        #Aggiunge una recensione al database, altrimenti reindirizza ad errore500.php
        public function addRecensione ($titolo, $data, $utente, $testo)
        {
            $query = $this->connection->prepare('INSERT INTO Recensione(titolo, testo, data, utente) VALUES ($titolo, $testo, $data, $utente');
            $query->bind_param('ssss', $titolo, $testo, $data, $utente);
            if($query->execute())
            {
                return true;
            }
            else
            {
                header("Location: /errore500.php");
            }
        }   

		#funzione per il get delle recensioni;
		public function getRecensioni() 
		{
			#DESC o ASC in modo che prima ci sia la più recente;
			$query = $this->connection->prepare("SELECT * FROM Recensione ORDER BY data DESC");
			$query->execute();
			$queryResult = $query->get_result();

			if (mysqli_num_rows($queryResult) == 0)
			{
				return null;
			} 
			else
			{
				$result = array();

				while ($row = mysqli_fetch_assoc($queryResult)) {
					$arraySingolaRecensione = array (
						'Titolo' => $row['titolo'],
						'Data' => $row['data'],
						'Utente' => $row['utente'],
						'Testo' => $row['testo'],
					);
					array_push($result, $arraySingolaRecensione);
				}

				return $result;
			}
		}

		/* FUNZIONI PER OTTENERE DATI DAL DATABASE */

		#funzione per il get dei prodotti per categoria con i nomi in ordine alfabetico;
		public function getProdotti($categoria) 
		{
			$query = $this->connection->prepare("SELECT * FROM Prodotto WHERE categoria = ? ORDER BY nome ASC");
			$query->bind_param('s', $categoria);
			$query->execute();
			$queryResult = $query->get_result();

			if (mysqli_num_rows($queryResult) == 0)
			{
				return null;
			} 
			else
			{
				$result = array();

				while ($row = mysqli_fetch_assoc($queryResult)) {
					$arraySingoloProdotto = array (
						'Nome' => $row['nome'],
						'Prezzo' => $row['prezzo'],
						'Pezzi' => $row['pezzi'],
						'Descrizione' => $row['descrizione'],
					);
                    array_push($result, $arraySingoloProdotto);
				}

				return $result;
			}
		}

		#funzione per il get degli indirizzi per utente;
		public function getIndirizzi($utente) 
		{
			$query = $this->connection->prepare("SELECT via, numero_civico FROM Destinazione WHERE utente = ?");
			$query->bind_param('s', $utente);
			$query->execute();
			$queryResult = $query->get_result();

			if (mysqli_num_rows($queryResult) == 0)
			{
				return null;
			} 
			else
			{
				$result = array();

				while ($row = mysqli_fetch_assoc($queryResult)) {
					$arraySingoloIndirizzo = array (
						'Via' => $row['via'],
						'Num' => $row['numero_civico'],
					);
                    array_push($result, $arraySingoloIndirizzo);
				}

				return $result;
			}
		}

		#funzione per il get della carta di credito per utente;
		public function getPagamento($utente) 
		{
			$query = $this->connection->prepare("SELECT numero_carta FROM Utente WHERE username = ?");
			$query->bind_param('s', $utente);
			$query->execute();
			$queryResult = $query->get_result();

			if (mysqli_num_rows($queryResult) == 0)
			{
				return null;
			} 
			else
			{
			    $row = mysqli_fetch_assoc($queryResult);
				return $row['numero_carta'];
			}
		}

    }

    //Reindirizza alla home giusta in base all'autorizzazione passata come paramentro (Utente o Admin)
    function redirectHome($autorizzazione)
    {
        if(strcmp($autorizzazione,"Utente") == 0)
        {
            header("Location: home_utente.php"); /*CONTROLLARE SE LA PAGINA E' GIUSTA*/
        }
        elseif(strcmp($autorizzazione,"Admin") == 0)
        {
            header("Location: back/home_admin.php"); /*CONTROLLARE SE LA PAGINA E' GIUSTA*/
        }
        else
        {
            echo "Errore interno al server, contattare l'amministratore.";
        }
    }

    /* FUNZIONI PER IL CHECK DELL'INPUT */

    //Controlla che la stringa sia lunga almeno due caratteri
    function checkMinLen($string) {
        if(strlen($string)<2){
            return false;
        }else {
            return true;
        }
    }

    //Controlla che la stringa non contenga caratteri speciali
    function checkAlfanumerico($string) {
        if(!checkMinLen($string)) return false;
        if (!preg_match('/^[a-zA-Z0-9]+$/', $string)) {
            return false;
        } else return true;
    }

    //Controlla che la stringa non contenga numeri e abbia almeno due caratteri
    function checkSoloLettereEDim($string) {
        if(!checkMinLen($string)) return false;
        if (!preg_match('/^[a-zA-Z]+$/', $string)) {
            return false;
        } else return true;
    }

    //Controlla che la stringa contenga solo numeri e che sia lunga almeno due caratteri
    function checkSoloNumerieDim($string){
        if(!checkMinLen($string)) return false;
        if (!preg_match('/^[0-9]+$/', $string)) {
            return false;
        } else return true;
    }

    //Controlla che il numero sia intero e $numero non sia vuoto
    //Ritorna true se rispetta le condizioni, false altrimenti
    function checkNumeroIntero($numero){
        if(empty($numero)) return false;
        if (!preg_match('/^[0-9]+$/', $numero)) {
            return false;
        } else return true;
    }

    //Controlla che il parametro sia un numero consono ad essere un prezzo ovvero può essere decimale ma con al massimo due cifre dopo la virgola e
    // tre cifre prima della virgola (228,90)(non può essere stringa vuota)
    //Ritorna true se rispetta i vincoli sopra descritti, false altrimenti.
    function checkPrezzo($numero)
    {
        if(empty($numero)) return false;
        if (!preg_match('/^[0-9]{1,3}((.|,)[0-9]{1,2})?$/', $numero)) {
            return false;
        } else return true;
    }

    /* ALTRO */
		//Ritorna la parte di menu corretta a seconda che l'utente sia loggato o meno
	function getMenu() {


		if(isset($_SESSION['username'])) {
		    return '<li class="impostazioni">
						<span id="dropbtn">Area Riservata</span>
						<ul id="dropdown_content">
							<li><a href="carrello.html" tabindex="8">Carrello</a></li>
							<li><a href="storico_ordini.html" tabindex="9">Storico ordini</a></li>
							<li><a href="gestione_profilo_utente.html" tabindex="10">Gestione profilo</a></li>
							<li><a lang="en" href="logout.php" tabindex="11">Logout</a></li>
						</ul>
					</li>';

		}else {
			return '<li class="login"><a href="login.html" tabindex="7"><span lang="en">Login</span>/Registrazione</a></li>';
		}
	}

	//Funzione per ottenere le categorie dei prodotti
    function getCategorie()
    {
        return array("Antipasti","Primi Piatti","Teppanyako e tempure","Uramaki","Nigiri ed Onigiri","Gunkan","Temaki","Hosomaki","Sashimi","Dessert");
    }
?>
