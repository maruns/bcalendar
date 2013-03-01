<?php 
ini_set('display_errors', 1); //pokazywanie błędów PHP
require('Smarty/libs/Smarty.class.php');
define('SMARTY_DIR', 'Smarty/libs/');
$smarty = new Smarty();
$SmartyTemplateDirectory = dirname(__FILE__).'/Smarty/templates_c';
if (is_writable($SmartyTemplateDirectory))
{
    $smarty->compile_dir = $SmartyTemplateDirectory . '/';
}
else
{
    $smarty->compile_dir = '/tmp/';
}
setlocale(LC_ALL, 'pl_PL', 'pl', 'Polish_Poland.28592');
?>
