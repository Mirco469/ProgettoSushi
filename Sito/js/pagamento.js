function disableInputDest()
{
    element = document.getElementById("nome_cognome");
    element.setAttribute("disabled", "disabled");
    element = document.getElementById("via");
    element.setAttribute("disabled", "disabled");
    element = document.getElementById("civico");
    element.setAttribute("disabled", "disabled");
    element = document.getElementById("cap");
    element.setAttribute("disabled", "disabled");
    element = document.getElementById("tel")
    element.setAttribute("disabled", "disabled");
    
}

function enableInputDest()
{
    var element;
    element = document.getElementById("nome_cognome");
    element.removeAttribute("disabled");
    element = document.getElementById("via");
    element.removeAttribute("disabled");
    element = document.getElementById("civico");
    element.removeAttribute("disabled");
    element = document.getElementById("cap");
    element.removeAttribute("disabled");
    element = document.getElementById("tel");
    element.removeAttribute("disabled");
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
    var element;
    element = document.getElementById("intestatario_carta");
    element.removeAttribute("disabled");
    element = document.getElementById("num_carta");
    element.removeAttribute("disabled");
    element = document.getElementsByName("mese_scad")[0];
    element.setAttribute("disabled", "disabled");
    element = document.getElementsByName("anno_scad")[0];
    element.setAttribute("disabled", "disabled");
    element = document.getElementById("cvv_carta");
    element.setAttribute("disabled", "disabled");
}

function enableInputCarta()
{
    var element;
    element = document.getElementById("intestatario_carta");
    element.removeAttribute("disabled");
    element = document.getElementById("num_carta");
    element.removeAttribute("disabled");
    element = document.getElementsByName("mese_scad")[0];
    element.removeAttribute("disabled");
    element = document.getElementsByName("anno_scad")[0];
    element.removeAttribute("disabled");
    element = document.getElementById("cvv_carta");
    element.removeAttribute("disabled");
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
