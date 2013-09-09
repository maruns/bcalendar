<?php
$GLOBALS['egw_info'] = array(
        'flags' => array(
                'currentapp' => 'bcalendar',
                'noheader'   => True
        ),
);
date_default_timezone_set('Europe/Warsaw');
require_once '../DatabaseConnection.php';
echo '<option value="-1">Wszyscy</option>';
if ($_GET['aia'] === 'false')
{
    $Expiration = " and `egw_accounts`.`account_status` = 'A' and (`egw_accounts`.`account_expires` = -1 or `egw_accounts`.`account_expires` > ".intval($_SERVER['REQUEST_TIME']) . ')';
}
$result = SendQuery("select `egw_addressbook`.`n_fn`, `egw_addressbook`.`account_id`, `egw_addressbook`.`contact_id` from `egw_addressbook` inner join `egw_accounts` on `egw_addressbook`.`account_id` = `egw_accounts`.`account_id` where `egw_accounts`.`account_type` = 'u' and `egw_addressbook`.`n_fn` like '%".  EscapeSpecialCharacters($_GET['search'])
                    ."%'". $Expiration . 
        " order by `egw_addressbook`.`n_family`, `egw_addressbook`.`n_given`, `egw_addressbook`.`n_middle`");
while ($row = GetNextRow($result))
{
     echo '<option value="'.$row["account_id"].'">'.$row["n_fn"] . '</option>';
}
?>
