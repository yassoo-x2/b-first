<?php
include 'setting.php';

$tpl = 'includes/templates/' ; //chortcut tampletes
$func = 'includes/function/' ;
$css = 'design/css/';   //chortcut css
$js = 'design/js/';

//important folder
include $func . 'functions.php';
include  $tpl . 'header.php';
include  $tpl . 'API.php';

if(!isset($nonavbar)) {include $tpl . 'navbar.php';}


?>


