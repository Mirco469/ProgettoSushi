<?php
	require_once("../php/dbaccess.php");

    session_start();

    // Se non si ha già una sessione attiva si viene reindirizzati verso il login
    if(isset($_SESSION["username"]) && isset($_SESSION["autorizzazione"]))
    {
        //Se non ho l'autorizzazione di admin mando alla pagina 403
        if(strcmp($_SESSION["autorizzazione"],"Admin") == 0)
        {
			$erroreModifica = "";
            //Campi per il form di aggiunta
            $nomeProdotto = "";
            $categoria = "";
            $numeroPezzi = "";
            $prezzo = "";
            $descrizione = "";
			

            $oggettoConnessione =  new DBAccess();
            if($oggettoConnessione->openDBConnection())
            {
				$prodotto = $oggettoConnessione->getInfoProdotto($_GET['nome']);
				if(isset($prodotto))
				{
					$nomeProdotto = $_GET['nome'];
					$categoria = $prodotto["categoria"];
					$numeroPezzi = $prodotto["pezzi"];
					$prezzo = $prodotto["prezzo"];
					$descrizione = $prodotto["descrizione"];
				}
				else
				{
					header("Location: errore500.html"); /*CONTROLLARE SE LA PAGINA E' GIUSTA*/
					exit;
				}
				
                if(isset($_POST['annulla']))
                {
                    header('location: aggiunta_prodotti.php');
                }
                else if(isset($_POST['elimina']))
                {
                    if($oggettoConnessione->deleteProdotto($_GET['nome']))
                    {
                        header('location: aggiunta_prodotti.php');
                        exit;
                    }
                    else
                    {
                        header("Location: errore500.html");
                    }
                }
                else if(isset($_POST['modifica']))
                {
                    $nomeProdotto = htmlentities(trim($_POST['prodotto']));
                    $categoria = htmlentities(trim($_POST['categoria']));
                    $numeroPezzi = htmlentities(trim($_POST['porzione']));
                    $prezzo = htmlentities(trim($_POST['prezzo']));
                    $descrizione = htmlentities(trim($_POST['descrizione']));

                    // Controllo gli input
                    if (!checkNumeroIntero($numeroPezzi))
                    {
                        $erroreModifica .= "<li>Il numero dei pezzi deve essere un numero intero</li>";
                    }
                    if (!checkPrezzo($prezzo))
                    {
                        $erroreModifica .= "<li>Il prezzo deve essere un numero decimale con al massimo 3 cifre prima del punto e 2 cifre dopo il punto</li>";
                    }

                    //Controllo se non ho riscontrato errori
                    if ($erroreModifica == "")
                    {
                        //Modifico il prodotto e reindirizzo
                        if($oggettoConnessione->modifyProdotto($nomeProdotto,$categoria,$numeroPezzi,$prezzo,$descrizione))
                        {
                            header('location: aggiunta_prodotti.php');
                            exit;
                        }
                        else
                        {
                            header("Location: errore500.html");
                        }
                    }
                    else
                    {
                        $erroreModifica = "<ul class='errore'>" . $erroreModifica . "</ul>";
                    }
                }

				$option = '<option value="Antipasti">Antipasti</option>
						<option value="Primi Piatti">Primi Piatti</option>
						<option value="Teppanyako e Tempure" lang="ja">Teppanyako e tempure</option>
						<option value="Uramaki" lang="ja">Uramaki</option>
						<option value="Nigiri ed Onigiri" lang="ja">Nigiri ed Onigiri</option>
						<option value="Gunkan" lang="ja">Gunkan</option>
						<option value="Temaki" lang="ja">Temaki</option>
						<option value="Hosomaki" lang="ja">Hosomaki</option>
						<option value="Sashimi" lang="ja">Sashimi</option>
						<option value="Dessert" lang="fr">Dessert</option>';
				$option = preg_replace('/'.$categoria.'"/', $categoria.'" selected="selected"', $option, 1);

				$formModifica = "
				    <p>
                        <label for=\"prodotto\">Nome Prodotto:</label>
                        <input type=\"text\" id=\"prodotto\" name=\"prodotto\" value=\"".$nomeProdotto."\" readonly=\"readonly\"/>
                    </p>
                    <p>
                        <label for=\"categoria\">Categoria:</label>
                        <select id=\"categoria\" name=\"categoria\">
                            ".$option."
                        </select>
                    </p>
                    <p>
                        <label for=\"porzione\">Numero pezzi:</label>
                        <input type=\"text\" id=\"porzione\" name=\"porzione\" value=\"".$numeroPezzi."\"/>
                    </p>
                    <p>
                        <label for=\"prezzo\">Prezzo:</label>
                        <input type=\"text\" id=\"prezzo\" name=\"prezzo\" value=\"".$prezzo."\"/>
                    </p>
                    <p>
                        <label for=\"descrizione\">Descrizione:</label>
                        <textarea id=\"descrizione\" name=\"descrizione\" rows=\"4\" cols=\"35\">".$descrizione."</textarea>
                    </p>";


				$paginaHTML = file_get_contents('html/modifica_prodotto.html');
				$paginaHTML = str_replace('<erroreModifica />', $erroreModifica, $paginaHTML);
				$paginaHTML = str_replace('<formModifica />', $formModifica, $paginaHTML);
                    echo $paginaHTML;
            }
            else
            {
                header("Location: errore500.html"); /*CONTROLLARE SE LA PAGINA E' GIUSTA*/
            }
        }
        else
        {
            header('location: ../errore403.php'); /* CONTROLLARE SE LA PAGINA E' GIUSTA */
        }
    }
    else
    {
        header('location: ../login.php');
    }
?>