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

            if (isset($_SESSION['autorizzazione']) && $_SESSION['autorizzazione'] == 'Admin') {



                if (isset($_POST['inserisci'])) {
                    $titolo = htmlentities(trim($_POST['titolo']));
                    $data = date("Y-m-d");
                    $testo =  trim($_POST['notizia']);

                    if(!checkAlfanumerico($titolo)){
                        $erroriNews .= '<li>Il titolo deve contenere almeno due caratteri e non caratteri speciali</li>';
                    }

                    if(!checkAlfanumerico($testo)){
                        $erroriNews .= '<li>Il testo deve contenere almeno due caratteri e non caratteri speciali</li>';
                    }
                    if(strlen($testo)>150){
                        $erroriNews .= '<li>Il testo deve contenere meno di 151 caratteri</li>';
                    }

                    if(strlen($erroriNews)==0){
                        $db->inserisciNews($titolo, $data, $testo, $username);
                        $titolo = '';
                        $testo = '';
                    }else{
                        $erroriNews = '<ul class="errore">'.$erroriNews.'</ul>';
                    }


                }

                $queryResult = $db->getNews();

                $paginaHTML = file_get_contents('html/home_admin.html');

                $formNews = '<fieldset>
                            <messaggio />
                                <legend>Inserisci la notizia</legend>
                                <label for="titolo">Inserisci il titolo: </label>
                                <input type="text" id="titolo" name="titolo" value="'.$titolo.'"/>
                                <label for="notizia">Inserisci il testo: </label>
                                <textarea name="notizia" id="notizia" rows="4" cols="35" />'.$testo.'</textarea>
                                <input class="defaultButton" type="submit" name="inserisci" value="Inserisci"/>
                              </fieldset>';


                $notizie = '';
                $index = 0;
                while ($row = mysqli_fetch_assoc($queryResult)) {
                    $notizie .= "<dt>" . $row['data'] . " - " . $row['titolo'] . "</dt>
                                        <dd>" . $row['descrizione'] . "</dd>
                                        <dd><input onclick='eliminaNews(".$index.")' \"button\" name=\"elimina\" value=\"Elimina\"/></dd>
                                    ";
                    $index++;
                }
                $paginaHTML = str_replace('<formNews />', $formNews, $paginaHTML);
                $paginaHTML = str_replace('<messaggio />', $erroriNews, $paginaHTML);
                echo str_replace('<notizie />', $notizie, $paginaHTML);
                exit;

            } else {
                header('location: errore403.html');
            }
        }
        header('location: errore500.html');
    } else {
        header('location: errore404.html');
    }

?>