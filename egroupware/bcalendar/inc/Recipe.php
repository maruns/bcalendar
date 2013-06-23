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
$smarty->assign('date', $_GET['date']);
$result = SendQuery("SELECT `adr_one_street`, `adr_one_street2`, `adr_one_postalcode`, `adr_one_locality` FROM `egw_addressbook` WHERE `contact_id` = " . 
                    intval($_GET['patient']));
while ($row = GetNextRow($result))
{
    $smarty->assign('PatientStreet', $row['adr_one_street']);
    $smarty->assign('PatientPlace', $row['adr_one_street2']);
    $smarty->assign('PatientPostalCode', $row['adr_one_postalcode']);
    $smarty->assign('PatientPostalPlace', $row['adr_one_locality']);
}
$result = SendQuery("SELECT `contact_value` FROM `egw_addressbook_extra` WHERE `contact_name` = 'PESEL' AND `contact_id` = " . 
                    intval($_GET['patient']));
while ($row = GetNextRow($result))
{
    $smarty->assign('PESEL', $row['contact_value']);
}
$result = SendQuery("SELECT DISTINCT Visits.Recipe FROM Visits WHERE Visits.cal_id = " . intval($_GET['id']));
while ($row = GetNextRow($result))
{
    $Recipe = explode("\n", $row['Recipe']); 
    $smarty->assign('Recipe', $Recipe);
    $smarty->assign('re', count($Recipe));
}
$result = SendQuery("select `egw_addressbook`.`n_fn`, `org_name`,`adr_one_street`,`adr_one_street2`,`adr_one_postalcode`,`adr_one_locality`, `tel_prefer`, `tel_cell_private`, `tel_cell`, `tel_work`, `tel_assistent`, `tel_home`, `tel_car`, `tel_other`, `contact_value` FROM `egw_addressbook` LEFT JOIN `egw_addressbook_extra` ON (`egw_addressbook`.contact_id = `egw_addressbook_extra`.contact_id AND `contact_name` = 'NIP') WHERE `egw_addressbook`.`account_id` = " . intval($_GET['owner']));
while ($row = GetNextRow($result))
{
    $smarty->assign('owner', $row["n_fn"]);
    $smarty->assign('company', $row['org_name']);
    $smarty->assign('street', $row['adr_one_street']);
    $smarty->assign('place', $row['adr_one_street2']);
    $smarty->assign('PostalCode', $row['adr_one_postalcode']);
    $smarty->assign('PostalPlace', $row['adr_one_locality']);
    $smarty->assign('NIP', $row['contact_value']);
    $smarty->assign('phone', $row[$row['tel_prefer']]);
}
$smarty->display('../templates/Recipe.tpl');
?>
