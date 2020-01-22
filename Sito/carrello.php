<?php
	require_once "php/dbaccess.php";
	
	session_start();
		
	if(!isset($_SESSION['carrello'])) {
		$_SESSION['carrello'] = array();
	}
	
	if( isset($_SESSION['username']) ) {
		if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
			if( isset($_POST['action']) ) {
				if( $_POST['action'] == 'edit' ) {
					setQuantita($_POST['name'],$_POST['quantity']);
				} else if($_POST['action'] == 'remove') {
					rmProdotto($_POST['name']);
				} else {
					header('Content-Type: application/json');
					echo json_encode(array('result'=>false,'error'=>'The request is not valid'));
					exit;
				}
			} else {
				header('Content-Type: application/json');
				echo json_encode(array('result'=>false,'error'=>'The request is not valid'));
				exit;
			}
		} else {
			loadPagina();
		}
	} else {
		if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
			header('Content-Type: application/json');
			echo json_encode(array('result'=>false,'error'=>'The request is not valid'));
			exit;
		} else {
			header("location: errore403.php");
		}
	}
		
	function loadPagina() {
		if( count($_SESSION['carrello']) > 0 ) {
			
			$totale = 0;
			$content = '<dl class="defaultLista">';
			
			foreach( $_SESSION['carrello'] AS $row ) {
				$content .= '<dt id="dt-'.$row['nome'].'">'.
					$row['nome'].' - <a href="prodotti.html#'.$row['categoria'].'">'.$row['categoria'].'</a>
					<input title="Rimuovi '.$row['nome'].'" class="rimuovi" type="button" name="rimuovi" onclick="rmProdotto(\''.$row['nome'].'\')" value="Rimuovi" />
				</dt>
				<dd id="dd-'.$row['nome'].'">
					<input title="sottrai '.$row['nome'].' di 1" type="button" name="minus" onclick="rmQuantita(\''.$row['nome'].'\')" value="-" /><!-- togli_prodotto -->
					<input id="qt-'.$row['nome'].'" type="number" name="quantita" readonly="readonly" value="'.$row['quantita'].'" /><!-- numero prodotto -->
					<input title="aggiungi '.$row['nome'].' di 1" type="button" name="plus" onclick="addQuantita(\''.$row['nome'].'\')" value="+" /><!-- aggiungi_prodotto -->
					<span id="tot-'.$row['nome'].'">Prezzo: '.number_format($row['prezzo'], 2, ',', '.').'€</span>
				</dd>';
			}
			
			$content .= '</dl>
			<p class="totaleText">Totale: <span id="totaleValue">'.number_format(getTotaleCarrello(), 2, ',', '.').'</span>€</p>
			<a id="paga" href="pagamento.php">Vai a pagamento</a>';
		
		} else {
			$content = '<p>Il carrello attualmente è vuoto. Se vuoi ordinare qualcosa recati alla pagina <a href="take_away.php" lang="en">Take Away</a></p>';
		}
		$paginaHTML = file_get_contents('html/carrello.html');
	
		$paginaHTML = str_replace('<menu />',getMenu(),$paginaHTML);
		$paginaHTML = str_replace('<carrello/>',$content,$paginaHTML);
		
		echo $paginaHTML;
	}
	
	function setQuantita($nome,$nuovaQuantita) {
		$result = array(
			'success' => false
		);
		
		if(isset($_SESSION['carrello'][$nome])) {
			if( checkNumeroIntero($nuovaQuantita) ) {
				$prodotto = &$_SESSION['carrello'][$nome];
				
				$vecchiaQuantita = $prodotto['quantita'];
				$newPrezzo = $prodotto['prezzo']/$vecchiaQuantita*$nuovaQuantita;
				
				$prodotto['quantita'] = $nuovaQuantita;
				$prodotto['prezzo'] = $newPrezzo;
				
				$result['success'] = true;
				$result['price'] = $newPrezzo;
				$result['quantity'] = $nuovaQuantita;
				$result['total'] = getTotaleCarrello();
			} else {
				$result['error'] = 'invalid quantity';
				$result['total'] = getTotaleCarrello();
			}
		} else {
			$result['error'] = 'not found';
			$result['total'] = getTotaleCarrello();
		}
		
		
		header('Content-Type: application/json');
		echo json_encode($result);
		exit;
	}
	
	function rmProdotto($nome) {
		
		$result = array();
		
		if( isset($_SESSION['carrello'][$nome]) ) {
			unset($_SESSION['carrello'][$nome]);
			
			$result['success'] = true;
			$result['total'] = getTotaleCarrello();
		} else {
			$result['success'] = false;
			$result['error'] = 'not found';
			$result['total'] = getTotaleCarrello();
		}
		
		header('Content-Type: application/json');
		echo json_encode($result);
		exit;
	}

	function getTotaleCarrello() {
		$totale = 0;
		
		foreach( $_SESSION['carrello'] AS $row ) {
			$totale += $row['prezzo'];
		}
		
		return $totale;
	}
?>