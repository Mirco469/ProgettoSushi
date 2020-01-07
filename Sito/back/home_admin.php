<?php
    require_once("../php/dbaccess.php");
    $db = new DBAccess();

    session_start();
   


    if(isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        if($db->openDBConnection()){

            $titolo = '';
            $data = 'AAAA-MM-GG';
            $testo = '';
            $erroriNews = '';
            $successoNews = '';
            $messEliminazione = '';

            if (isset($_SESSION['autorizzazione']) && $_SESSION['autorizzazione'] == 'Admin') {



                if (isset($_POST['inserisci'])) {
                    $titolo = htmlentities(trim($_POST['titolo']));
                    $data = date("Y-m-d");
                    $testo =  trim($_POST['notizia']);

                    if(!checkTesto($titolo)){
                        $erroriNews .= '<li>Il titolo deve contenere almeno due caratteri e non caratteri speciali</li>';
                    }

                    if(!checkTesto($testo)){
                        $erroriNews .= '<li>Il testo deve contenere almeno due caratteri e non caratteri speciali</li>';
                    }
                    if(strlen($testo)>150){
                        $erroriNews .= '<li>Il testo deve contenere meno di 151 caratteri</li>';
                    }

                    if(strlen($erroriNews)==0){
                        $db->inserisciNews($titolo, $data, $testo, $username);
                        $successoNews = '<ul class="successo"><li>La notizia &egrave; stata aggiunta con successo!</li></ul>';
                        $titolo = '';
                        $testo = '';
                    }else{
                        $erroriNews = '<ul class="errore">'.$erroriNews.'</ul>';
                    }


                } elseif (isset($_POST['elimina'])) {
                    if(isset($_POST['scegliNews'])){
                        $indice=$_POST['scegliNews'];
                        $db->eliminaNews($indice);
                        $messEliminazione="<ul class='successo'><li>Notizia eliminata con successo!</li></ul>";
                    }else {
                        $messEliminazione = "<ul class='errore'><li>Seleziona una notizia!</li></ul>";
                    }

                }

                $queryResult = $db->getNews();

                $paginaHTML = file_get_contents('html/home_admin.html');

                $formNews = '<fieldset id="addNews">
                            <messaggio />
                                <legend>Inserisci la notizia</legend>
                                <label for="titolo">Inserisci il titolo: </label>
                                <input type="text" id="titolo" name="titolo" value="'.$titolo.'"/>
                                <label for="notizia">Inserisci il testo: </label>
                                <textarea name="notizia" id="notizia" rows="4" cols="35" />'.$testo.'</textarea>
                                <input class="defaultButton" type="submit" name="inserisci" value="Inserisci"/>
                              </fieldset>';


                $notizie = '';
                if(mysqli_num_rows($queryResult)>=1){
                    $row = mysqli_fetch_assoc($queryResult);
                    $notizie = '<input checked="checked" type="radio" name="scegliNews" value="'.$row['id_news'].'" id="radio' . $row['id_news'] . '" aria-labelledby="radio' . $row['id_news'] . '-help"/>
                               <label for="radio' . $row['id_news'] .'">'.$row['data']." - ".$row['titolo'].'</label>
                               <span id="radio' . $row['id_news'] . '-help">'.$row['descrizione'].'</span>';
                }
                while ($row = mysqli_fetch_assoc($queryResult)) {

                    $notizie .= '<input type="radio" name="scegliNews" value="'.$row['id_news'].'" id="radio' . $row['id_news'] . '" aria-labelledby="radio' . $row['id_news'] . '-help"/>
                               <label for="radio' . $row['id_news'] .'">'.$row['data']." - ".$row['titolo'].'</label>
                               <span id="radio' . $row['id_news'] . '-help">'.$row['descrizione'].'</span>';

                }



                $notizie = '<fieldset id="formNews"> 
                                <messaggio1 />
                                <legend> Notizie </legend>'.$notizie.
                            '<input class="defaultButton" type="submit" name="elimina" value="Elimina"/>
                                </fieldset>';

                $paginaHTML = str_replace('<formNews />', $formNews, $paginaHTML);
                if(strlen($successoNews)!=0){
                    $paginaHTML = str_replace('<messaggio />', $successoNews, $paginaHTML);
                }else{
                    $paginaHTML = str_replace('<messaggio />', $erroriNews, $paginaHTML);
                }
                $paginaHTML = str_replace('<notizie />', $notizie, $paginaHTML);
                echo str_replace('<messaggio1 />', $messEliminazione, $paginaHTML);
                exit;

            } else {
                header('location: ../errore403.html');
            }
        }
        header('location: errore500.html');
    } else {
        header('location: ../login.php');
    }

?>
