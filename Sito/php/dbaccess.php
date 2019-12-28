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

        public function getPassword($utente){
            $query = $this->connection->prepare('SELECT * FROM Utente WHERE username= ?');
            $query->bind_param('s', $utente);
            $query->execute();
            $queryResult = $query->get_result();

            if(mysqli_num_rows($queryResult)==0){
                return null;
            }else{
               $row = mysqli_fetch_assoc($queryResult);
               return $row['password'];
            }
        }


        public function modificaPassword($utente, $password){

            $query = $this->connection->prepare('UPDATE Utente SET password = ? WHERE username = ?');
            $query->bind_param('ss', $password, $utente);
            if(!$query->execute()){
                header('location: errore500.html');
            }

        }



        public function addSpedizione($user, $nome_cognome, $indirizzo, $numero_civico, $cap, $tel){
            $query = $this->connection->prepare('INSERT INTO Destinazione (nome_cognome, numero_telefonico, CAP, via, numero_civico, utente) VALUES (?,?,?,?,?,"'.$user.'")');
            $query->bind_param('sssss',$nome_cognome, $tel, $cap, $indirizzo, $numero_civico);
            if(!$query->execute()){
                header('location: errore500.html');
            }

        }

        public function modificaPagamento($utente, $intestatario, $num_carta, $mese_scadenza, $anno_scadenza){

            $scadenza = $anno_scadenza.'-'.$mese_scadenza.'-00';

            $query = $this->connection->prepare('UPDATE Utente SET numero_carta = ?, intestatario = ?, scadenza = ? WHERE username = ?');
            $query->bind_param('ssss', $num_carta, $intestatario ,$scadenza, $utente);
            if(!$query->execute()){
                header('location: errore500.html');
            }
        }

        public function getDestinazioni($utente)
        {
            $query = $this->connection->prepare("SELECT nome_cognome, numero_telefonico, CAP, via, numero_civico  FROM Destinazione WHERE utente = ?");
            $query->bind_param('s', $utente);
            $query->execute();
            $queryResult = $query->get_result();

            if (mysqli_num_rows($queryResult) == 0)
            {
                return null;
            }
            else
            {
                return $queryResult;
            }
        }

        public function getCartaDiCredito($utente){
            $query = $this->connection->prepare('SELECT * FROM Utente WHERE username= ?');
            $query->bind_param('s', $utente);
            $query->execute();
            $queryResult = $query->get_result();
            $row = mysqli_fetch_assoc($queryResult);
            if($row['numero_carta']==null){
                return null;
            }else{
                return $row;
            }
        }

    }

//Controlla se viene inserito un CAP di Padova
function checkCAP($string) {
    if(!preg_match('/35(100|121|122|123|124|125|126|127|128|129|131|132|133|134|135|136|137|138|139|141|142|143)/', $string)){
        return false;
    } else return true;
}
/*Controlla se la carta di credito inserita è valida
si è scelto di utilizzare un controllo più basilare per semplicità nei testing
function checkCreditCard($cc) {
    if(!preg_match('^(?:4[0-9]{12}(?:[0-9]{3})?|[25][1-7][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$', $cc)){
        return false;
    } else return true;
}
*/

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
    if (!preg_match('/^[a-zA-Z0-9 ]+$/', $string)) {
        return false;
    } else return true;
}

//Controlla che la stringa non contenga numeri e abbia almeno due caratteri
function checkSoloLettereEDim($string) {
    if(!checkMinLen($string)) return false;
    if (!preg_match('/^[a-zA-Z ]+$/', $string)) {
        return false;
    } else return true;

}

//Controlla che la stringa contenga solo numeri e che sia lunga almeno due caratteri
function checkSoloNumerieDim($string){
    if(!checkMinLen($string)) return false;
    if (!checkSoloNumeri($string)) {
        return false;
    } else return true;

}

function checkSoloNumeri($string){
    if (!preg_match('/^[0-9]+$/', $string)) {
        return false;
    } else return true;


}
//Stampa il menu a seconda che l'utente sia autenticato o meno
//Va passato il contenuto della pagina come parametro
function getMenu($paginaHTML) {
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
				array_push($result,$arraySingoloPersonaggio);
			}
			
			return $result;
		}
	}
	*/


?>
