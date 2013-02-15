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
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $modified = intval($_SERVER['REQUEST_TIME']);
    $title = EscapeSpecialCharacters(trim($_POST['title']));
    $id = intval($_POST['id']);
    $dentist = intval($_POST['dentist']);
    if ($id > 0)
    {
        "UPDATE `egw_cal` SET";
    }
    else
    {
        "INSERT INTO `egw_cal` (`cal_uid`, `cal_owner`, `cal_owner`, `cal_category`, `cal_modified`, `cal_priority`, `cal_public`, `cal_title`, `cal_title`,`cal_description`,`cal_modifier`, `cal_creator`, `cal_created`) VALUES ('calendar-0-" . uniqid() . "', " . $dentist . ", '', '" . $modified . "', 2, 1, " . $title . ", " . EscapeSpecialCharacters(trim($_POST['description'])) . ", " . $dentist . ", " . $dentist . ", " . $modified . ")";
    }
}
$smarty->assign('id', $_GET['cal_id']);
$smarty->assign('date', $_GET['date']);
$result = SendQuery("select `egw_addressbook`.`n_fn`, `egw_addressbook`.`account_id`, `egw_addressbook`.`contact_id` from `egw_addressbook` inner join `egw_accounts` on `egw_addressbook`.`account_id` = `egw_accounts`.`account_id` where `egw_accounts`.`account_type` = 'u' and `egw_accounts`.`account_primary_group` = -329 order by `egw_addressbook`.`n_family`, `egw_addressbook`.`n_given`, `egw_addressbook`.`n_middle`"); //-329
while ($row = GetNextRow($result))
{
     $dentists[$row["account_id"]] = $row["n_fn"];
}
ob_start();
include 'VideosList.php';
$smarty->assign('videos', ob_get_clean());
$smarty->display('../templates/Event.tpl');
?>
