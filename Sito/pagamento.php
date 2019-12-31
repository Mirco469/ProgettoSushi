<?php

	require_once("php/dbaccess.php");

    session_start();
	#Login per testare la pagina;
	$_SESSION['username'] = "user";
	$_SESSION['password'] = "user";

	if (isset($_SESSION['username']))
	{
		$user = $_SESSION['username'];

		$db =  new DBAccess();

		if ($db->openDBConnection())
		{
			$erroriDest = "";
			$successoDest = "";
			$nome_cognome = "";
			$via = "";
			$civico = "";
			$cap = "";
			$tel = "";

			$erroriCarta = "";
			$successoCarta = "";
			$intestatario = "";
			$num_carta = "";
			$mese_scad = "";
			$anno_scad = "";
			$cvv = "";

			//Controllo se ho già ricevuto la post dalla form o no;
			if (isset($_POST['paga']))
			{
				#Primo fieldset;
				if (isset($_POST['destinazione']) && $_POST['destinazione'] != 'Indirizzo')
				{
					$successoDest = '<p class="successo">Informazioni di spedizione valide!</p>';
				}
				else
				{
					#Controllo i dettagli di spedizione;
					$nome_cognome = htmlentities(trim($_POST['nome_cognome']));
					$via = htmlentities(trim($_POST['via']));
					$civico = htmlentities(trim($_POST['civico']));
					$cap = htmlentities(trim($_POST['cap']));
					$tel = htmlentities(trim($_POST['tel']));

					if (!checkNomeCognome($nome_cognome))
					{
						$erroriDest .= "<li>Il nome deve contenere solo lettere e non contenere meno di due caratteri</li>";
					}
					if (!checkAlfanumericoESpazi($via))
					{
						$erroriDest .= "<li>La Via non deve contenere caratteri speciali</li>";
					}
					if (!checkSoloNumeri($civico))
					{
						$erroriDest .= "<li>Il numero civico deve contenere solo numeri</li>";
					}
					if (!checkCAP($cap))
					{
						$erroriDest .= "<li>Il numero civico deve contenere solo numeri</li>";
					}
					if (!checkSoloNumeriEDIm($tel))
					{
						$erroriDest .= "<li>Non hai inserito un numero telefonico valido</li>";
					}
					if (strlen($erroriDest) == 0)
					{
						$successoDest = '<p class="successo">Informazioni di spedizione valide!</p>';
					}
					else
					{
						$erroriDest = '<ul class="errore">' . $erroriDest . '</ul>';
					}
				}

				#Seconso fieldset;
				if (isset($_POST['carta_credito']) && $_POST['carta_credito'] != 'Carta di credito')
				{
					$successoCarta = '<p class="successo">Informazioni di pagamento valide!</p>';
				}
				else
				{
					#Controllo la carta di credito;
					$intestatario = htmlentities(trim($_POST['intestatario_carta']));
					$num_carta = htmlentities(trim($_POST['num_carta']));
					$mese_scad = $_POST['mese_scad'];
					$anno_scad = $_POST['anno_scad'];
					$cvv = htmlentities(trim($_POST['cvv_carta']));

					if (!checkNomeCognome($intestatario))
					{
						$erroriCarta .= '<li>L\'intestatario deve contenere solo lettere ed essere lungo almeno due caratteri</li>';
					}
					if (!checkSoloNumerieDim($num_carta))
					{
						$erroriCarta .= '<li>Non hai inserito un numero di carta corretto</li>';
					}
					if ($mese_scadenza == 'Mese')
					{
						$erroriCarta .= '<li>Seleziona il mese di scadenza</li>';
					}
					if ($anno_scadenza == 'Anno')
					{
						$erroriCarta .= '<li>Seleziona l\'anno di scadenza</li>';
					}
					if (!checkSoloNumeri($cvv))
					{
						$erroriCarta .= '<li>Il CVV deve essere composto da tre cifre</li>';
					}
					if (strlen($erroriCarta) == 0)
					{
						$successoCarta = '<p class="successo">Informazioni di pagamento valide!</p>';
					}
					else
					{
						$erroriCarta = '<ul class="errore">' . $erroriCarta . '</ul>';
					}
				}
			}

			if (($erroriCarta == "") && ($erroriDest == "")) #Reindirizzo alla pagina di successo;
			{
				if (isset($_POST['tipoConsegna']) && ($_POST['tipoConsegna'] == 'domicilio'))
				{
					if (!$db->addSpedizione($user, $nome_cognome, $via, $civico, $cap, $tel))
					{
						header('location: errore500.php');
					}
					$nome_cognome = "";
					$via = "";
					$civico = "";
					$cap = "";
					$tel = "";
					$intestatario = "";
					$num_carta = "";
					$mese_scad = "";
					$anno_scad = "";
					$cvv = "";
					/*
					if (!$db->addOrdine())
					{
						header('location: errore500.php');
					}

					header('location: successo.php');
					*/
				}
				else if (isset($_POST['tipoConsegna']) && ($_POST['tipoConsegna'] == 'asporto'))
				{
					/*
					if (!$db->addOrdine())
					{
						header('location: errore500.php');
					}

					header('location: successo.php');
					*/
				}
			}
			else #Stampo la pagina con gli eventuali messaggi;
			{
				$paginaHTML = file_get_contents('html/pagamento.html');
				$menu = getmenu();
			    $paginaHTML = str_replace('<menu />', $menu, $paginaHTML);

				#Primo fieldset;
				$paginaHTML = str_replace('<erroreDestinazione />', $erroriDest, $paginaHTML);
				$paginaHTML = str_replace('<successoDestinazione />', $successoDest, $paginaHTML);

				$indirizziUtente = "";
				if ($db->openDBConnection())
				{
                 	$queryResult = $db->getDestinazioni($user);
             	} 
				else
				{
                	header('location: errore500.html');
             	}
				while ($row = mysqli_fetch_assoc($queryResult))
				{
					$indirizziUtente .= "<option value=\"$row[id_destinazione]\">$row[via], $row[numero_civico]</option>";
				}
				$paginaHTML = str_replace('<indirizziUtente />', $indirizziUtente, $paginaHTML);

				$formDest = "
				<label for=\"nome_cognome\">Nome e Cognome: </label>
				<input type=\"text\" name=\"nome_cognome\" id=\"nome_cognome\" placeholder=\"Mario Rossi\" value=\"$nome_cognome\" />
				<label for=\"via\">Via: </label>
				<input type=\"text\" id=\"via\" name=\"via\" placeholder=\"Inserire via\" value=\"$via\" />
				<label for=\"civico\">Numero civico: </label>
				<input type=\"text\" id=\"civico\" name=\"civico\" placeholder=\"Inserire numero civico\" value=\"$civico\" />
				<label for=\"cap\"><abbr title=\"Codice di Avviamento Postale\">CAP</abbr>: </label>
				<input type=\"text\" id=\"cap\" name=\"cap\" placeholder=\"Inserire CAP\" value=\"$cap\" />
				<label for=\"comune\">Comune: </label>
				<input type=\"text\" id=\"comune\" name=\"comune\" value=\"Padova\" disabled=\"disabled\"/>
				<label for=\"provincia\">Provincia: </label>
				<input type=\"text\" id=\"provincia\" name=\"provincia\" value=\"Padova\" disabled=\"disabled\"/>
				<label for=\"stato\">Stato: </label>
				<input type=\"text\" id=\"stato\" name=\"stato\" value=\"Italia\" disabled=\"disabled\"/>
				<label for=\"tel\">Numero di telefono: </label>
				<input type=\"text\" id=\"tel\" name=\"tel\"  value=\"$tel\" />
				";
				$paginaHTML = str_replace('<formDestinazione />', $formDest, $paginaHTML);

				#Secondo fieldset;
				$paginaHTML = str_replace('<erroreCarta />', $erroriCarta, $paginaHTML);
				$paginaHTML = str_replace('<successoCarta />', $successoCarta, $paginaHTML);

				$cartaUtente = null;
				if ($db->openDBConnection())
				{
                 	$cartaUtente = $db->getCartaDiCredito($user);
             	} 
				else
				{
                	header('location: errore500.html');
             	}
				if ($cartaUtente != null)
				{
					$cartaUtente = "<option>$cartaUtente</option>";
				}
				$paginaHTML = str_replace('<cartaUtente />', $cartaUtente, $paginaHTML);

				$years = "";
				$annoCorrente = date("Y");
				for ($i = 0; $i<20; $i++ )
				{
                    $years .= '<option value="' . ($annoCorrente+$i) . '">' . ($annoCorrente+$i) . '</option>';
                }
				$formCarta = "
				<div>
					<label for=\"intestatario_carta\">Intestatario carta: </label>
					<input type=\"text\" id=\"intestatario\" name=\"intestatario_carta\" value=\"$intestatario\" />
				</div>
				<div>
					<label for=\"numero_carta\">Numero carta: </label>
					<input type=\"text\" id=\"numero_carta\" name=\"numero_carta\" maxlength=\"16\" value=\"$num_carta\" />
				</div>
				<select name=\"mese_scadenza\" class=\"selezione_small\">
					<option>Mese</option>
					<option value=\"01\">Gennaio</option>
					<option value=\"02\">Febbraio</option>
					<option value=\"03\">Marzo</option>
					<option value=\"04\">Aprile</option>
					<option value=\"05\">Maggio</option>
					<option value=\"06\">Giugno</option>
					<option value=\"07\">Luglio</option>
					<option value=\"08\">Agosto</option>
					<option value=\"09\">Settembre</option>
					<option value=\"10\">Ottobre</option>
					<option value=\"11\">Novembre</option>
					<option value=\"12\">Dicembre</option>
				</select>
				<select name=\"anno_scad\" class=\"selezione_small\">
					<option>Anno</option>
					$years
				</select>
				<label for=\"cvv_carta\" lang=\"en\"><abbr title=\"Card Verification Value\">CVV</abbr>: </label>
				<input type=\"text\" id=\"cvv_carta\" name=\"cvv_carta\" maxlength=\"3\" value=\"$cvv\" />
				";
				$paginaHTML = str_replace('<formCarta />', $formCarta, $paginaHTML);

				echo $paginaHTML;
			}
		}
		else
		{
			header('location: errore500.php');
		}
	}
	else
	{
		header('location: login.php');
	}

?>
