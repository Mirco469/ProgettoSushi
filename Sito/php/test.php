<?php
    require_once("dbaccess.php");
    $oggettoConnessione =  new DBAccess();
    if(!$oggettoConnessione->openDBConnection())
    {
        echo "errore";
    }
    //$risultato = $oggettoConnessione->getProdotti("Uramaki");
    //$risultato = $oggettoConnessione->getIndirizzi('utente');
    //$risultato = $oggettoConnessione->getRecensioni();
    $risultato = checkPrezzo("Takosu");

    /*foreach($risultato as $riga)
    {
        //echo $riga['Nome']."--------".$riga['Pezzi']."--------".$riga['Prezzo']."--------".$riga['Descrizione'];
        //echo $riga['Via']."--------".$riga['Num'];
        //echo $riga['Titolo']."--------".$riga['Data']."--------".$riga['Utente']."--------".$riga['Testo'];
        echo "</br>";
    }*/
    if($risultato)
    {
        echo "Va bene";
    }
    else
    {
        echo "Nope";
    }
?>
