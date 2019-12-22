<?php
	require_once("php/dbaccess.php");


    session_start();
    $_SESSION['username']= 'user';
    
	if( isset($_SESSION['username'])) {
	         $user = $_SESSION['username'];

             $old_password = '';
             $c_old_password = '';
             $new_password = '';
             $c_new_password = '';

             $nome_cognome = 'Mario Rossi';
             $indirizzo = 'Inserire indirizzo';
             $numero_civico = 'Inserire numero civico';
             $cap = 'Inserire CAP';
             $tel = 'Inserire numero telefonico';

             $intestatario = 'Inserire intestatario della carta';
             $num_carta = 'Inserire numero carta';
             $mese_scadenza = '- Mese -';
             $anno_scadenza = '- Anno -';

             $erroriPass='';
             $erroriSped='';
             $erroriPaga='';


             if(isset($_POST['dati_personali'])){

                 $db = new DBAccess();
                 if($db->openDBConnection()){

                 $old_password = $db->getPassword($user);
                 if($old_password !== null){

                     $c_old_password = htmlentities(trim($_POST['v_password']));
                     $new_password = htmlentities(trim($_POST['password']));
                     $c_new_password = htmlentities(trim($_POST['c_password']));




                         if($c_old_password !== $old_password){
                             $erroriPass .= '<li>La password che ha inserito non coincide con quella salvata</li>';
                         }
                         if(!checkMinLen($new_password)){
                             $erroriPass .= '<li>La nuova password che hai inserito deve essere lunga almeno 2 caratteri</li>';
                         }
                         if($new_password !== $c_new_password){
                             $erroriPass .= '<li>Le due password che hai inserito non coincidono</li>';
                         }
                     }else {

                     $erroriPass .= '<li>La password che hai inserito e\' stata trovata nel nostro database</li>';
                     }
                 }else{
                     header('location: errore500.html');
                 }

                 if(strlen($erroriPass)==0){
                       $db->modificaPassword($user, $new_password);

                 }else {
                        $erroriPass = '<ul class="errore">'.$erroriPass.'</ul>';
                 }



             }
             elseif(isset($_POST['dati_spedizione'])){

                 $nome_cognome = htmlentities(trim($_POST['nome_cognome']));
                 $indirizzo = htmlentities(trim($_POST['indirizzo']));
                 $numero_civico = htmlentities(trim($_POST['civico']));
                 $cap = htmlentities(trim($_POST['cap']));
                 $tel = htmlentities(trim($_POST['tel']));

                 $db = new DBAccess();

                 if($db->openDBConnection()){
                    if(!checkSoloLettereEDim($nome_cognome)){
                        $erroriSped .= '<li>Il nome deve contenere solo lettere e non contenere meno di due caratteri</li>';
                    }
                    if(!checkAlfanumerico($indirizzo)){
                        $erroriSped .= '<li>L\'indirizzo non deve contenere caratteri speciali</li>';
                    }
                    if(!checkSoloNumeri($numero_civico)){
                        $erroriSped .= '<li>Il numero civico deve contenere solo numeri</li>';
                    }
                    if(!checkCAP($cap)){
                        $erroriSped .= '<li>Non hai inserito un CAP corretto</li>';
                    }
                    if(!checkSoloNumeriEDIm($tel)){
                        $erroriSped .= '<li>Non hai inserito un numero corretto</li>';
                    }

                    if(strlen($erroriSped)==0){

                        $db->addSpedizione($user, $nome_cognome, $indirizzo, $numero_civico, $cap, $tel);
                        $nome_cognome = 'Mario Rossi';
                        $indirizzo = 'Inserire indirizzo';
                        $numero_civico = 'Inserire numero civico';
                        $cap = 'Inserire CAP';
                        $tel = 'Inserire numero telefonico';


                    }else{
                        $erroriSped ='<ul class="errore">'.$erroriSped.'</ul>';
                    }


                 }else {
                     header('location: errore500.html');
                 }
             }elseif (isset($_POST['dati_pagamento'])){
                 $intestatario = htmlentities(trim($_POST['intestatario_carta']));
                 $num_carta = htmlentities(trim($_POST['num_carta']));
                 $mese_scadenza = htmlentities(trim($_POST['mese_scad']));
                 $anno_scadenza = htmlentities(trim($_POST['mese_scad']));

                 $db = new DBAccess();

                 if($db->openDBConnection()){

                     if(!checkSoloLettereEDim($intestatario)){
                         $erroriPaga .= '<li>L\'intestatario deve contenere solo lettere ed essere lungo almeno due caratteri</li>';
                     }
                     if(!checkSoloNumerieDim($num_carta)){
                         $erroriPaga .= '<li>Non hai inserito un numero della carta corretto</li>';
                     }
                     if($mese_scadenza == '- Mese -'){
                         $erroriPaga .= '<li>Seleziona il mese di scadenza</li>';
                     }
                     if($anno_scadenza == '- Anno -'){
                         $erroriPaga .= '<li>Seleziona l\'anno di scadenza</li>';
                     }

                     if(strlen($erroriPaga)==0){
                         $db->modificaPagamento($user, $intestatario, $num_carta, $mese_scadenza, $anno_scadenza);
                         $intestatario = 'Inserire intestatario della carta';
                         $num_carta = 'Inserire numero carta';
                         $mese_scadenza = '- Mese -';
                         $anno_scadenza = '- Anno -';

                     }


                 }else{

                     header('location: errore500.html');
                 }



             }

             $paginaHTML = file_get_contents('html/gestione_profilo_utente.html');

             $formPassword = '<fieldset>
                <legend>Informazioni personali: </legend>
                <h2>Nome Utente</h2>
                <label for="username">Nome utente: </label>
                <input type="text" id="username" name="username" value="'.$user.'" readonly="readonly"/>
    
                <h2 id="cp">Cambia <span lang="en">Password:</span> </h2>
                <label for="v_password">Inserisci la vecchia <span lang="en">password</span>: </label>
                <input type="password" id="v_password" name="v_password" />
                <label for="password">Inserisci la nuova <span lang="en">password</span>: </label>
                <input type="password" id="password" name="password" />
                <label for="c_password">Conferma la nuova <span lang="en">password</span>: </label>
                <input type="password" id="c_password" name="c_password" value=""/>
                <input class="defaultButton" type="submit" name="dati_personali" value="Salva"/>  <!--Submit legato solo al cambio della password-->
            </fieldset>';

             $formSpedizione ='<fieldset>
            <legend id="is" >Aggiungi un metodo di spedizione: </legend>

            <label for="nome_cognome">Nome e Cognome: </label>
            <input type="text" name="nome_cognome" id="nome_cognome" placeholder="'.$nome_cognome.'"/>
            <label for="indirizzo">Indirizzo: </label>
            <input type="text" id="indirizzo" name="indirizzo" placeholder="'.$indirizzo.'"/>
            <label for="civico">Numero civico: </label>
            <input type="text" id="civico" name="civico" placeholder="'.$numero_civico.'"/>
            <label for="cap"><abbr title="Codice di Avviamento Postale">CAP</abbr> :</label>
            <input type="text" id="cap"  name="cap" placeholder="'.$cap.'"/>
            <label for="comune">Comune: </label>
            <input type="text" id="comune" name="comune" value="Padova" disabled="disabled"/>
            <label for="provincia">Provincia: </label>
            <input type="text" id="provincia" name="provincia" value="Padova" disabled="disabled"/>
            <label for="stato">Stato: </label>
            <input type="text" id="stato" name="stato" value="Italia" disabled="disabled"/>
            <label for="tel">Numero di telefono: </label>
            <input type="text" id="tel" name="tel" placeholder="'.$tel.'" />

            <input class="defaultButton" type="submit" name="dati_spedizione" value="Salva"/> <!--Submit legato solo alle informazioni di spedizione-->
        	</fieldset>';

             $years ='<select name="anno_scad">
                    <option>- Anno -</option>';
             $annoCorrente = date("Y");
             for($i = 0; $i<20; $i++ ){
                 $years.='<option value="'.($annoCorrente+$i).'">'.($annoCorrente+$i).'</option>';
             }

             $years.='</select>
             <input class="defaultButton" type="submit" name="dati_pagamento" value="Salva"> </fieldset>';



             $formPagamento ='<fieldset>
            <legend id="ip">Informazioni di pagamento: </legend>
                <label for="intestatario_carta">Intestatario carta: </label>
                <input type="text" name="intestatario_carta" id="intestatario_carta" placeholder="'.$intestatario.'" />
                <label for="num_carta">Numero carta: </label>
                <input type="text" name="num_carta" id="num_carta" placeholder="'.$num_carta.'" />
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
        '.$years;

             $listaDestinazioni ='';
             $queryResult=null;

             $db = new DBAccess();
             if($db->openDBConnection()){
                 $queryResult = $db->getDestinazioni($user);
             } else {
                 header('location: errore500.html');
             }

             if($queryResult == null){
                 header('location: errore500.html');
             }

                $index = 0;
                 while($row = mysqli_fetch_assoc($queryResult)) {
                     $listaDestinazioni.='<dt>'.$row['nome_cognome'].', indirizzo: '.$row['via'].' '.$row['numero_civico'].', '.$row['CAP'].' </dt>
                                          <dd><input type="button" name="elimina'.$index.'" value="Elimina"/></dd>';
                     $index++;
                 }

                 $listaDestinazioni = '<dl id="listaDestinazioni">'.$listaDestinazioni.'</dl>';


                 $paginaHTML = str_replace('<formSpedizione2 />', $listaDestinazioni, $paginaHTML);


                 $paginaHTML = str_replace('<messaggio1 />', $erroriPass, $paginaHTML);

                 $paginaHTML = str_replace('<messaggio2 />', $erroriSped, $paginaHTML);

                 $paginaHTML = str_replace('<messaggio3 />', $erroriPaga, $paginaHTML);

                 $paginaHTML = str_replace('<formPassword />', $formPassword, $paginaHTML);

                 $paginaHTML = str_replace('<formSpedizione />', $formSpedizione, $paginaHTML);
                 echo str_replace('<formPagamento />', $formPagamento, $paginaHTML);

	}

?>