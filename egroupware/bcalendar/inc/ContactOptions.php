<?php
$GLOBALS['egw_info'] = array(
        'flags' => array(
                'currentapp' => 'bcalendar',
                'noheader'   => True
        ),
);
date_default_timezone_set('Europe/Warsaw');
require_once '../../DatabaseConnection.php';
echo '<option value="0">Brak</option>';
$result = SendQuery("select `egw_addressbook`.`n_fn`, `egw_addressbook`.`account_id`, `egw_addressbook`.`contact_id` from `egw_addressbook` where  `egw_addressbook`.`n_fn` like '%".  EscapeSpecialCharacters($_GET['search'])
                    ."%' order by `egw_addressbook`.`n_family`, `egw_addressbook`.`n_given`, `egw_addressbook`.`n_middle`");
$isFirst = true;
while ($row = GetNextRow($result))
{
    if ($isFirst)
    {
        $selected = ' selected = "selected"';
        $isFirst = false;
    }
    else
    {
        $selected = '';
    }
    echo '<option value="'.$row["contact_id"].'"' . $selected . '>'.$row["n_fn"] . '</option>';
}
?>
