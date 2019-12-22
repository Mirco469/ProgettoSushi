<?php
    require_once("../php/dbaccess.php");

    //***************************
    session_start();
    $_SESSION['username']= 'admin';
    $_SESSION['autorizzazione']='admin';
    //***************************

    if( isset($_SESSION['username'])) {
        if(isset($_SESSION['autorizzazione']) && $_SESSION['autorizzazione']=='admin') {
            $user = $_SESSION['username'];

            $old_password = '';
            $c_old_password = '';
            $new_password = '';
            $c_new_password = '';


            $erroriPass = '';


            if (isset($_POST['dati_personali'])) {

                $db = new DBAccess();
                if ($db->openDBConnection()) {

                    $old_password = $db->getPassword($user);
                    if ($old_password !== null) {

                        $c_old_password = htmlentities(trim($_POST['v_password']));
                        $new_password = htmlentities(trim($_POST['password']));
                        $c_new_password = htmlentities(trim($_POST['c_password']));


                        if ($c_old_password !== $old_password) {
                            $erroriPass .= '<li>La password che ha inserito non coincide con quella salvata</li>';
                        }
                        if (!checkMinLen($new_password)) {
                            $erroriPass .= '<li>La nuova password che hai inserito deve essere lunga almeno 2 caratteri</li>';
                        }
                        if ($new_password !== $c_new_password) {
                            $erroriPass .= '<li>Le due password che hai inserito non coincidono</li>';
                        }

                    } else {

                        $erroriPass .= '<li>La password che hai inserito non e\' stata trovata nel nostro database</li>';
                    }

                    if (strlen($erroriPass) == 0) {
                        $db->modificaPassword($user, $new_password);

                    } else {
                        $erroriPass = '<ul class="errore">' . $erroriPass . '</ul>';
                    }

                } else {
                    header('location: errore500.html');
                }


            }
            
            $paginaHTML = file_get_contents('gestione_profilo_admin.html');

            $formPassword = '<fieldset>
                     <legend>Informazioni personali: </legend>
                     <h2>Nome Utente</h2>
                     <label for="username">Nome utente: </label>
                     <input type="text" id="username" name="username" value="' . $user . '" readonly="readonly"/>
         
                     <h2 id="cp">Cambia <span lang="en">Password:</span> </h2>
                     <label for="v_password">Inserisci la vecchia <span lang="en">password</span>: </label>
                     <input type="password" id="v_password" name="v_password" />
                     <label for="password">Inserisci la nuova <span lang="en">password</span>: </label>
                     <input type="password" id="password" name="password" />
                     <label for="c_password">Conferma la nuova <span lang="en">password</span>: </label>
                     <input type="password" id="c_password" name="c_password" value=""/>
                     <input class="defaultButton" type="submit" name="dati_personali" value="Salva"/>  <!--Submit legato solo al cambio della password-->
                 </fieldset>';

            $paginaHTML = str_replace('<formPassword />', $formPassword, $paginaHTML);
            echo str_replace('<messaggio />', $erroriPass, $paginaHTML);

        }else {
            header('location: errore403.html');
        }
    }else {
        header('location: errore403.html');
    }



?>