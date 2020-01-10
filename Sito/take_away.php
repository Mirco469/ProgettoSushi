<?php
	require_once("php/dbaccess.php");

	session_start();

	$paginaHTML = file_get_contents('html/take_away.html');

	$oggettoConnessione =  new DBAccess();

	if ($oggettoConnessione->openDBConnection())
	{
		#Stampo la porzione di menu corretta
		$menu = getmenu();
		$paginaHTML = str_replace('<menu />', $menu, $paginaHTML);

		#Prendo tutti i prodotti e li stampo per categoria
        $listaProdotti = "";
        foreach(getCategorie() as $categoriaSingola)
        {
			$id = str_replace(' ', '_', $categoriaSingola);
    	    $listaProdotti .= "
    	        <div class=\"lista_piatti\">
    		    <h1 id=\"$id\">$categoriaSingola</h1>
    		    <dl>";
      		foreach($oggettoConnessione->getProdotti($categoriaSingola) as $singoloProdotto)
        	{
        		$nomeP = $singoloProdotto["Nome"];
          	    $pezziP = $singoloProdotto["Pezzi"];
				$prezzoP = $singoloProdotto["Prezzo"];
				$descrizioneP = $singoloProdotto["Descrizione"];
				$listaProdotti .= "
					<dt>$nomeP<input class=\"buttonSmall\" type=\"button\" name=\"Aggiungi\" value=\"Aggiungi\" /></dt>
					<dd>$prezzoP &euro;</dd>
					<dd><span>[$pezziP<abbr title=\"Pezzi\">pz</abbr>]</span> $descrizioneP</dd>";
			}
			$listaProdotti .= "
				</dl>
			    </div>";
		}
		$paginaHTML = str_replace('<listaProdotti />', $listaProdotti, $paginaHTML);
        echo $paginaHTML;
	}
	else #Errore di connessione al databse
	{
		header("Location: /errore500.php");
	}

?>
