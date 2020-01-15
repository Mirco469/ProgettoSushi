<?php
	require_once "php/dbaccess.php";
	
	session_start();
		
	if(!isset($_SESSION['carrello'])) {
		$_SESSION['carrello'] = array();
	}
	
	if( isset($_POST['action']) ) {
		if( $_POST['action'] == 'edit' ) {
			setQuantita($_POST['name'],$_POST['quantity']);
		} else if($_POST['action'] == 'remove') {
			rmProdotto($_POST['name']);
		}
		//echo json_encode(array('index'=>$_POST['index'],'amount'=>$_POST['amount']));exit;
	} else {
		/*
		$_SESSION['carrello'] = array(
			'maguro' => array(
				'nome' => 'maguro',
				'categoria' => 'uramaki',
				'quantita' => 2,
				'prezzo' => 4
			),
			'tartara di tonno' => array(
				'nome' => 'tartara di tonno',
				'categoria' => 'antipasti',
				'quantita' => 1,
				'prezzo' => 6
			)
		);
		*/
		loadPagina();
	}
		
	function loadPagina() {
		if( session_status() != PHP_SESSION_NONE && $_SESSION['username'] != null ) {
			
			if( count($_SESSION['carrello']) > 0 ) {
				$carrello = $_SESSION['carrello'];
				
				$totale = 0;
				$content = '<dl class="defaultLista">';
				
				foreach( $carrello AS $row ) {
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
				<a id="paga" href="pagamento.html">Vai a pagamento</a>';
			
			} else {
				$content = '<p>Il carrello attualmente è vuoto. Se vuoi ordinare qualcosa recati alla pagina <a href="takeaway.php" lang="en">Take Away</a></p>';
			}
			$paginaHTML = file_get_contents('html/carrello.html');
		
			$paginaHTML = str_replace('<menu />',getMenu(),$paginaHTML);
			$paginaHTML = str_replace('<carrello/>',$content,$paginaHTML);
			
			echo $paginaHTML;
		} else {
			header("location: errore403.php");
		}
	}
	
	function setQuantita($nome,$nuovaQuantita) {
		$result = array(
			'success' => false
		);
		
		if(isset($_SESSION['carrello'][$nome])) {
			if( checkNumeroIntero($nuovaQuantita) ) {
				
				$vecchiaQuantita = $_SESSION['carrello'][$nome]['quantita'];
				$newPrezzo = $_SESSION['carrello'][$nome]['prezzo']/$vecchiaQuantita*$nuovaQuantita;
				
				$_SESSION['carrello'][$nome]['quantita'] = $nuovaQuantita;
				$_SESSION['carrello'][$nome]['prezzo'] = $newPrezzo;
				
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
		$result['carrello'] = $_SESSION['carrello'];
		
		
		header('Content-Type: application/json');
		echo json_encode($result);
	}
	
	function rmProdotto($nome) {
		$prodotto = &$_SESSION['carrello'][$nome];
		
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
	}

	function getTotaleCarrello() {
		$totale = 0;
		
		foreach( $_SESSION['carrello'] AS $row ) {
			$totale += $row['prezzo'];
		}
		
		return $totale;
	}
?>