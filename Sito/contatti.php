<?php

require_once("php/dbaccess.php");

session_start();

$paginaHTML = file_get_contents('html/contatti.html');

$menu = getMenu();

echo str_replace('<menu />', $menu, $paginaHTML);


?>