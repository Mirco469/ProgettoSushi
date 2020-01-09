<?php

	require_once("php/dbaccess.php");

    session_start();
	#Login per testare la pagina;
	$_SESSION['username'] = "user"; #da togliere
	$_SESSION['password'] = "user"; #da togliere

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

			$paginaHTML = file_get_contents('html/pagamento.html');
			$menu = getmenu();
			$paginaHTML = str_replace('<menu />', $menu, $paginaHTML);
			$sceltaConsegna = "
			<input type=\"radio\" id=\"consegna_asporto\" name=\"tipoConsegna\" value=\"asporto\" onclick=\"disableInput()\" />
			<label for=\"consegna_asporto\">Asporto</label>
			<input type=\"radio\" id=\"consegna_domicilio\" name=\"tipoConsegna\" value=\"domicilio\" checked=\"checked\" onclick=\"enableInput()\" />
			<label for=\"consegna_domicilio\">Domicilio</label>
			";

			if (isset($_POST['paga']))
			{
				if (isset($_POST['tipoConsegna']) && ($_POST['tipoConsegna'] == 'domicilio'))
				{
					if (isset($_POST['destinazione']) && $_POST['destinazione'] != 'Indirizzo')
					{
						$successoDest = '<p class="successo">Informazioni di spedizione valide!</p>';
					}
					else
					{
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
							$erroriDest .= "<li>La via non deve contenere caratteri speciali</li>";
						}
						if (!checkSoloNumeri($civico))
						{
							$erroriDest .= "<li>Il numero civico deve contenere solo numeri</li>";
						}
						if (!checkCAP($cap))
						{
							$erroriDest .= "<li>Il CAP deve contenere solo numeri</li>";
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
				}
				else if (isset($_POST['tipoConsegna']) && ($_POST['tipoConsegna'] == 'asporto'))
				{
					$sceltaConsegna = "
					<input type=\"radio\" id=\"consegna_asporto\" name=\"tipoConsegna\" value=\"asporto\" checked=\"checked\" onclick=\"disableInput()\" />
					<label for=\"consegna_asporto\">Asporto</label>
					<input type=\"radio\" id=\"consegna_domicilio\" name=\"tipoConsegna\" value=\"domicilio\" onclick=\"enableInput()\" />
					<label for=\"consegna_domicilio\">Domicilio</label>
					";
				}

				if (isset($_POST['carta_credito']) && $_POST['carta_credito'] != 'Carta di credito')
				{
					$successoCarta = '<p class="successo">Informazioni di pagamento valide!</p>';
				}
				else
				{
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
					if ($mese_scad == 'Mese')
					{
						$erroriCarta .= '<li>Seleziona il mese di scadenza</li>';
					}
					if ($anno_scad == 'Anno')
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

				if (($erroriCarta == "") && ($erroriDest == ""))
				{
					$totale = totaleCarrello();
					$dataOrdine = date("Y-m-d H:i:s");

					if (isset($_POST['tipoConsegna']) && ($_POST['tipoConsegna'] == 'domicilio'))
					{
						if (($_POST['destinazione'] == 'Indirizzo') && (!$db->alreadyExistsDest($nome_cognome, $tel, $cap, $via, $civico, $user)) && (!$db->addSpedizione($user, $nome_cognome, $via, $civico, $cap, $tel)))
						{
							header('location: errore500.php');
						}

						$dataConsegna = date("Y-m-d H+1:i:s");
						$idDestinazione = "";
						if ($db->openDBConnection())
						{
							$maxId = 0;
							$queryResult = $db->getDestinazioni($user);
							while ($row = mysqli_fetch_assoc($queryResult))
							{
								if (isset($_POST['destinazione']) && $_POST['destinazione'] == $row['id_destinazione'])
								{
									$idDestinazione = $row['id_destinazione'];
								}
								else
								{
									if ($row['id_destinazione'] > $maxId)
									{
										$maxId = $row['id_destinazione'];
									}
								}
							}
							if ($maxId != 0)
							{
								$idDestinazione = $maxId;
							}
						}
						else
						{
							header('location: errore500.php');
						}

						if (!$db->addOrdine($dataOrdine, $dataConsegna, $totale, $idDestinazione, $user))
						{
							header('location: errore500.php');
						}
						header('location: successo.html');
					}
					else if (isset($_POST['tipoConsegna']) && ($_POST['tipoConsegna'] == 'asporto'))
					{
						$dataConsegna = null;
						$destinazione = null;

						if (!$db->addOrdine($dataOrdine, $dataConsegna, $totale, $destinazione, $user))
						{
							header('location: errore500.php');
						}
						header('location: successo.html');
					}
				}
			}

			$paginaHTML = str_replace('<sceltaConsegna />', $sceltaConsegna, $paginaHTML);

			$paginaHTML = str_replace('<erroreDestinazione />', $erroriDest, $paginaHTML);
			$paginaHTML = str_replace('<successoDestinazione />', $successoDest, $paginaHTML);

			$indirizziUtente = "";
			if ($db->openDBConnection())
			{
				$queryResult = $db->getDestinazioni($user);
				while ($row = mysqli_fetch_assoc($queryResult))
				{
					$indirizzo = "$row[id_destinazione]";
					if (isset($_POST['destinazione']) && $_POST['destinazione'] == $indirizzo)
					{
						$indirizziUtente .= "<option value=\"$row[id_destinazione]\" selected=\"selected\" >$row[via], $row[numero_civico]</option>";
					}
					else
					{
						$indirizziUtente .= "<option value=\"$row[id_destinazione]\">$row[via], $row[numero_civico]</option>";
					}
				}
			}
			else
			{
				header('location: errore500.php');
			}
			
			$paginaHTML = str_replace('<indirizziUtente />', $indirizziUtente, $paginaHTML);

			$formDest = "
			<p>
			<label for=\"nome_cognome\">Nome e Cognome: </label>
			<input type=\"text\" name=\"nome_cognome\" id=\"nome_cognome\" placeholder=\"Mario Rossi\" value=\"$nome_cognome\" />
			</p>
			<p>
			<label for=\"via\">Via: </label>
			<input type=\"text\" id=\"via\" name=\"via\" placeholder=\"Inserire via\" value=\"$via\" />
			</p>
			<p>
			<label for=\"civico\">Numero civico: </label>
			<input type=\"text\" id=\"civico\" name=\"civico\" placeholder=\"Inserire numero civico\" value=\"$civico\" />
			</p>
			<p>
			<label for=\"cap\"><abbr title=\"Codice di Avviamento Postale\">CAP</abbr>: </label>
			<input type=\"text\" id=\"cap\" name=\"cap\" placeholder=\"Inserire CAP\" value=\"$cap\" />
			</p>
			<p>
			<label for=\"comune\">Comune: </label>
			<input type=\"text\" id=\"comune\" name=\"comune\" value=\"Padova\" disabled=\"disabled\"/>
			</p>
			<p>
			<label for=\"provincia\">Provincia: </label>
			<input type=\"text\" id=\"provincia\" name=\"provincia\" value=\"Padova\" disabled=\"disabled\"/>
			</p>
			<p>
			<label for=\"stato\">Stato: </label>
			<input type=\"text\" id=\"stato\" name=\"stato\" value=\"Italia\" disabled=\"disabled\"/>
			</p>
			<p>
			<label for=\"tel\">Numero di telefono: </label>
			<input type=\"tel\" id=\"tel\" name=\"tel\" placeholder=\"Inserire recapito\" value=\"$tel\" />
			</p>
			";
			$paginaHTML = str_replace('<formDestinazione />', $formDest, $paginaHTML);

			$paginaHTML = str_replace('<erroreCarta />', $erroriCarta, $paginaHTML);
			$paginaHTML = str_replace('<successoCarta />', $successoCarta, $paginaHTML);

			$cartaUtente = null;
			if ($db->openDBConnection())
			{
				$cartaUtente = $db->getCartaDiCredito($user);
			}
			else
			{
				header('location: errore500.php');
			}
			if ($cartaUtente != null)
			{
				if (isset($_POST['carta_credito']) && $_POST['carta_credito'] == $cartaUtente)
				{
					$cartaUtente = "<option selected=\"selected\">$cartaUtente</option>";
				}
				else
				{
					$cartaUtente = "<option>$cartaUtente</option>";
				}
			}
			$paginaHTML = str_replace('<cartaUtente />', $cartaUtente, $paginaHTML);

			$months = "<p>";
			$mesi = array('Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre');
			for ($i = 0; $i<12; $i++)
			{
				if ($mese_scad == $i+1)
				{
					$months .= '<option value="' . ($i+1) . '" selected="selected">' . $mesi[$i] . '</option>';
				}
				else
				{
					$months .= '<option value="' . ($i+1) . '">' . $mesi[$i] . '</option>';
				}
			}
			$months .= "</p>";

			$years = "<p>";
			$annoCorrente = date("Y");
			for ($i = 0; $i<20; $i++)
			{
				if ($anno_scad == ($annoCorrente+$i))
				{
					$years .= '<option value="' . ($annoCorrente+$i) . '" selected="selected">' . ($annoCorrente+$i) . '</option>';
				}
				else
				{
					$years .= '<option value="' . ($annoCorrente+$i) . '">' . ($annoCorrente+$i) . '</option>';
				}
			}
			$years .= "</p>";

			$formCarta = "
			<p>
				<label for=\"intestatario_carta\">Intestatario carta: </label>
				<input type=\"text\" id=\"intestatario_carta\" name=\"intestatario_carta\" value=\"$intestatario\" />
			</p>
			<p>
				<label for=\"num_carta\">Numero carta: </label>
				<input type=\"text\" id=\"num_carta\" name=\"num_carta\" maxlength=\"16\" value=\"$num_carta\" />
			</p>
			<p>
			<select name=\"mese_scad\" class=\"selezione_small\">
				<option>Mese</option>
				$months
			</select>
			</p>
			<p>
			<select name=\"anno_scad\" class=\"selezione_small\">
				<option>Anno</option>
				$years
			</select>
			</p>
			<p>
			<label for=\"cvv_carta\" lang=\"en\"><abbr title=\"Card Verification Value\">CVV</abbr>: </label>
			<input type=\"text\" id=\"cvv_carta\" name=\"cvv_carta\" maxlength=\"3\" value=\"$cvv\" />
			</p>
			";
			$paginaHTML = str_replace('<formCarta />', $formCarta, $paginaHTML);

			echo $paginaHTML;
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
