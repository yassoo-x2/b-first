<?php
include 'admin/setting.php';

$tpl = 'includes/templates/' ; //chortcut tampletes
$tplad = 'admin/includes/templates/' ; //chortcut tampletes
$func = 'includes/function/' ;
$css = 'admin/design/css/';   //chortcut css
$js = 'admin/design/js/';
$cssme ='design/css/';
$jsme = 'design/js/';

//important folder
include $func . 'functions.php';
include  $tpl . 'header.php';
include  $tplad . 'API.php';

if(!isset($nonavbar)) {include $tpl . 'navbar.php';}




?>


