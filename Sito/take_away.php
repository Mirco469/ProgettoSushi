<?php
	require_once("php/dbaccess.php");

	session_start();

	if (isset($_SESSION['username']) && isset($_POST['action']) && $_POST['action'] == 'aggiungi')
	{
		$db =  new DBAccess();
		if ($db->openDBConnection())
		{
			$nomeProdotto = $_POST['name'];
			$prodotto = $db->getInfoProdotto($nomeProdotto);
			if ($prodotto == null) 
			{
				header('location: errore500.php');
			}
			addToCart($prodotto['nome'], $prodotto['categoria'], $prodotto['prezzo']);
		}
		else
		{
			header('location: errore500.php');
		}
	}
	else
	{
		caricaPagina();
	}

	function caricaPagina()
	{
		$db =  new DBAccess();
		if ($db->openDBConnection())
		{
			$paginaHTML = file_get_contents('html/take_away.html');
			$menu = getmenu();
			$paginaHTML = str_replace('<menu />', $menu, $paginaHTML);

	        $listaProdotti = "";
	        foreach(getCategorie() as $categoriaSingola)
	        {
				$id = str_replace(' ', '_', $categoriaSingola);
	    	    $listaProdotti .= "
	    	        <div class=\"lista_piatti\">
	    		    <h1 id=\"$id\">$categoriaSingola</h1>
	    		    <dl>";
	      		foreach($db->getProdotti($categoriaSingola) as $singoloProdotto)
	        	{
	        		$nomeP = $singoloProdotto["Nome"];
	          	    $pezziP = $singoloProdotto["Pezzi"];
					$prezzoP = $singoloProdotto["Prezzo"];
					$descrizioneP = $singoloProdotto["Descrizione"];
					$listaProdotti .= "<dt>$nomeP";
						if (isset($_SESSION['username']))
						{
							$listaProdotti .= "<input class=\"buttonSmall\" type=\"button\" name=\"Aggiungi\" value=\"Aggiungi\" />";
						}
						$listaProdotti .= "</dt>
						<dd class=\"prezzo\">$prezzoP &euro;</dd>
						<dd class=\"dettagli\"><span>[$pezziP<abbr title=\"Pezzi\">pz</abbr>]</span> $descrizioneP</dd>";
				}
				$listaProdotti .= "
					</dl>
				    </div>";
			}
			$paginaHTML = str_replace('<listaProdotti />', $listaProdotti, $paginaHTML);
	        echo $paginaHTML;
		}
		else
		{
			header("Location: errore500.php");
		}
	}

	function addToCart($nome, $categoria, $prezzo)
 	{
		$result = array('success' => false);
  		$prodotto = array("nome"=>$nome, "categoria"=>$categoria, "quantita"=>1, "prezzo"=>$prezzo);
  		if (!isset($_SESSION['carrello']))
  		{
   			$_SESSION['carrello'] = array($nome=>$prodotto);
			$result['success'] = true;
  		}
  		else
  		{
			if (!isset($_SESSION['carrello'][$nome]))
			{
				$_SESSION['carrello'][$nome] = $prodotto;
				$result['success'] = true;
			}
			else 
			{
				$result['error'] = 'already present';
			}
  		}
		if (($result['success'] == false) && !isset($result['error']))
		{
			$result['error'] = 'unknown';
		}
		header('Content-Type: application/json');
		echo json_encode($result);
 	}

?>
