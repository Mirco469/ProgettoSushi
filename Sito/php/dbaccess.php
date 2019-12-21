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

        public function inserisciNews($titolo, $data ,$testo){
            $username = $_SESSION['username'];


            $strErrori='';
            if(strlen($titolo)==0){
                $strErrori .= '<li>Devi inserire un titolo</li>';
            }
            if(strlen($data)==0){
                $strErrori .='<li>Devi inserire una data</li>';
            }
            if(!checkData($data)){
                $strErrori .= '<li>Devi inserire una data nel formato AAAA-MM-GG</li>';
            }
            if(strlen($testo)==0){
                $strErrori .= '<li>Non puoi inserire una notizia vuota</li>';
            }

            $paginaHTML = file_get_contents('home_admin.html');

            if(strlen($strErrori)==0){
                $query = $this->connection->prepare('INSERT into News ("titolo", "descrizione", "data", "utente") VALUES ("'.$titolo.'","'.$testo.'","'.$data.'","'.$username.'")');
                $query->execute();
                $result = $query->get_result();

                if(!$result){
                    header('location: erroreDatabase.html');
                }else{
                    $tmp1 = str_replace('<messaggio />', '<p>Inserimento avvenuto con successo!</p>', $paginaHTML);
                    $content = "<dt>".$data." - ".$titolo."</dt>
                                    <dd>".$testo."</dd>
                                    <dd><input type=\"button\" name=\"elimina\" value=\"Elimina\"/></dd>
                                <notizia />";
                    return str_replace('<notizia />', $content, $tmp1);
                }
            } else {
                $strErrori= '<ul class="errore">'.$strErrori.'</ul>';
                $tmp1 = str_replace('<messaggio />', $strErrori, $paginaHTML);
                return $tmp1;
            }
        }

        public function togliNews() {}
	}


	function checkData($data){
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $data)) {
            return true;
        } else {
            return false;
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
