<?php
$GLOBALS['egw_info'] = array(
        'flags' => array(
                'currentapp' => 'bcalendar',
                'noheader'   => True
        ),
);
require_once('../DatabaseConnection.php');
require_once('../SmartyConfig.php');
$smarty->assign('name', $_GET['name']);
$smarty->assign('sp', $_GET['patient']);
$smarty->assign('query', str_replace('&', '&amp;', $_SERVER['QUERY_STRING']));
$visits = SendQuery("select egw_cal.cal_deleted, `egw_addressbook`.`n_fn`, `egw_cal`.`cal_title`, `egw_cal`.`cal_description`, `egw_cal_dates`.`cal_start`, `egw_cal_dates`.`cal_end`, `egw_cal`.`cal_id` from `egw_cal` left join (`egw_addressbook`, `egw_cal_dates`, `egw_cal_user`) on (`egw_cal`.`cal_owner` = `egw_addressbook`.`account_id` and `egw_cal`.`cal_id` = `egw_cal_dates`.`cal_id` and `egw_cal`.`cal_id` = `egw_cal_user`.`cal_id`) where `egw_cal_user`.`cal_role` = 'REQ-PARTICIPANT' and `egw_cal_user`.`cal_user_id` = ".intval($_GET['patient']));
$statement = PrepareStatement("select s.fs_name AS FileName, prop_value FROM egw_sqlfs as f join egw_sqlfs as s on (f.fs_id = s.fs_dir) LEFT JOIN egw_sqlfs_props ON (s.fs_id = egw_sqlfs_props.fs_id) where f.fs_name = ?");
$ResultShouldBeGot = (GetRowsNumber($visits) > 90 && ResultCanBeGot());
while ($row = GetNextRow($visits))
{
    if ($row['cal_deleted'])
    {
        $attr[] = 'class="deleted"';
        $title = ' (zdarzenie usunięte)';
    }
    else
    {
        $attr[] = '';
        $title = '';
    }
    $vt[] = $row['cal_title'] . $title;
    if ($row['cal_description'] == '')
    {
        $vt[] = '&nbsp;';
    }
    else
    {
        $vt[] = $row['cal_description'];
    }
    $vt[] = $row['n_fn'];
    $vt[] = date('d.m.Y',$row['cal_start']).' r.';
    $vt[] = date('G:i',$row['cal_start']);
    $vt[] = date('G:i',$row['cal_end']);
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
$smarty->assign('attr', $attr);
CloseConnection();
$smarty->display('PatientVisits.tpl');
?>
