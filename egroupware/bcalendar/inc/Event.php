<?php
$GLOBALS['egw_info'] = array(
        'flags' => array(
                'currentapp' => 'bcalendar',
                'noheader'   => True
        ),
);
date_default_timezone_set('Europe/Warsaw');
require_once '../../DatabaseConnection.php';
require_once '../../SmartyConfig.php';
$smarty->assign('id', $_GET['cal_id']);
$smarty->display('../templates/Event.tpl');
?>
