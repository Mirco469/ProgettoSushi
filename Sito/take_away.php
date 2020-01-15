<?php
	require_once("php/dbaccess.php");

	session_start();
	#Login per testare la pagina;
	$_SESSION['username'] = "user"; #da togliere
	$_SESSION['password'] = "user"; #da togliere

	if (isset($_SESSION['username']) && isset($_POST['action']) && $_POST['action'] == 'aggiungi')
	{
		$db =  new DBAccess();
		if ($db->openDBConnection())
		{
			$nomeProdotto = $_POST['name'];
			$prodotto = $db->getInfoProdotto($nomeProdotto);
			if (!alreadyInCart($prodotto['nome']))
			{
				addToCart($prodotto['nome'], $prodotto['categoria'], $prodotto['prezzo']);
			}
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
		else
		{
			header("Location: errore500.php");
		}
	}

	function alreadyInCart($nome)
	{
		if (isset($_SESSION['carrello']))
		{
			foreach($_SESSION['carrello'] AS $prodotto)
			{
				if ($prodotto == $nome)
				{
					return true;
				}
			}
		}
		return false;
	}

	function addToCart($nome, $categoria, $prezzo)
 	{
  		$prodotto = array("nome"=>$nome, "categoria"=>$categoria, "quantita"=>1, "prezzo"=>$prezzo);
  		if (!isset($_SESSION['carrello']))
  		{
   			$_SESSION['carrello'] = array($nome=>$prodotto);
  		}
  		else
  		{
   			$_SESSION['carrello'][$nome] = $prodotto;
  		}
 	}
?>
