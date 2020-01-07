<?php
	require_once("../php/dbaccess.php");

    session_start();

    // Se non si ha già una sessione attiva si viene reindirizzati verso il login
    if(isset($_SESSION["username"]) && isset($_SESSION["autorizzazione"]))
    {
        //Se non ho l'autorizzazione di admin mando alla pagina 403
        if(strcmp($_SESSION["autorizzazione"],"Admin") == 0)
        {

            $oggettoConnessione =  new DBAccess();
            if($oggettoConnessione->openDBConnection())
            {
                $prodotto = $oggettoConnessione->getInfoProdotto($_GET['nome']);
                if(isset($prodotto))
                {
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
                    $option = preg_replace('/'.$prodotto["categoria"].'"/', $prodotto["categoria"].'" selected', $option, 1);

                    $formModifica = "
                        <label for=\"prodotto\">Nome Prodotto:</label>
					    <input type=\"text\" id=\"prodotto\" name=\"prodotto\" value=\"".$_GET['nome']."\"/>
					    <label for=\"categoria\">Categoria:</label>
                        <select id=\"categoria\" name=\"categoria\">
                            ".$option."
                        </select>
                        <label for=\"porzione\">Numero pezzi:</label>
                        <input type=\"text\" id=\"porzione\" name=\"porzione\" value=\"".$prodotto["pezzi"]."\"/>
                        <label for=\"prezzo\">Prezzo:</label>
                        <input type=\"text\" id=\"prezzo\" name=\"prezzo\" value=\"".$prodotto["prezzo"]."\"/>
                        <label for=\"descrizione\">Descrizione:</label>
                        <textarea id=\"descrizione\" name=\"descrizione\" rows=\"4\" cols=\"35\">".$prodotto["descrizione"]."</textarea>";


                    $paginaHTML = file_get_contents('html/modifica_prodotto.html');
                    $paginaHTML = str_replace('<formModifica />', $formModifica, $paginaHTML);
                    echo $paginaHTML;
                }
                else
                {
                    header("Location: /errore500.php"); /*CONTROLLARE SE LA PAGINA E' GIUSTA*/
                }
            }
            else
            {
                header("Location: /errore500.php"); /*CONTROLLARE SE LA PAGINA E' GIUSTA*/
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