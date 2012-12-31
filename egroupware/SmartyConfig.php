<?php 
ini_set('display_errors', 1); //pokazywanie błędów PHP
require('Smarty/libs/Smarty.class.php');
define('SMARTY_DIR', 'Smarty/libs/');
$smarty = new Smarty();
$EGroupwareDirectory = dirname(__FILE__);
$smarty->compile_dir = $EGroupwareDirectory.'/Smarty/templates_c/';
$smarty->cache_dir = $EGroupwareDirectory.'/Smarty/cache/';
setlocale(LC_ALL, 'pl_PL', 'pl', 'Polish_Poland.28592');
?>
