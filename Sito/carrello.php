<?php
	require_once "php/dbaccess.php";
	
	session_start();
	
	if( isset($_POST['action']) ) {
		if( $_POST['action'] == 'edit' ) {
			changeQuantity($_POST['name'],$_POST['category'],intval($_POST['quantity']));
		} else if($_POST['action'] == 'remove') {
			removeProduct($_POST['name'],$_POST['category']);
		}
		//echo json_encode(array('index'=>$_POST['index'],'amount'=>$_POST['amount']));exit;
	} else {
		$_SESSION['carrello'] = array(
			0 => array(
				'nome' => 'maguro',
				'categoria' => 'uramaki',
				'quantita' => 2,
				'prezzo' => 4
			),
			1 => array(
				'nome' => 'tartara di tonno',
				'categoria' => 'antipasti',
				'quantita' => 1,
				'prezzo' => 6
			)
		);
		loadPage();
	}
		
	function loadPage() {
		if( session_status() != PHP_SESSION_NONE && $_SESSION['username'] != null ) {
			$db = new DBAccess();
			
			$carrello = $_SESSION['carrello'];
			if( $_SESSION['carrello'] != null ) {
				
				$totale = 0;
				$content = '<dl class="defaultLista">';
				
				foreach( $carrello AS $row ) {
					$content .= '<dt>'.$row['nome'].' - <a href="prodotti.html#'.$row['categoria'].'">'.$row['categoria'].'</a><input class="rimuovi" type="button" name="rimuovi" value="Rimuovi" /></dt>
					<dd>
						<input type="button" name="minus" value="-" /><!-- togli_prodotto -->
						<input type="number" name="quantita" readonly="readonly" value="'.$row['quantita'].'" /><!-- numero prodotto -->
						<input type="button" name="plus" value="+" /><!-- aggiungi_prodotto -->
						<span>Prezzo: '.number_format($row['prezzo'], 2, ',', '.').'€</span>
					</dd>';
				}
				
				$content .= '</dl>
				<p class="totaleText">Totale: <span>'.number_format(getCartTotal(), 2, ',', '.').'€</span></p>
				<a id="paga" href="pagamento.html">Vai a pagamento</a>';
			
			} else {
				$content = '<p>Il carrello attualmente è vuoto. Se vuoi ordinare qualcosa recati alla pagina <a href="takeaway.php" lang="en">Take Away</a></p>';
			}
			$paginaHTML = file_get_contents('html/carrello.html');
		
			$paginaHTML = str_replace('<menu />',getMenu(),$paginaHTML);
			$paginaHTML = str_replace('<carrello/>',$content,$paginaHTML);
			
			echo $paginaHTML;
		} else {
			header("location: errore403.html");
		}
	}
	
	function changeQuantity($name,$category,$newQuantity) {
		$i = getIndexByNameCategory($name,$category);
		
		$oldQuantity = $_SESSION['carrello'][$i]['quantita'];
		$newPrezzo = $_SESSION['carrello'][$i]['prezzo']/$oldQuantity*$newQuantity;
		
		$_SESSION['carrello'][$i]['quantita'] = $newQuantity;
		$_SESSION['carrello'][$i]['prezzo'] = $newPrezzo;
		
		header('Content-Type: application/json');
		echo json_encode(array(
			'success'=>true,
			'price'=>$newPrezzo,
			'quantity'=>$newQuantity,
			'total'=>getCartTotal(),
			'carrello'=>$_SESSION['carrello'],
			'index'=>$i
		));
	}
	
	function removeProduct($name,$category) {
		$i = getIndexByNameCategory($name,$category);
		
		unset($_SESSION['carrello'][$i]);
		
		header('Content-Type: application/json');
		echo json_encode(array(
			'success'=>true,
			'total'=>getCartTotal()
		));
	}
	
	function getIndexByNameCategory($name,$category) {
		foreach( $_SESSION['carrello'] AS $index => $row ) {
			if( $row['nome'] === $name ) {
				if( $row['categoria'] === $category ) {
					return $index;
				}
			}
		}
		return -1;
	}

	function getCartTotal() {
		$totale = 0;
		
		foreach( $_SESSION['carrello'] AS $row ) {
			$totale += $row['prezzo'];
		}
		
		return $totale;
	}
?>