<?php
require_once('../../SmartyConfig.php');
require_once('../../DatabaseConnection.php');
$result = SendQuery("SELECT `et_data` FROM `egw_etemplate` WHERE `et_name` = 'etemplate.link_widget.entry'");
$row = GetNextRow($result);echo "<pre>";
//print_r(unserialize($row['et_data']));
echo "</pre>";
echo "<pre>";
$c = unserialize($row['et_data']);
$c[0][1][3]['onkeypress'] = 'if (event.keyCode==13){this.form.submit();}';
print_r($c);
echo mysql_escape_string(serialize($c));
echo "</pre>";



$result = SendQuery("select `egw_addressbook`.`n_fn`, `egw_addressbook`.`account_id` from `egw_addressbook` inner join `egw_accounts` on `egw_addressbook`.`account_id` = `egw_accounts`.`account_id` where `egw_accounts`.`account_type` = 'u' and `egw_accounts`.`account_primary_group` = -329 order by `egw_addressbook`.`n_family`, `egw_addressbook`.`n_given`, `egw_addressbook`.`n_middle`");
while ($row = GetNextRow($result))
{
    if ($_GET['dentist'] == $row['account_id'])
    {
        $smarty->assign('name',$row['n_fn']);
        $dentists[] = '<a id="current" href="?dentist='.$row['account_id'].'"><strong>'.$row['n_fn'].'</strong></a>';
    }
    else
    {
        $dentists[] = '<a href="?dentist='.$row['account_id'].'">'.$row['n_fn'].'</a>';
    }
}
$result = SendQuery("select `ID`, `Day`, `Start`, `End` from `PeriodsOfNormalWorkingTime` where `account_id` = ".$_GET['dentist'].
                    " order by Day, Start");
$smarty->assign('dentists', $dentists);
$smarty->display('WorkingTime.tpl');
?>
