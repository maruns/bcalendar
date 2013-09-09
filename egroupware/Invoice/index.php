<?php
$GLOBALS['egw_info'] = array(
        'flags' => array(
                'currentapp' => 'Invoice',
                'noheader'   => True
        ),
);
require_once('../DatabaseConnection.php');
require_once('../SmartyConfig.php');
include_once('/var/lib/egroupware/Invoice.php');
date_default_timezone_set('Europe/Warsaw');
$result = SendQuery("select `egw_addressbook`.`n_fn`, `egw_addressbook`.`account_id`, `egw_addressbook`.`contact_id` from `egw_addressbook` inner join `egw_accounts` on `egw_addressbook`.`account_id` = `egw_accounts`.`account_id` where `egw_accounts`.`account_type` = 'u' and `egw_accounts`.`account_primary_group` = -329 order by `egw_addressbook`.`n_family`, `egw_addressbook`.`n_given`, `egw_addressbook`.`n_middle`"); //-329
while ($row = GetNextRow($result))
{
    $sv = $row["contact_id"].'α'.$row["account_id"].'α'.$row["n_fn"];
    $dentists[$sv] = $row["n_fn"];
    if ($row["account_id"] == $GLOBALS['egw_info']['user']['account_id'])
    {
        $lu = $sv;
    }
}
CloseConnection();
$smarty->assign('d_VAT',d_VAT);
$smarty->assign('d_FRANCZYZA',d_FRANCZYZA);
$smarty->assign('dentists',$dentists);
$smarty->assign('lu', $lu);
$smarty->display('templates/InvoiceWindow.tpl');
?>
