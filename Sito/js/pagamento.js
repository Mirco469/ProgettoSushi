function disableInputDest()
{
    document.getElementById("nome_cognome").disabled = true;
    document.getElementById("via").disabled = true;
    document.getElementById("civico").disabled = true;
    document.getElementById("cap").disabled = true;
    document.getElementById("tel").disabled = true;
}

function enableInputDest()
{
    document.getElementById("nome_cognome").disabled = false;
    document.getElementById("via").disabled = false;
    document.getElementById("civico").disabled = false;
    document.getElementById("cap").disabled = false;
    document.getElementById("tel").disabled = false;
}

function changeStateDest()
{
    var sceltaIndirizzo = document.getElementById("destinazione");
    var indirizzo_val = sceltaIndirizzo.options[sceltaIndirizzo.selectedIndex].text;
    if (indirizzo_val == "Indirizzo")
    {
         enableInputDest();
    }
    else
    {
        disableInputDest();
    }
}

function disableInputCarta()
{
    document.getElementById("intestatario_carta").disabled = true;
    document.getElementById("num_carta").disabled = true;
    document.getElementsByName("mese_scad")[0].disabled = true;
    document.getElementsByName("anno_scad")[0].disabled = true;
    document.getElementById("cvv_carta").disabled = true;
}

function enableInputCarta()
{
    document.getElementById("intestatario_carta").disabled = false;
    document.getElementById("num_carta").disabled = false;
    document.getElementsByName("mese_scad")[0].disabled = false;
    document.getElementsByName("anno_scad")[0].disabled = false;
    document.getElementById("cvv_carta").disabled = false;
}

function changeStateCarta()
{
    var sceltaCarta = document.getElementById("carta_credito");
    var carta_val = sceltaCarta.options[sceltaCarta.selectedIndex].text;
    if (carta_val == "Carta di credito")
    {
        enableInputCarta();
    }
    else
    {
        disableInputCarta();
    }
}