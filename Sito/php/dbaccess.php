<?php

    class DBAccess
	{
        const HOST_DB = 'localhost';
        const USERNAME = 'root';
        const PASSWORD = '';
        const DATABASE_NAME = 'progettosushi'; //Ogni utente ha un database già creato con nome uguale alla propria login (scritto sulle slide)

        public $connection = null;
        public function openDBConnection()
        {
            $this->connection = mysqli_connect(static::HOST_DB,static::USERNAME, static::PASSWORD, static::DATABASE_NAME);
            return $this->connection;
        }
		
		public function getDettagliOrdine($id_ordine,$username) {
			$query = $this->connection->prepare("SELECT O.*, D.* FROM Ordine O INNER JOIN Destinazione D ON O.destinazione = D.id_destinazione INNER JOIN Utente U ON D.utente = U.username WHERE U.username = ? AND O.id_ordine = ?");
			$query->bind_param('ss',$username,$id_ordine);
			$query->execute();
			$queryResult = $query->get_result();
			
			if( $queryResult->num_rows > 0 ) {
				$result = $queryResult->fetch_object();
				
				$query = $this->connection->prepare("SELECT C.*, P.categoria FROM Contiene C INNER JOIN Prodotto P ON C.nome = P.nome WHERE id_ordine = ?");
				$query->bind_param('s',$id_ordine);
				$query->execute();
				$queryResult = $query->get_result();
				
				$listaProdotti = array();
				
				while ($row = $queryResult->fetch_object()) {
					array_push($listaProdotti, $row);
				}
				
				$result->listaProdotti = $listaProdotti;
				
				return $result;
			} else {
				/* errore
					l'id_ordine non esiste
					l'username non ha effettuato l'ordine con quel id_ordine
				*/
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
        echo str_replace('<menu />', $menu, $paginaHTML);
    }else {
        $menu = '<li class="login"><a href="login.html" tabindex="7"><span lang="en">Login</span>/Registrazione</a></li>';
       echo str_replace('<menu />', $menu, $paginaHTML);
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
