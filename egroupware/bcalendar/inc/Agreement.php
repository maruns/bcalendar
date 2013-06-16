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
$smarty->assign('pn', $_GET['pn']);
$result = SendQuery("SELECT `contact_value` FROM `egw_addressbook_extra` WHERE `contact_name` = 'PESEL' AND `contact_id` = " . 
                    intval($_GET['patient']));
while ($row = GetNextRow($result))
{
    $smarty->assign('PESEL', $row['contact_value']);
}
$result = SendQuery("SELECT DISTINCT Visits.Agreement FROM Visits WHERE Visits.cal_id = " . intval($_GET['id']));
while ($row = GetNextRow($result))
{
     $smarty->assign('Agreement', $row['Agreement']);
}
$smarty->display('../templates/Agreement.tpl');
?>
