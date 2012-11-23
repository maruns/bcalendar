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
$result = SendQuery("SELECT `egw_addressbook`.`n_fn`, `egw_cal`.`cal_title`, `egw_cal`.`cal_description`, (SELECT DISTINCT CONCAT(`egw_addressbook`.`n_family`, ' ', `egw_addressbook`.`n_given`, ' ', `egw_addressbook`.`n_middle`) FROM `egw_addressbook` JOIN `egw_cal_user` ON ( `egw_addressbook`.`contact_id` = `egw_cal_user`.`cal_user_id` ) WHERE `egw_cal_user`.`cal_id` = `egw_cal`.`cal_id` AND `egw_cal_user`.`cal_role` = 'REQ-PARTICIPANT'  limit 1) as `patient`, ( SELECT DISTINCT `egw_cal_dates`.`cal_start` FROM `egw_cal_dates` WHERE `egw_cal`.`cal_id` = `egw_cal_dates`.`cal_id` limit 1) AS `start`, ( SELECT DISTINCT `egw_cal_dates`.`cal_end` FROM `egw_cal_dates` WHERE `egw_cal`.`cal_id` = `egw_cal_dates`.`cal_id`  limit 1) AS `end`  FROM `egw_cal` join `egw_addressbook` on `egw_addressbook`.`account_id` = `egw_cal`.`cal_owner` where `egw_cal`.`cal_id` = " . intval($_GET['id']));
while($row = GetNextRow($result))
{
    $smarty->assign('title', $row['cal_title']);
    $smarty->assign('patient', $row['patient']);
    $smarty->assign('dentist', $row['n_fn']);
    $smarty->assign('description', $row['cal_description']);
    $smarty->assign('start', date('G:i',$row['start']));
    $smarty->assign('end', date('G:i',$row['end']));
}
CloseConnection();
$smarty->assign('date', intval(substr($_GET['date'], 6)) . '.' . substr($_GET['date'], 4, 2) . '.' . substr($_GET['date'], 0, 4));
$smarty->display('../templates/VisitInfo.tpl')
?>
