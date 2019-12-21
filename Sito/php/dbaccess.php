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
					array_push_result($result, $arraySingolaRecensione);
				}
			}
			return $result;
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
					array_push_result($result, $arraySingoloProdotto);
				}
			}
			return $result;
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
					array_push_result($result, $arraySingoloProdotto);
				}
			}
			return $result;
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
