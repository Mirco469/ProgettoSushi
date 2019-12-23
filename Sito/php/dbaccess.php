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

        /* INIZIO FUNZIONI PAGINA LOGIN */

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

        /* FINE FUNZIONI PAGINA LOGIN*/



        public function inserisciNews($titolo, $data ,$testo, $user){

                $query = $this->connection->prepare('INSERT INTO News (titolo, descrizione, data, utente) VALUES (?,?,?,?)');
                $query->bind_param('ssss', $titolo, $testo, $data, $user);
                if(!$query->execute()){
                    header('location: errore500.html');
                }

        }

        public function getNews() {
            $query = $this->connection->prepare('SELECT * FROM News ORDER BY data ');
            $query->execute();
            return $query->get_result();
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

		#funzione per il get dei prodotti per categoria;
		public function getProdotti($categoria) 
		{
			$query = $this->connection->prepare("SELECT * FROM Prodotto WHERE categoria = ?");
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

	function checkData($data){
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $data)) {
            return true;
        } else {
            return false;
        }
    }

    //Controlla che la stringa sia lunga almeno due caratteri
    function checkMinLen($string) {
        if(strlen($string)<2){
            return false;
        }else {
            return true;
        }
    }

    function checkTesto($string) {
        if(!checkMinLen($string)) return false;
        if (!preg_match('/^[a-zA-Z0-9 ,.?!]+$/', $string)) {
            return false;
        } else return true;
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
        if (!preg_match('/[^0-9]+$/', $string)) {
            return false;
        } else return true;
    }

	//Stampa il menu a seconda che l'utente sia autenticato o meno
	//Va passato il contenuto della pagina come parametro
	function printMenu($paginaHTML) {
	$menu = '';
		if(isset($_SESSION['username'])) {

			$menu = '<li class="impostazioni">
						<span id="dropbtn">Area Riservata</span>
						<ul id="dropdown_content">
							<li><a href="carrello.html" tabindex="8">Carrello</a></li>
							<li><a href="storico_ordini.html" tabindex="9">Storico ordini</a></li>
							<li><a href="gestione_profilo_utente.html" tabindex="10">Gestione profilo</a></li>
							<li><a lang="en" href="#" tabindex="11">Logout</a></li>
						</ul>
					</li>';
			return str_replace('<menu />', $menu, $paginaHTML);
		}else {
			$menu = '<li class="login"><a href="login.html" tabindex="7"><span lang="en">Login</span>/Registrazione</a></li>';
		   return str_replace('<menu />', $menu, $paginaHTML);
		}
	}


	/*	Esempio di funzione per prendere i dati
	public function getPersonaggi()
	{
		$query = "SELECT * FROM personaggi ORDER BY ID ASC";
		$queryResult = myqsli_query($this->connection,$query);
		
		if(mysqli_num_rows($queryResult) == 0)
		{
			return null;
		}
		else
		{
			$result = array();
			
			while($row = mysqli_fetch_assoc($queryResult))
			{
				$arraySingoloPersonaggio = array(
					'Nome' =>$row['nome]',
					'Colore' => $row['colore'],
					'Peso' => $row['peso'],
					'Potenza' => $row['potenza'],
					'Descrizione' => $row['descrizione'],
					'ABR' => $row['angry_birds'],
					'ABSW' => $row['angry_birds_star_wars'],
					'AVS' => $row['angry_birds_space'],
					'Immagine' => $row['immagine']
				);
			}
				array_push($result,$arraySingoloPersonaggio);
			
			return $result;
		}
	}
	*/
?>
