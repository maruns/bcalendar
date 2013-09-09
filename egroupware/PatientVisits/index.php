<?php  
$GLOBALS['egw_info'] = array(
        'flags' => array(
                'currentapp' => 'bcalendar',
                'noheader'   => True
        ),
);
require_once('../DatabaseConnection.php');
require_once('../SmartyConfig.php');
if ($_GET['search'] == null)
{
    $patients = SendQuery("select `n_fn`, `egw_addressbook`.`contact_id`, `tel_prefer`, `tel_cell_private`, `tel_cell`, `tel_work`, `tel_assistent`, `tel_home`, `tel_car`, `tel_other`, `contact_value` from `egw_addressbook` LEFT JOIN `egw_addressbook_extra` ON (`egw_addressbook`.`contact_id` = `egw_addressbook_extra`.`contact_id` AND `egw_addressbook_extra`.`contact_name` = 'PESEL') order by `n_family`, `n_given`, `n_middle`");
}
else
{
    $smarty->assign('search', $_GET['search']);
    $patients = SendQuery("select `n_fn`, `egw_addressbook`.`contact_id`, `tel_prefer`, `tel_cell_private`, `tel_cell`, `tel_work`, `tel_assistent`, `tel_home`, `tel_car`, `tel_other`, `contact_value` from `egw_addressbook` LEFT JOIN `egw_addressbook_extra` ON (`egw_addressbook`.`contact_id` = `egw_addressbook_extra`.`contact_id` AND `egw_addressbook_extra`.`contact_name` = 'PESEL') where `n_fn` like '%" . EscapeSpecialCharacters($_GET['search']) . "%' order by `n_family`, `n_given`, `n_middle`");
}
while ($row = GetNextRow($patients))
{
    if ($row['n_fn'])
    {
        $ps[] = $row['n_fn'];
    }
    else
    {
        $ps[] = '&nbsp;';
    }
    $tel_prefer = trim($row[$row['tel_prefer']]);
    if ($tel_prefer)
    {
        $ps[] = $tel_prefer;
    }
    else
    {
        $tel_cell_private = trim($row['tel_cell_private']);
        if ($tel_cell_private)
        {
            $ps[] = $tel_cell_private;
        }
        else
        {
            $tel_cell_private = trim($row['tel_work']);
            if ($tel_cell)
            {
                $ps[] = $tel_cell;
            }
            else
            {
                $tel_work = trim($row['tel_work']);
                if ($tel_work)
                {
                    $ps[] = $tel_work;
                }
                else
                {
                    $tel_assistent = trim($row['tel_assistent']);
                    if ($tel_assistent)
                    {
                        $ps[] = $tel_assistent;
                    }
                    else
                    {
                        $tel_home = trim($row['tel_home']);
                        if ($tel_home)
                        {
                            $ps[] = $tel_home;
                        }
                        else
                        {
                            $tel_home = trim($row['tel_car']);
                            if ($tel_car)
                            {
                                $ps[] = $tel_car;
                            }
                            else
                            {
                                $ps[] = trim($row['tel_other']);
                            }
                        }
                    }
                }
            }
        }
    }
    $ps[] = $row['contact_value'];
    $attr[] = "onclick=\"window.open('PatientVisits.php?name=" . htmlentities($row['n_fn'], ENT_QUOTES | ENT_XHTML) . "&amp;patient=" . 
              $row['contact_id'] . 
              "','_blank','width='+1000+',height='+400+',location=no,menubar=no,toolbar=no,scrollbars=yes,status=yes');\"";
}
$smarty->assign('patient', $ps);
$smarty->assign('attr', $attr);
if ($_GET['patient'] != null)
{
    $smarty->assign('focus', 'patient');
    
}
else
{
    $smarty->assign('focus', 'search');
}
CloseConnection();
$smarty->display('Patients.tpl');
?>
