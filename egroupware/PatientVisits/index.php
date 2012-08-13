<?php  
require_once('../SmartyConfig.php');
require_once('../DatabaseConnection.php');
if ($_GET['search'] == null)
{
    $patients = SendQuery("select `n_fn`, `contact_id` from `egw_addressbook` order by `n_family`, `n_given`, `n_middle`");
}
else
{
    $smarty->assign('search', $_GET['search']);
    $patients = SendQuery("select `n_fn`, `contact_id` from `egw_addressbook` where `n_fn` like '%".EscapeSpecialCharacters($_GET['search'])
                          ."%' order by `n_family`, `n_given`, `n_middle`");
}
while ($row = GetNextRow($patients))
{
    $ps[$row['contact_id']] = $row['n_fn'];
}
$smarty->assign('patient', $ps);
if ($_GET['patient'] != null)
{
    $smarty->assign('focus', 'patient');
    $smarty->assign('sp', $_GET['patient']);
    $visits= SendQuery("select `egw_addressbook`.`n_fn`, `egw_cal`.`cal_title`, `egw_cal`.`cal_description`, `egw_cal_dates`.`cal_start`, `egw_cal_dates`.`cal_end` from `egw_cal` left join (`egw_addressbook`, `egw_cal_dates`, `egw_cal_user`) on (`egw_cal`.`cal_owner` = `egw_addressbook`.`account_id` and `egw_cal`.`cal_id` = `egw_cal_dates`.`cal_id` and `egw_cal`.`cal_id` = `egw_cal_user`.`cal_id`) where `egw_cal_user`.`cal_role` = 'REQ-PARTICIPANT' and `egw_cal_user`.`cal_user_id` = ".intval($_GET['patient']));
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
        $vt[] = date('d.m.Y',$row['cal_start']).' r.';
        $vt[] = date('G:i',$row['cal_start']);
        $vt[] = date('G:i',$row['cal_end']);
    }
    $smarty->assign('visits', $vt);
}
else
{
    $smarty->assign('focus', 'search');
}
CloseConnection();
$smarty->display('PatientVisits.tpl');
?>
