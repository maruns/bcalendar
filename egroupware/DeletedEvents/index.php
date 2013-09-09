<?php
$GLOBALS['egw_info'] = array(
        'flags' => array(
                'currentapp' => 'bcalendar',
                'noheader'   => True
        ),
);
require_once('../DatabaseConnection.php');
require_once('../SmartyConfig.php');
$smarty->assign('owner', $_GET['owner']);
$smarty->assign('from', $_GET['from']);
$smarty->assign('to', $_GET['to']);
$dentists[-1] = 'Wszyscy';
if (isset($_GET['iad']))
{
    $smarty->assign('iad', ' checked="checked"');
    $result = SendQuery("select `egw_addressbook`.`n_fn`, `egw_addressbook`.`account_id` from `egw_addressbook` inner join `egw_accounts` on `egw_addressbook`.`account_id` = `egw_accounts`.`account_id` where `egw_accounts`.`account_type` = 'u' order by `egw_addressbook`.`n_family`, `egw_addressbook`.`n_given`, `egw_addressbook`.`n_middle`");
}
else
{
    $result = SendQuery("select `egw_addressbook`.`n_fn`, `egw_addressbook`.`account_id` from `egw_addressbook` inner join `egw_accounts` on `egw_addressbook`.`account_id` = `egw_accounts`.`account_id` where `egw_accounts`.`account_type` = 'u' AND account_status = 'A' AND (`egw_accounts`.`account_expires` = -1 or `egw_accounts`.`account_expires` > ".intval($_SERVER['REQUEST_TIME']).") order by `egw_addressbook`.`n_family`, `egw_addressbook`.`n_given`, `egw_addressbook`.`n_middle`");
}
while ($row = GetNextRow($result))
{
     $dentists[$row["account_id"]] = $row["n_fn"];
}
$smarty->assign('dentists', $dentists);
if ($_GET['from'] && $_GET['to'])
{
    if ($_GET['owner'] > 0)
    {
        $visits = SendQuery("select `egw_addressbook`.`n_fn`, `egw_cal`.`cal_title`, `egw_cal`.`cal_description`, egw_cal.cal_deleted, (SELECT DISTINCT `egw_addressbook`.`n_fn` FROM `egw_addressbook` JOIN `egw_cal_user` ON ( `egw_addressbook`.`contact_id` = `egw_cal_user`.`cal_user_id` ) WHERE `egw_cal_user`.`cal_id` = `egw_cal`.`cal_id` AND `egw_cal_user`.`cal_role` = 'REQ-PARTICIPANT'  limit 1) AS `patient`, `egw_cal_dates`.`cal_start`, `egw_cal_dates`.`cal_end`, `egw_cal`.`cal_id` from `egw_cal` left join (`egw_addressbook`, `egw_cal_dates`) on (`egw_cal`.`cal_owner` = `egw_addressbook`.`account_id` and `egw_cal`.`cal_id` = `egw_cal_dates`.`cal_id`) where egw_cal.cal_deleted IS NOT NULL AND `cal_owner` = ".intval($_GET['owner']) . " AND egw_cal_dates.cal_start BETWEEN ".strtotime($_GET['from']. '00:00'). ' and '.strtotime($_GET['to']. '23:59'));
    $statement = PrepareStatement("select s.fs_name AS FileName, prop_value FROM egw_sqlfs as f join egw_sqlfs as s on (f.fs_id = s.fs_dir) LEFT JOIN egw_sqlfs_props ON (s.fs_id = egw_sqlfs_props.fs_id) where f.fs_name = ?");
    }
    else
    {
        $visits = SendQuery("select `egw_addressbook`.`n_fn`, `egw_cal`.`cal_title`, `egw_cal`.`cal_description`, (SELECT DISTINCT `egw_addressbook`.`n_fn` FROM `egw_addressbook` JOIN `egw_cal_user` ON ( `egw_addressbook`.`contact_id` = `egw_cal_user`.`cal_user_id` ) WHERE `egw_cal_user`.`cal_id` = `egw_cal`.`cal_id` AND `egw_cal_user`.`cal_role` = 'REQ-PARTICIPANT'  limit 1) AS `patient`, `egw_cal_dates`.`cal_start`, `egw_cal_dates`.`cal_end`, `egw_cal`.`cal_id` from `egw_cal` left join (`egw_addressbook`, `egw_cal_dates`) on (`egw_cal`.`cal_owner` = `egw_addressbook`.`account_id` and `egw_cal`.`cal_id` = `egw_cal_dates`.`cal_id`) where egw_cal.cal_deleted IS NOT NULL AND egw_cal_dates.cal_start BETWEEN ".strtotime($_GET['from']. '00:00'). ' and '.strtotime($_GET['to']. '23:59'));
    $statement = PrepareStatement("select s.fs_name AS FileName, prop_value FROM egw_sqlfs as f join egw_sqlfs as s on (f.fs_id = s.fs_dir) LEFT JOIN egw_sqlfs_props ON (s.fs_id = egw_sqlfs_props.fs_id) where f.fs_name = ?");
    }
    $ResultShouldBeGot = (GetRowsNumber($visits) > 90 && ResultCanBeGot());
    while ($row = GetNextRow($visits))
    {
        $vt[] = $row['cal_title'];
        if ($row['cal_description'] == '')
        {
            $vt[] = '&nbsp;';
        }
        else
        {
            $vt[] = $row['cal_description'];
        }
        $vt[] = $row['n_fn'];
        $vt[] = $row['patient'];
        $vt[] = date('d.m.Y',$row['cal_start']).' r.';
        $vt[] = date('G:i',$row['cal_start']) . ' - ' . date('G:i',$row['cal_end']);
        if (is_object($statement))
        {
            $statement->bind_param('i', $row['cal_id']);           
            $statement->execute();
            $attachments = '';
            if ($ResultShouldBeGot)
            {
                $files = GetResult($statement);
                while ($fr = GetNextRow($files))
                {
                    $attachments .= ShowLinkToFile($fr['FileName'], $row['cal_id'], $fr['prop_value']);
                }
            }
            else
            {
                $statement->bind_result($file, $comment);       
                while (Fetch($statement))
                {

                    $attachments .= ShowLinkToFile($file, $row['cal_id'], $comment);
                }
            } 
            $vt[] = $attachments;
        }
        else
        {
            $vt[] = '';
        }
    }
    $smarty->assign('visits', $vt);
}
CloseConnection();
$smarty->display('DeletedEvents.tpl');
?>
