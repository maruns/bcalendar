<?php
$GLOBALS['egw_info'] = array(
        'flags' => array(
                'currentapp' => 'Invoice',
                'noheader'   => True
        ),
);
require_once('../DatabaseConnection.php');
require_once('../SmartyConfig.php');
$name = strtok($_GET['dentist'],'α');
$smarty->assign('dentist', $name);
$id = intval(strtok('α'));
date_default_timezone_set('Europe/Warsaw');
$result = SendQuery("SELECT DISTINCT `contact_name`, `contact_value` FROM `egw_addressbook_extra` WHERE `contact_name` = 'NIP' and `contact_id` = ".$id);
while($row = GetNextRow($result))
{
    $smarty->assign('NIP', $row['NIP']);
}
$result = SendQuery("SELECT DISTINCT `org_name`,`adr_one_street`,`adr_one_street2`,`adr_one_postalcode`,`adr_one_locality`,`tel_prefer` , `tel_work` , `tel_cell` , `tel_assistent` , `tel_car` , `tel_home` , `tel_cell_private` , `tel_other` FROM `egw_addressbook` WHERE `contact_id` = ".$id);
while($row = GetNextRow($result))
{
    $smarty->assign('company', $row['org_name']);
    $smarty->assign('street', $row['adr_one_street']);
    $smarty->assign('PostalPlace', $row['adr_one_street2']);
    $smarty->assign('PostalCode', $row['adr_one_postalcode']);
    $smarty->assign('place', $row['adr_one_locality']);
    $smarty->assign('phone', $row[$row['tel_prefer']]);
}
$result = SendQuery("select `egw_cal_extra`.`cal_extra_value`, `egw_cal_extra`.`cal_extra_name`, (select `egw_cal_dates`.`cal_end` from `egw_cal_dates` where `egw_cal_extra`.`cal_id` = `egw_cal_dates`.`cal_id`) as `date` from `egw_cal_extra` where (`egw_cal_extra`.`cal_extra_name` = 'suma_na_wizycie' or `egw_cal_extra`.`cal_extra_name` = 'koszty_łącznie') and (select `egw_cal`.`cal_owner` from `egw_cal` where `egw_cal_extra`.`cal_id` = `egw_cal`.`cal_id`) = ".$id." and ((select `egw_cal_dates`.`cal_end` from `egw_cal_dates` where `egw_cal_extra`.`cal_id` = `egw_cal_dates`.`cal_id`) between ".strtotime($_GET['from']. '00:00'). ' and '.strtotime($_GET['to']. '23:59'). ') order by `date`');
$netto = 0;
$dn = array();
while($row = GetNextRow($result))
{
    switch($row['cal_extra_name'])
    {
        case 'suma_na_wizycie':
            $netto += $row['cal_extra_value'];
            $dn[$row['date']] += $row['cal_extra_value'];
            break;
        case 'koszty_łącznie':
            $netto -= $row['cal_extra_value'];
            $dn[$row['date']] += $row['cal_extra_value'];
    }
}
CloseConnection();
$percent = $_GET['percent'].'%';
$VAT = $netto*0.01*$_GET['vat'];
$TVAT = round($VAT, 2).' zł';
foreach($dn as $key => $value)
{
    $DentistTable[] = date('Ymd', $key) . ' r.';
    $DentistTable[] = round($value, 2).' zł';
    $DentistTable[] = $_GET['vat'].'%';
    $DentistTable[] = $TVAT;
    $DentistTable[] = round($netto - $VAT, 2). ' zł';
}
$netto = $netto*0.01*$_GET['percent'];

$smarty->assign('DentistTable', $DentistTable);
$smarty->assign('SumTable', array($_GET['date-pick'].' r.', $_GET['percent'].'%', round($netto, 2).' zł', $_GET['vat'].'%',
                round($VAT, 2).' zł', round($netto - $VAT, 2). ' zł'));
$smarty->display('Invoice.tpl');
?>
