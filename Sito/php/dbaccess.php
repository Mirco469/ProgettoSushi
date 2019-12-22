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
            $query = $this->connection->prepare('UPDATE Utente SET password=? WHERE username = ?');
            $query->bind_param('ss', $password, $utente);
            if(!$query->execute()){
                header('location: errore500.html');
            }
        }

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

    //Controlla che la stringa sia lunga almeno due caratteri
    function checkMinLen($string) {
        if(strlen($string)<2){
            return false;
        }else {
            return true;
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
