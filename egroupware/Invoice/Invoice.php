<?php
require_once('../SmartyConfig.php');
require_once('../DatabaseConnection.php');
$name = strtok($_GET['dentist'],'α');
$smarty->assign('dentist', $name);
$id = intval(strtok('α'));
$result = SendQuery("select `egw_cal_extra`.`cal_extra_value`, `egw_cal_extra`.`cal_extra_name` from `egw_cal_extra` where (`egw_cal_extra`.`cal_extra_name` = 'suma_na_wizycie' or `egw_cal_extra`.`cal_extra_name` = 'koszty_łącznie') and (select `egw_cal`.`cal_owner` from `egw_cal` where `egw_cal_extra`.`cal_id` = `egw_cal`.`cal_id`) = ".$id." and ((select `egw_cal_dates`.`cal_end` from `egw_cal_dates` where `egw_cal_extra`.`cal_id` = `egw_cal_dates`.`cal_id`) between ".strtotime($_GET['date-pick']. '00:00'). ' and '.strtotime($_GET['date-pick']. '23:59'). ')');
$netto = 0;
while($row = GetNextRow($result))
{
    switch($row['cal_extra_name'])
    {
        case 'suma_na_wizycie':
            $netto += $row['cal_extra_value'];
            break;
        case 'koszty_łącznie':
            $netto -= $row['cal_extra_value'];
    }
}
CloseConnection();
$netto = $netto*0.01*$_GET['percent'];
$VAT = $netto*0.01*$_GET['vat'];
$smarty->assign('DentistTable', array($name, $_GET['percent'].'%', $_GET['date-pick'].' r.', round($netto, 2).' zł', $_GET['vat'].'%',
                round($VAT, 2).' zł', round($netto - $VAT, 2). ' zł'));
$smarty->display('Invoice.tpl');
?>
