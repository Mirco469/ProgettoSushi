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

			if (isset($_POST['paga']))
			{
				# Controllo primo fieldset
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
					if (!checkMaxLen($nome_cognome, 40))
					{
						$erroriDest .= '<li>Il campo nome e cognome non deve contenere più di 40 caratteri</li>';
					}
					if (!checkAlfanumericoESpazi($via))
					{
						$erroriDest .= "<li>La via non deve contenere caratteri speciali</li>";
					}
					if (!checkMaxLen($via, 20))
					{
						$erroriDest .= '<li>Il nome dell\'indirizzo non deve contenere più di 15 caratteri</li>';
					}
					if (!checkCivico($civico))
					{
						$erroriDest .= "<li>Il numero civico deve essere nel formato corretto (e.g. 4, 4b, 4/b, 4-b)</li>";
					}
					if (!checkMaxLen($civico, 10))
					{
						$erroriDest .= '<li>Il numero civico non deve contenere più di 10 caratteri</li>';
					}
					if (!checkCAP($cap))
					{
						$erroriDest .= "<li>Il CAP deve contenere solo numeri</li>";
					}
					if (!checkMaxLen($cap, 5))
					{
						$erroriDest .= '<li>Il CAP non deve contenere più di 5 caratteri</li>';
					}
					if (!checkSoloNumeriEDIm($tel))
					{
						$erroriDest .= "<li>Non hai inserito un numero telefonico valido</li>";
					}
					if (!checkMaxLen($tel, 15))
					{
						$erroriDest .= '<li>Il numero telefonico non deve contenere più di 15 caratteri</li>';
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

				# Controllo secondo fieldset
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
					if (!checkMaxLen($intestatario, 40))
					{
                        $erroriCarta .= '<li>Il campo intestatario non deve contenere più di 40 caratteri</li>';
                    }
					if (!checkSoloNumerieDim($num_carta))
					{
						$erroriCarta .= '<li>Non hai inserito un numero di carta corretto</li>';
					}
					if (!checkMaxLen($num_carta, 16))
					{
                        $erroriPaga .= '<li>Il numero della carta non deve contenere più di 16 caratteri</li>';
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

				# Informazioni corrette => Successo
				if (($erroriCarta == "") && ($erroriDest == ""))
				{
					if (($_POST['destinazione'] == 'Indirizzo') && (!$db->alreadyExistsDest($nome_cognome, $tel, $cap, $via, $civico, $user)) && (!$db->addSpedizione($user, $nome_cognome, $via, $civico, $cap, $tel)))
					{
						header('location: errore500.php');
					}

					$totale = totaleCarrello();
					$dataOrdine = date("Y-m-d H:i:s");
					$dataConsegna = date("Y-m-d H+1:i:s");

					$idDestinazione = "";
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
					if ($idDestinazione == "")
					{
						$idDestinazione = $maxId;
					}

					if (!$db->addOrdine($dataOrdine, $dataConsegna, $totale, $idDestinazione, $user))
					{
						header('location: errore500.php');
					}

					$idOrdine = 0;
					$queryResult = $db->getOrdini($user);
					while ($row = mysqli_fetch_assoc($queryResult))
					{
						if ($row['id_ordine'] > $idOrdine)
						{
							$idOrdine = $row['id_ordine'];
						}
					}
					foreach ($_SESSION['carrello'] AS $prodotto => $row)
					{
						$quantita = $row['quantita'];
						if (!$db->addContiene($idOrdine, $prodotto, $quantita))
						{
							header('location: errore500.php');
						}
					}

					header('location: successo.html');
				}
			}

			$paginaHTML = file_get_contents('html/pagamento.html');
			$paginaHTML = str_replace('<menu />', getMenu(), $paginaHTML);

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

			$cartaUtente = "";
			if ($db->openDBConnection())
			{
				$cartaUtente = $db->getCartaDiCredito($user);
				$cartaUtente = $cartaUtente['numero_carta'];
			}
			else
			{
				header('location: errore500.php');
			}
			if ($cartaUtente != "")
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
				<input type=\"text\" id=\"intestatario_carta\" name=\"intestatario_carta\" placeholder=\"Mario Rossi\" value=\"$intestatario\" />
			</p>
			<p>
				<label for=\"num_carta\">Numero carta: </label>
				<input type=\"text\" id=\"num_carta\" name=\"num_carta\" maxlength=\"16\" placeholder=\"Inserire numero\" value=\"$num_carta\" />
			</p>
			<p>
			<label for=\"mese_scad\">Mese di scadenza: </label>
			<select name=\"mese_scad\" class=\"selezione_small\">
				<option>Mese</option>
				$months
			</select>
			</p>
			<p>
			<label for=\"anno_scad\">Anno di scadenza: </label>
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
