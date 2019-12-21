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

        public function cambioPassw($v_password, $n_password, $c_password) {
        $nome_utente = $_SESSION['username'];
        $query = $this->connection->prepare('SELECT * from Utente WHERE username = ?');
        $query -> bind_param('s', $nome_utente);
        $query->execute();
        $result = $query->get_result();
        $paginaHTML = file_get_contents('gestione_profilo_utente.html');
        if(mysqli_num_rows($result)==0) {
            header('location: erroreDatabase.html');
            return false;
        }
        $row = mysqli_fetch_assoc($result);
        $old_psw = $row['password'];

        $strErr="";

        if($v_password !== $old_psw) {
            $strErr = '<li> La <span lang="en">password</span> che hai inserito non coincide con quella salvata</li>';
        }

        if(!checkMinLen($n_password)||!checkMinLen($c_password)||$n_password !== $c_password) {
            $strErr .= '<li> Le due nuove <span lang="en">password</span> non coincidono o non sono lunghe almeno 2 caratteri</li>';
        }

        $form = getFormPassword();

        $formPagamento = getFormPagamento();
        $formSpedizione = getFormSpedizione();

        if(strlen($strErr)>0){
            $strErr = '<ul class = "errore">'. $strErr.'</ul>';
            $tmp1 = str_replace('<formPassword />', $form, $paginaHTML);
            $tmp2 = str_replace("<messaggio1 />", $strErr, $tmp1);

            echo str_replace('<formPagamento />', $formPagamento, str_replace('<formSpedizione />', $formSpedizione, $tmp2));
            return false;
        }else {
            $query = $this->connection->prepare("UPDATE Utente SET password =?  WHERE username = ? ");
            $query -> bind_param('ss', $n_password, $nome_utente);
            $query->execute();
            $resultq = $query->get_result();
            if(!$resultq) {
                header('location: erroreDatabase.html');
                return false;
            } else{
                $tmp1 = str_replace('<formPassword />', $form, $paginaHTML);
                $tmp2 = str_replace('<messaggio1 />', '<p class = "successo>">La <span lang="en">password</span> &grave; stata aggiornata!</p>', $tmp1);
                echo str_replace('<formPagamento />', $formPagamento, str_replace('<formSpedizione />', $formSpedizione, $tmp2));
                return true;
            }


        }


    }

        public function saveInfoSpedizione($nome_cognome, $indirizzo, $civico, $cap, $tel) {
    $nome_utente = $_SESSION['username'];
    $paginaHTML = file_get_contents('gestione_profilo_utente.html');
    $strErrori="";

    if (!checkSoloLettereEDim($nome_cognome)) {
        $strErrori .= '<li>Il nome dell\'intestatario può contenere solo lettere e contenere almeno due caratteri</li>';
    }
    if (!checkSoloLettereEDim($indirizzo)) {
        $strErrori .= '<li>L\'indirizzo può contenere solo lettere e contenere almeno due caratteri</li>';
    }
    if (!checkAlfanumerico($civico)) {
        $strErrori .= '<li>Il numero civico può contenere solo caratteri alfanumerici</li>';
    }
    if (!checkCAP($cap)) {
        $strErrori .= '<li>Inserire un codice di avviamento postale del comune di Padova valido</li>';
    }
    if (!checkSoloNumerieDim($tel)) {
        $strErrori .= '<li>Inserire un numero telefonico valido</li>';
    }

    $formPassword = getFormPassword();
    $formPagamento = getFormPagamento();

    if(strlen($strErrori)==0){
        $query = $this->connection->prepare("INSERT into Destinazione ('nome_cognome', 'numero_telefonico', 'CAP', 'via', 'numero_civico', 'utente') VALUES ('".$nome_cognome."', '".$tel."', '".$cap."', '".$indirizzo."', '".$civico."', '".$nome_utente."')");
        $query->execute();
        $result = $query->get_result();
        if(!$result){
            header('location: erroreDatabase.html');

        }else{

            $tmp1=str_replace('<messaggio2 />', '<p class = "successo>">La nuova destinazione &grave; stata inserita!</p>', $paginaHTML);

            $formSuccesso = getFormSpedizione();
            $tmp2 = str_replace('<formPassword />', $formPassword, str_replace('<formPagamento />', $formPagamento, $tmp1));
        	echo str_replace('<formSpedizione />', $formSuccesso, $tmp2);
            return true;
        }
    }else {
        $strErrori = '<ul class = "errore">'.$strErrori.'</ul>';
        $tmp1 = str_replace('<messaggio2 />', $strErrori, $paginaHTML);
        $formErrore = '<fieldset>
            <legend id="is" >Modifica o inserisci informazioni sulla spedizione: </legend>

            <label for="nome_cognome">Nome e Cognome: </label>
            <input type="text" name="nome_cognome" id="nome_cognome" placeholder="'.$nome_cognome.'"/>
            <label for="indirizzo">Indirizzo: </label>
            <input type="text" id="indirizzo" name="indirizzo" placeholder="'.$indirizzo.'"/>
            <label for="civico">Numero civico: </label>
            <input type="text" id="civico" name="civico" placeholder="'.$civico.'"/>
            <label for="cap"><abbr title="Codice di Avviamento Postale">CAP</abbr> :</label>
            <input type="text" id="cap"  name="cap" placeholder="'.$cap.'"/>
            <label for="comune">Comune: </label>
            <input type="text" id="comune" name="comune" value="Padova" disabled="disabled"/>
            <label for="provincia">Provincia: </label>
            <input type="text" id="provincia" name="provincia" value="Padova" disabled="disabled"/>
            <label for="stato">Stato: </label>
            <input type="text" id="stato" name="stato" value="Italia" disabled="disabled"/>
            <label for="tel">Numero di telefono: </label>
            <input type="tel" id="tel" name="tel" value="'.$tel.'" />

            <input class="defaultButton" type="submit" name="dati_spedizione" value="Salva"/> <!--Submit legato solo alle informazioni di spedizione-->
        </fieldset>';
        $tmp2 = str_replace('<formPagamento />', $formPagamento, str_replace('<formPassword />', $formPassword, $tmp1));
        echo str_replace('<formSpedizione />', $formErrore, $tmp2);
        return false;
    }
}

        public function saveInfoPagamento($intestatario, $num_carta, $mese_scad, $anno_scad) {
    $nome_utente = $_SESSION['username'];
    $paginaHTML = file_get_contents('gestione_profilo_utente.html');
    $strErrori="";

    if (!checkSoloLettereEDim($intestatario)) {
        $strErrori .= '<li>Il nome dell\'intestatario deve contenere solo lettere ed essere almeno lungo due caratteri</li>';
    }
    if (!checkSoloNumerieDim($num_carta)) {
        $strErrori .= '<li>Inserire una carta di credito valida</li>';
    }
    if($mese_scad === '- Mese -') {
        $strErrori .= '<li>Selezionare un mese</li>';
    }
    if($anno_scad === '- Anno -') {
        $strErrori .= '<li>Selezionare un anno</li>';
    }

    $formPassword = getFormPassword();
    $formSpedizione = getFormSpedizione();

    if(strlen($strErrori)==0){
        $query = $this->connection->prepare("UPDATE Utente SET numero_carta = ?, intestatario = ?, scadenza ='".$anno_scad."-".$mese_scad."-"."00"."' WHERE username = '".$nome_utente."'");
        $query -> bind_param('ss', $num_carta, $intestatario);
        $query->execute();
        $result = $query->get_result();
        if(!$result){
            header('location: erroreDatabase.html');
            return false;
        }else {
            $tmp1 = str_replace('<messaggio3 />', '<p class = "successo>">Il metodo di pagamento &grave; stato inserito con successo!</p>', $paginaHTML);
            $formSuccesso = getFormPagamento();
            $tmp2 = str_replace('<formPassword />', $formPassword, str_replace('<formSpedizione />', $formSpedizione, $tmp1));
            echo str_replace('<formPagamento />', $formSuccesso, $tmp2);
            return true;
        }
    }else {
        $strErrori = '<ul class = "messaggio">'.$strErrori.'</ul>';
        $tmp1 = str_replace('<messaggio3 />', $strErrori, $paginaHTML);
        $formErrore = '        <fieldset>
            <legend id="ip">Informazioni di pagamento: </legend>
                <label for="intestatario_carta">Intestatario carta: </label>
                <input type="text" name="intestatario_carta" id="intestatario_carta" value="'.$intestatario.'" />
                <label for="num_carta">Numero carta: </label>
                <input type="text" name="num_carta" id="num_carta" value="'.$num_carta.'"/>
            <select name="mese_scad">
                <option>- Mese -</option>
                <option value="01">January</option>
                <option value="03">March</option>
                <option value="04">April</option>
                <option value="05">May</option>
                <option value="06">June</option>
                <option value="07">July</option>
                <option value="08">August</option>
                <option value="09">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
        </select>
        <select name="anno_scad">
                <option>- Anno -</option>
                <option value="2019">2019</option>
                <option value="2020">2020</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
                <option value="2026">2026</option>
                <option value="2027">2027</option>
                <option value="2028">2028</option>
                <option value="2029">2028</option>
                <option value="2030">2030</option>
                <option value="2031">2031</option>
                <option value="2032">2032</option>
                <option value="2033">2033</option>
                <option value="2034">2034</option>
                <option value="2035">2035</option>
                <option value="2036">2036</option>
                <option value="2037">2037</option>
                <option value="2038">2038</option>
                <option value="2039">2039</option>
            </select>

            <input class="defaultButton" type="submit" name="dati_pagamento" value="Salva">
        </fieldset>';
        $tmp2 = str_replace('<formPassword />', $formPassword, str_replace('<formSpedizione />', $formSpedizione, $tmp1));
        echo str_replace('<formPagamento />', $formErrore, $tmp2);
        return false;
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
    if (!preg_match('/[^A-Za-z0-9]/', $string)) {
        return false;
    } else return true;
}

//Controlla che la stringa non contenga numeri e abbia almeno due caratteri
function checkSoloLettereEDim($string) {
    if(!checkMinLen($string)) return false;
    if (!preg_match('/[^A-Za-z]/', $string)) {
        return false;
    } else return true;
}

//Controlla che la stringa contenga solo numeri e che sia lunga almeno due caratteri
function checkSoloNumerieDim($string){
    if(!checkMinLen($string)) return false;
    if (!preg_match('/[^0-9]/', $string)) {
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
function getFormPassword(){
    $nome_utente = $_SESSION['username'];
    return '<fieldset>
                <legend>Informazioni personali: </legend>
                <h2>Nome Utente</h2>
                <label for="username">Nome utente: </label>
                <input type="text" id="username" name="username" value="'.$nome_utente.'" readonly="readonly"/>
    
                <h2 id="cp">Cambia <span lang="en">Password:</span> </h2>
                <label for="v_password">Inserisci la vecchia <span lang="en">password</span>: </label>
                <input type="password" id="v_password" name="v_password" />
                <label for="password">Inserisci la nuova <span lang="en">password</span>: </label>
                <input type="password" id="password" name="password" />
                <label for="c_password">Conferma la nuova <span lang="en">password</span>: </label>
                <input type="password" id="c_password" name="c_password" value=""/>
                <input class="defaultButton" type="submit" name="dati_personali" value="Salva"/>  <!--Submit legato solo al cambio della password-->
            </fieldset>';
}

function getFormSpedizione(){
    return '<fieldset>
            <legend id="is" >Modifica o inserisci informazioni sulla spedizione: </legend>

            <label for="nome_cognome">Nome e Cognome: </label>
            <input type="text" name="nome_cognome" id="nome_cognome" placeholder="Mario Rossi"/>
            <label for="indirizzo">Indirizzo: </label>
            <input type="text" id="indirizzo" name="indirizzo" placeholder="Inserire via"/>
            <label for="civico">Numero civico: </label>
            <input type="text" id="civico" name="civico" placeholder="Inserire numero civico"/>
            <label for="cap"><abbr title="Codice di Avviamento Postale">CAP</abbr> :</label>
            <input type="text" id="cap"  name="cap" placeholder="Inserire CAP"/>
            <label for="comune">Comune: </label>
            <input type="text" id="comune" name="comune" value="Padova" disabled="disabled"/>
            <label for="provincia">Provincia: </label>
            <input type="text" id="provincia" name="provincia" value="Padova" disabled="disabled"/>
            <label for="stato">Stato: </label>
            <input type="text" id="stato" name="stato" value="Italia" disabled="disabled"/>
            <label for="tel">Numero di telefono: </label>
            <input type="tel" id="tel" name="tel" />

            <input class="defaultButton" type="submit" name="dati_spedizione" value="Salva"/> <!--Submit legato solo alle informazioni di spedizione-->
        	</fieldset>';
}

function getFormPagamento(){
    return '<fieldset>
            <legend id="ip">Informazioni di pagamento: </legend>
                <label for="intestatario_carta">Intestatario carta: </label>
                <input type="text" name="intestatario_carta" id="intestatario_carta" value="" />
                <label for="num_carta">Numero carta: </label>
                <input type="text" name="num_carta" id="num_carta" />
            <select name="mese_scad">
                <option>- Mese -</option>
                <option value="01">January</option>
                <option value="03">March</option>
                <option value="04">April</option>
                <option value="05">May</option>
                <option value="06">June</option>
                <option value="07">July</option>
                <option value="08">August</option>
                <option value="09">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
        </select>
        <select name="anno_scad">
                <option>- Anno -</option>
                <option value="2019">2019</option>
                <option value="2020">2020</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
                <option value="2026">2026</option>
                <option value="2027">2027</option>
                <option value="2028">2028</option>
                <option value="2029">2028</option>
                <option value="2030">2030</option>
                <option value="2031">2031</option>
                <option value="2032">2032</option>
                <option value="2033">2033</option>
                <option value="2034">2034</option>
                <option value="2035">2035</option>
                <option value="2036">2036</option>
                <option value="2037">2037</option>
                <option value="2038">2038</option>
                <option value="2039">2039</option>
            </select>

            <input class="defaultButton" type="submit" name="dati_pagamento" value="Salva">
        </fieldset>';
}

?>
