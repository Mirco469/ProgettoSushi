<?php
	require_once("php/dbaccess.php");
    $db = new DBAccess();

    session_start();
    $_SESSION['username']= 'user';
    
	if( isset($_SESSION['username'])) {

        if($db->openDBConnection()) {
            $user = $_SESSION['username'];


             $formPassword = getFormPassword();
             $formSpedizione = getFormSpedizione();
             $formPagamento = getFormPagamento();

             if(isset($_POST['dati_personali'])){
                 $formPassword = $db->cambioPassw(trim($_POST['v_password']), trim($_POST['password']), trim($_POST['c_password']));
             }
             elseif(isset($_POST['dati_spedizione'])){
                 $formSpedizione = $db->saveInfoSpedizione(trim($_POST['nome_cognome']), trim($_POST['indirizzo']), trim($_POST['civico']), trim($_POST['cap']), trim($_POST['tel']));
             }elseif (isset($_POST['dati_pagamento'])){
                 $formPagamento = $db->saveInfoPagamento(trim($_POST['intestatario_carta']), trim($_POST['num_carta']), trim($_POST['mese_scad']), trim($_POST['anno_scad']));
             }


                 $paginaHTML = file_get_contents('../html/gestione_profilo_utente.html');
                 $listaDestinazioni ='';

                /*
                 Da fare query che preleva tutte le destinazioni di un certo utente
                 $query = $db->connection->prepare('SELECT * FROM Destinazione WHERE "utente" = ? ');
                 $query->bind_param('s', $user);
                 $query->execute();
                */

                 while($row = $queryResult->fetch_assoc()) {
                     $listaDestinazioni.='<li>'.$row['nome_cognome'].', indirizzo: '.$row['via'].' '.$row['civico'].' '.$row['cap'].' </li>';
                 }

                 $listaDestinazioni = '<ul id="listaDestinazioni">'.$listaDestinazioni.'</ul>';

                 $tmp0 = str_replace('<formSpedizione2 />', $listaDestinazioni, $paginaHTML);

                 $tmp1 = str_replace('<formPassword />', $formPassword, $tmp0);
                 $tmp2 = str_replace('<formSpedizione />', $formSpedizione, $tmp1);
                 echo str_replace('<formPagamento />', $formPagamento, $tmp2);



        }else {
            header('location: errore500.html');
        }

    }else {
	    header('location: errore403.html');
    }

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
            <legend id="is" >Aggiungi un metodo di spedizione: </legend>

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
    $years ='<select name="anno_scad">
                <option>- Anno -</option>';
    $annoCorrente = date("Y");
    for($i = 0; $i<20; $i++ ){
        $years.='<option value="'.($annoCorrente+$i).'">'.($annoCorrente+$i).'</option>';
    }

    $years.='</select>
     <input class="defaultButton" type="submit" name="dati_pagamento" value="Salva"> </fieldset>';


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
        '.$years;
}


?>