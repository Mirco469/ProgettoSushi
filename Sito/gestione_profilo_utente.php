<?php
	require_once("php/dbaccess.php");
    session_start();
 

	if( isset($_SESSION['username'])) {
	         $db = null;
	         $user = $_SESSION['username'];

	         //Inizializzo i dati dei form

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
             $listaDestinazioni ='';
             $queryResult=null;
             $queryCarta=null;

             //Prendo i dati di pagamento salvati se esistono


            $db = new DBAccess();


            if($db->openDBConnection()){

                $queryCarta = $db->getCartaDiCredito($user);
            } else {
                header('location: errore500.php');
            }






            if($queryCarta!=null){
                $intestatario = $queryCarta['intestatario'];
                $num_carta = $queryCarta['numero_carta'];

                $scad = $queryCarta['scadenza'];
            }

            //Inizializzo i messaggi

             $erroriPass='';
             $erroriSped='';
             $erroriPaga='';
             $successoPass = '';
             $successoDest = '';
             $successoPaga='';
             $successoEliminaDest = '';

             //Controllo se è stato premuto il bottone per cambiare password

             if(isset($_POST['dati_personali'])){

                 $db = new DBAccess();
                 if($db->openDBConnection()){

                 $old_password = $db->getPassword($user);
                 if($old_password !== null){

                     $c_old_password = htmlentities(trim($_POST['v_password']));
                     $new_password = htmlentities(trim($_POST['password']));
                     $c_new_password = htmlentities(trim($_POST['c_password']));


                        if(!checkMaxLen($new_password, 20)){
                            $erroriPass.='<li>La password non deve contenere più di 16 caratteri</li>';
                        }

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

                     $erroriPass .= '<li>La password che hai inserito non e\' stata trovata nel nostro database</li>';
                     }
                 }else{
                     header('location: errore500.php');
                 }

                 if(strlen($erroriPass)==0){

                       $db->modificaPassword($user, $new_password);
                       $successoPass = '<ul class="successo"><li>La <span lang="en">password</span> è stata cambiata con successo</li></ul>';


                 }else {
                        $erroriPass = '<ul class="errore">'.$erroriPass.'</ul>';
                 }



             }

             //Controllo se è stato premuto il bottone per salvare la nuova destinazione
             elseif(isset($_POST['dati_spedizione'])){

                 $nome_cognome = htmlentities(trim($_POST['nome_cognome']));
                 $indirizzo = htmlentities(trim($_POST['indirizzo']));
                 $numero_civico = htmlentities(trim($_POST['civico']));
                 $cap = htmlentities(trim($_POST['cap']));
                 $tel = htmlentities(trim($_POST['tel']));


                 $db = new DBAccess();

                 if($db->openDBConnection()){
                    if(!checkNomeCognome($nome_cognome)){
                        $erroriSped .= '<li>Il nome deve contenere solo lettere e non contenere meno di due caratteri</li>';
                    }
                    if(!checkAlfanumericoESpazi($indirizzo)){
                        $erroriSped .= '<li>L\'indirizzo non deve contenere caratteri speciali</li>';
                    }
                    if(!checkCivico($numero_civico)){ //Potrebbe essere 4b o 4 o 4/b o 4-b
                        $erroriSped .= '<li>Il numero civico deve essere nel formato corretto (e.g. 4, 4b, 4/b, 4-b)</li>';
                    }
                    if(!checkCAP($cap)){
                        $erroriSped .= '<li>Non hai inserito un CAP del comune di Padova</li>';
                    }
                    if(!checkSoloNumeriEDIm($tel)){
                        $erroriSped .= '<li>Non hai inserito un numero telefonico corretto</li>';
                    }

                    if(!checkMaxLen($nome_cognome, 40)){
                        $erroriSped.='<li>Il campo nome e cognome non deve contenere più di 40 caratteri</li>';
                    }

                    if(!checkMaxLen($tel, 15)){
                        $erroriSped.='<li>Il numero telefonico non deve contenere più di 16 caratteri</li>';
                    }

                    if(!checkMaxLen($numero_civico, 10)){
                        $erroriSped.='<li>Il numero civico non deve contenere più di 10 caratteri</li>';
                    }

                    if(!checkMaxLen($cap, 5)){
                        $erroriSped.='<li>Il CAP non deve contenere più di 5 caratteri</li>';
                    }

                    if(!checkMaxLen($indirizzo, 30)){
                        $erroriSped.='<li>Il nome dell\'indirizzo non deve contenere più di 30 caratteri</li>';
                    }

                    if(strlen($erroriSped)==0){

                        $db->addSpedizione($user, $nome_cognome, $indirizzo, $numero_civico, $cap, $tel);
                        $nome_cognome = 'Mario Rossi';
                        $indirizzo = 'Inserire indirizzo';
                        $numero_civico = 'Inserire numero civico';
                        $cap = 'Inserire CAP';
                        $tel = 'Inserire numero telefonico';
                        $successoDest = '<ul class = "successo"><li>Hai inserito una nuova destinazione con successo!</li></ul>';


                    }else{
                        $erroriSped ='<ul class="errore">'.$erroriSped.'</ul>';
                    }


                 }else {
                     header('location: errore500.php');
                 }

              //Controllo se è stato premuto il bottone per salvare il metodo di pagamento

             }elseif (isset($_POST['dati_pagamento'])){
                 $intestatario = htmlentities(trim($_POST['intestatario_carta']));
                 $num_carta = htmlentities(trim($_POST['num_carta']));
                 $mese_scadenza = htmlentities(trim($_POST['mese_scad']));
                 $anno_scadenza = htmlentities(trim($_POST['anno_scad']));

                 $db = new DBAccess();

                 if($db->openDBConnection()){

                     if(!checkNomeCognome($intestatario)){
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

                    if(!checkMaxLen($intestatario, 40)){
                        $erroriPaga.='<li>Il campo intestatario non deve contenere più di 40 caratteri</li>';
                    }
                    if(!checkMaxLen($num_carta, 16)){
                        $erroriPaga.='<li>Il numero della carta non deve contenere più di 16 caratteri</li>';
                    }


                     if(strlen($erroriPaga)==0){

                         $db->modificaPagamento($user, $intestatario, $num_carta, $mese_scadenza, $anno_scadenza);
                         $successoPaga = '<ul class = "successo"><li>Hai modificato il tuo metodo di pagamento con successo!</li></ul>';

                     }else {
                         $erroriPaga ='<ul class="errore">'.$erroriPaga.'</ul>';

                     }


                 }else{
                     header('location: errore500.php');
                 }



             }elseif(isset($_POST['indirizzoDest'])){
                $indice = $_POST['indirizzoDest'];
                $db = new DBAccess();

                if($db->openDBConnection()){
                    $db->eliminaDestinazione($indice);
                    $successoEliminaDest = '<ul class="successo"><li>Destinazione eliminata con successo!</li></ul>';
                }else {
                    header('location: errore500.php');
                }
             }

             $paginaHTML = file_get_contents('html/gestione_profilo_utente.html');

             //Creo il form del cambio password

             $formPassword = '<fieldset>
                <legend>Informazioni personali: </legend>
                <messaggio1 />
                <h2>Nome Utente</h2>
		<p>
                <label for="username">Nome utente: </label>
                <input type="text" id="username" name="username" value="'.$user.'" readonly="readonly"/>
    		</p>
                <h2 id="cp">Cambia <span lang="en">Password:</span> </h2>
		<p>
                <label for="v_password">Inserisci la vecchia <span lang="en">password</span>: </label>
                <input type="password" id="v_password" name="v_password" />
		</p>
		<p>
                <label for="password">Inserisci la nuova <span lang="en">password</span>: </label>
                <input type="password" id="password" name="password" />
		</p>
		<p>
                <label for="c_password">Conferma la nuova <span lang="en">password</span>: </label>
                <input type="password" id="c_password" name="c_password" value=""/>
		</p>
	
                <input class="defaultButton" type="submit" name="dati_personali" value="Salva" onclick="return validazioneFormPassw_gestioneProfilo();"/>  <!--Submit legato solo al cambio della password-->
            </fieldset>';


             //Creo il form della spedizione

             $formSpedizione ='<fieldset>
                                <legend id="is" >Aggiungi un metodo di spedizione: </legend>
                                <messaggio2 />
				
				                <p>
                                <label for="nome_cognome">Nome e Cognome: </label>
                                <input type="text" name="nome_cognome" id="nome_cognome" placeholder="'.$nome_cognome.'"/>
				                </p>
				                <p>
                                <label for="indirizzo">Indirizzo: </label>
                                <input type="text" id="indirizzo" name="indirizzo" placeholder="'.$indirizzo.'"/>
				                </p>
				                <p>
                                <label for="civico">Numero civico: </label>
                                <input type="text" id="civico" name="civico" placeholder="'.$numero_civico.'"/>
				                </p>
				                <p>
                                <label for="cap"><abbr title="Codice di Avviamento Postale">CAP</abbr> :</label>
                                <input type="text" id="cap"  name="cap" placeholder="'.$cap.'"/>
				                </p>
                                <label for="comune">Comune: </label>
                                <input type="text" id="comune" name="comune" value="Padova" disabled="disabled"/>
                                <label for="provincia">Provincia: </label>
                                <input type="text" id="provincia" name="provincia" value="Padova" disabled="disabled"/>
                                <label for="stato">Stato: </label>
                                <input type="text" id="stato" name="stato" value="Italia" disabled="disabled"/>
				                <p>
                                <label for="tel">Numero di telefono: </label>
                                <input type="text" id="tel" name="tel" placeholder="'.$tel.'" />
                    	       	</p>
	
                                <input class="defaultButton" type="submit" name="dati_spedizione" value="Salva" onclick="return validazioneFormDest_gestione_profilo_utente();" /> <!--Submit legato solo alle informazioni di spedizione-->
                           </fieldset>';


            //Creo il form del pagamento


             $years ='<p>
                    <label for="anno_scad">Anno di scadenza: </label>
                    <select name="anno_scad" id="anno_scad">
                    <option>- Anno -</option>';
             $annoCorrente = date("Y");
             for($i = 0; $i<20; $i++ ){
                 $years.='<option value="'.($annoCorrente+$i).'">'.($annoCorrente+$i).'</option>';
             }

             $years.='</select></p>
             <input class="defaultButton" type="submit" name="dati_pagamento" value="Salva" onclick="return validazioneFormPaga_gestione_profilo_utente();" /> </fieldset>';

        $formPagamento ='<fieldset>
            <legend id="ip">Informazioni di pagamento: </legend>
            <messaggio3 />
		      <p>
                <label for="intestatario_carta">Intestatario carta: </label>
                <input type="text" name="intestatario_carta" id="intestatario_carta" placeholder="'.$intestatario.'" />
		      </p>
		      <p>
                <label for="num_carta">Numero carta: </label>
                <input type="text" name="num_carta" id="num_carta" placeholder="'.$num_carta.'" />
		      </p>
		      <p>
            <label for="mese_scad">Mese di scadenza: </label>  
            <select name="mese_scad" id="mese_scad">
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
	     </p>
        '.$years;


        //Creo la lista delle destinazioni dell'utente

        if($db->openDBConnection()){
            $queryResult = $db->getDestinazioni($user);
        } else {
            header('location: errore500.php');
        }

        if($queryResult == null){
            $listaDestinazioni = '<fieldset id="listaDestinazioni"><messaggioEliminaz />
                <legend> Lista delle destinazioni</legend><select disabled="disabled" name="indirizzoDest"><option value="00">Non è presente nessuna destinazione salvata!</option></select><input class="defaultButton" type="submit" value="Elimina"/></fieldset>';
        }else{

            while($row = mysqli_fetch_assoc($queryResult)) {
            $listaDestinazioni.='<option value ="'.$row['id_destinazione'].'">
                                    '.$row['nome_cognome'].', indirizzo: '.$row['via'].' '.$row['numero_civico'].', '.$row['CAP'].'</option>';
            }

            $listaDestinazioni = '<fieldset id="listaDestinazioni"><legend> Lista delle destinazioni</legend><select name="indirizzoDest">'.$listaDestinazioni.'</select><input class="defaultButton" type="submit" value="Elimina"/></fieldset>';

        }


        




            //Cambio i form se sono stati compiuti degli errori
            if(strlen($erroriSped)!=0){
                $formSpedizione ='<fieldset>
                    <legend id="is" >Aggiungi un metodo di spedizione: </legend>
                    <messaggio2 />
		          <p>
                    <label for="nome_cognome">Nome e Cognome: </label>
                    <input type="text" name="nome_cognome" id="nome_cognome" value="'.$nome_cognome.'"/>
		          </p>
		          <p>
                    <label for="indirizzo">Indirizzo: </label>
                    <input type="text" id="indirizzo" name="indirizzo" value="'.$indirizzo.'"/>
		          </p>
		          <p>
                    <label for="civico">Numero civico: </label>
                    <input type="text" id="civico" name="civico" value="'.$numero_civico.'"/>
		           </p>
		          <p>
                    <label for="cap"><abbr title="Codice di Avviamento Postale">CAP</abbr> :</label>
                    <input type="text" id="cap"  name="cap" value="'.$cap.'"/>
		           </p>
                    <label for="comune">Comune: </label>
                    <input type="text" id="comune" name="comune" value="Padova" disabled="disabled"/>
                    <label for="provincia">Provincia: </label>
                    <input type="text" id="provincia" name="provincia" value="Padova" disabled="disabled"/>
                    <label for="stato">Stato: </label>
                    <input type="text" id="stato" name="stato" value="Italia" disabled="disabled"/>
		          <p>
                    <label for="tel">Numero di telefono: </label>
                    <input type="text" id="tel" name="tel" value="'.$tel.'" />
		           </p>
		
        
                    <input class="defaultButton" type="submit" name="dati_spedizione" value="Salva" onclick="return validazioneFormDest_gestione_profilo_utente();" /> <!--Submit legato solo alle informazioni di spedizione-->
                    </fieldset>';
            }
            if(strlen($erroriPaga)!=0){
                $formPagamento ='<fieldset>
                                    <legend id="ip">Informazioni di pagamento: </legend>
                                    <messaggio3 />
				    
		                              <p>
                                        <label for="intestatario_carta">Intestatario carta: </label>
                                        <input type="text" name="intestatario_carta" id="intestatario_carta" value="'.$intestatario.'" />
                                        </p>
		                              <p>
					<label for="num_carta">Numero carta: </label>
                                        <input type="text" name="num_carta" id="num_carta" value="'.$num_carta.'" />
				                </p>
		                          <p>
                                    <label for="mese_scad">Mese di scadenza: </label>
                                    <select name="mese_scad" id="mese_scad">
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
				                </p>
		
                                '.$years;
            }

            //Inserisco i form e i messaggi nella pagina HTML
             $paginaHTML = str_replace('<formSpedizione2 />', $listaDestinazioni, $paginaHTML);
             $paginaHTML = str_replace('<messaggioEliminaz />', $successoEliminaDest, $paginaHTML);
             $paginaHTML = str_replace('<formPassword />', $formPassword, $paginaHTML);
             $paginaHTML = str_replace('<formSpedizione />', $formSpedizione, $paginaHTML);
             $paginaHTML = str_replace('<formPagamento />', $formPagamento, $paginaHTML);

             
             if(strlen($successoPass)!=0){
                 $paginaHTML = str_replace('<messaggio1 />', $successoPass, $paginaHTML);
             }else {
                 $paginaHTML = str_replace('<messaggio1 />', $erroriPass, $paginaHTML);
             }
             if(strlen($successoDest)!=0){
                 $paginaHTML = str_replace('<messaggio2 />', $successoDest, $paginaHTML);
             }else {
                 $paginaHTML = str_replace('<messaggio2 />', $erroriSped, $paginaHTML);
             }
             if(strlen($successoPaga)!=0){
                 echo str_replace('<messaggio3 />', $successoPaga, $paginaHTML);
             }else {
                 echo str_replace('<messaggio3 />', $erroriPaga, $paginaHTML);
             }

	}else header('location: login.php');

?>
