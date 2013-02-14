<?php
$GLOBALS['egw_info'] = array(
        'flags' => array(
                'currentapp' => 'bcalendar',
                'noheader'   => True
        ),
);
date_default_timezone_set('Europe/Warsaw');
require_once '../../DatabaseConnection.php';
$result = SendQuery("select `egw_addressbook`.`n_fn`, `egw_addressbook`.`account_id`, `egw_addressbook`.`contact_id` from `egw_addressbook` inner join `egw_accounts` on `egw_addressbook`.`account_id` = `egw_accounts`.`account_id` where `egw_accounts`.`account_type` = 'u' and `egw_accounts`.`account_primary_group` = -329 and `egw_addressbook`.`n_fn` like '%".  EscapeSpecialCharacters($_GET['search'])
                    ."%' order by `egw_addressbook`.`n_family`, `egw_addressbook`.`n_given`, `egw_addressbook`.`n_middle`"); //-329
while ($row = GetNextRow($result))
{
     echo '<option value="'.$row["contact_id"].'α'.$row["account_id"].'α'.$row["n_fn"].'">'.$row["n_fn"] . '</option>';
}
?>
