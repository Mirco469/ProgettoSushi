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

        //Funzione per controllare le credenziali
        public function checkLogin($username,$password)
        {
            $query = $this->connection->prepare('SELECT * FROM utente WHERE username= ? AND password= ?');
            $query->bind_param('ss', $username,$password);
            $query->execute();
            $queryResult = $query->get_result();

            /*while ($row = $queryResult->fetch_assoc()) {
                echo $row['nome']." ".$row['cognome'];
            }*/

            if(mysqli_num_rows($queryResult) == 0)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
    }

?>