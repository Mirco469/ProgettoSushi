<?php
    require_once("php/dbaccess.php");
    $db = new DBAccess();
    session_start();

    if($db->openDBConnection()){
        $paginaHTML = file_get_contents('html/home_utente.html');
        $menu = getMenu();

        $maxNews = 2;
        $queryNews = $db->getNewsUtente($maxNews);

        $notizie = '';
      
        if($queryNews == null){
            $notizie ="<dt>Al momento non ci sono notizie!<dt>
                          <dd>Appena ne avremo una sarai il/la primo/a a saperlo!</dd>"; 
        }else{
            while ($row = mysqli_fetch_assoc($queryNews)) {
            $notizie .= "<dt>" . $row['data'] . " - " . $row['titolo'] . "</dt>
                            <dd>" . $row['descrizione'] . "</dd>";
            }
        }
        


        $paginaHTML = str_replace('<menu />', $menu, $paginaHTML);
        echo str_replace('<notizie />',$notizie, $paginaHTML);


    }else {
        header('location: errore500.php');
    }


?>