<?php
    require_once("../php/dbaccess.php");
    $db = new DBAccess();
    //********* DA RIMUOVERE *****************
    session_start();
    $_SESSION['autorizzazione'] = 'admin';
    $_SESSION['username'] = 'ammin';
    //****************************************
    if(isset($_SESSION['username'])) {
        if($db->openDBConnection()){
            if (isset($_SESSION['autorizzazione']) && $_SESSION['autorizzazione'] == 'admin') {

                if (isset($_POST['inserisci'])) {
                    echo $db->inserisciNews(trim($_POST['titolo']), trim($_POST['data']), trim($_POST['notizia']));
                }
                $query = $this->connection->prepare('SELECT * FROM News');
                $query->execute();
                $queryResult = $query->get_result();


                $paginaHTML = file_get_contents('home_admin.html');
                $notizie = '';

                while ($row = $queryResult->fetch_assoc()) {
                    $notizie .= "<dt>" . $row['data'] . " - " . $row['titolo'] . "</dt>
                                        <dd>" . $row['descrizione'] . "</dd>
                                        <dd><input type=\"button\" name=\"elimina\" value=\"Elimina\"/></dd>
                                    ";
                }
                echo str_replace('<notizie />', $notizie, $paginaHTML);


            } else {
                header('location: errore403.html');
            }
        }
        header('location: errore500.html');
    } else {
        header('location: errore404.html');
    }

?>