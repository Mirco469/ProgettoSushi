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

    }

    //Reindirizza alla home giusta in base all'autorizzazione passata come paramentro
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
        if (!preg_match('/[^0-9]+$/', $string)) {
            return false;
        } else return true;
    }

?>