<?php
require_once('../../SmartyConfig.php');
require_once('../../DatabaseConnection.php');

$result = SendQuery("select `egw_addressbook`.`n_fn`, `egw_addressbook`.`account_id` from `egw_addressbook` inner join `egw_accounts` on `egw_addressbook`.`account_id` = `egw_accounts`.`account_id` where `egw_accounts`.`account_type` = 'u' and `egw_accounts`.`account_primary_group` = -329 and (`egw_accounts`.`account_expires` = -1 or `egw_accounts`.`account_expires` > ".intval($_SERVER['REQUEST_TIME']).") order by `egw_addressbook`.`n_family`, `egw_addressbook`.`n_given`, `egw_addressbook`.`n_middle`");
$AccountIsSet = false;
while ($row = GetNextRow($result))
{
    if ($_GET['dentist'] == $row['account_id'] || $_POST['dentist'] == $row['account_id'])
    {
        $smarty->assign('name',$row['n_fn']);
        $smarty->assign('did',$row['account_id']);
        $AccountIsSet = true;
        $dentists[] = '<a id="current" href="?dentist='.$row['account_id'].'"><strong>'.$row['n_fn'].'</strong></a>';
    }
    else
    {
        $dentists[] = '<a href="?dentist='.$row['account_id'].'"><strong>'.$row['n_fn'].'</strong></a>';
    }
}
$smarty->assign('dentists', $dentists);
if ($AccountIsSet)
{
    if($_POST['submit'] == 'Zapisz')
    {print_r($_POST);
        foreach($_POST as $key=>$value)
        {
            if ($key[0] != 'S' || $key[1] != 'H')
            {
                continue;
            }
            $hs = intval($value);
            $ms = intval($_POST['SM'.substr($value, 2)]);
            $he = intval($_POST['E'.substr($value, 1)]);
            $me = intval($_POST['EM'.substr($value, 2)]);
            if ($hs > -1 && $hs < 24 && $he > -1 && $he < 24 && $ms > -1 && $ms < 60 && $me > -1 && $me < 60)
            {
                switch($key[2])
                {
                    case 'E':
                        switch($key[3])
                        {
                            case 'N':
                                $enq .= "update `PeriodsOfNormalWorkingTime` set `Start` = '".sprintf("%02d",$hs).':'.sprintf("%02d",$ms).
                                        ", `End` = '".sprintf("%02d",$he).':'.sprintf("%02d",$me)." where `ID` = ".inval(substr($key,5)).";";
                        }
                        break;
                    case 'N':
                        switch($key[3])
                        {
                            case 'N':
                                if (isset($nnq))
                                {
                                    $nnq .= ', ('.intval($_POST['dentist']).', '.$key[4].", '".sprintf("%02d",$hs).':'.sprintf("%02d",$ms)."', '".
                                            sprintf("%02d",$he).
                                            ':'.sprintf("%02d",$me)."')";
                                }
                                else
                                {echo $key.$value;
                                    $nnq = 'insert into `PeriodsOfNormalWorkingTime` (`account_id`, `Day`, `Start`, `End`) values ('.
                                           intval($_POST['dentist']).
                                           ', '.$key[4].", '".sprintf("%02d",$hs).':'.sprintf("%02d",$ms)."', '".
                                           sprintf("%02d",$he).
                                           ':'.sprintf("%02d",$me)."')";
                                }
                        }
                }
            }
        }
        SendQuery($enq.$nnq);echo $enq.$nnq;
    }
    $result = SendQuery("select `ID`, `Day`, `Start`, `End` from `PeriodsOfNormalWorkingTime` where `account_id` = ".intval($_GET['dentist']).
                        " order by `Start`");
    while ($row = GetNextRow($result))
    {
        $periods[$row['Day']][] = $row;
    }
    $smarty->assign('Monday', $periods[1]);
    $smarty->assign('Tuesday', $periods[2]);
    $smarty->assign('Wednesday', $periods[3]);
    $smarty->assign('Thursday', $periods[4]);
    $smarty->assign('Friday', $periods[5]);
    $smarty->assign('Saturday', $periods[6]);
    $smarty->assign('Sunday', $periods[7]);
    $result = SendQuery("select `PeriodsOfWorkingTimeForSpecialDates`.`ID`, `PeriodsOfWorkingTimeForSpecialDates`.`SpecialDateID`, `SpecialDates`.`Day`, `SpecialDates`.`Month`, `SpecialDates`.`Year`, `PeriodsOfWorkingTimeForSpecialDates`.`Start`, `PeriodsOfWorkingTimeForSpecialDates`.`End` from `SpecialDates` left join `PeriodsOfWorkingTimeForSpecialDates` on `PeriodsOfWorkingTimeForSpecialDates`.`SpecialDateID` = `SpecialDates`.`ID` where `SpecialDates`.`account_id` = ".intval($_GET['dentist']).
                        " order by `Start`");
    while ($row = GetNextRow($result))
    {
        $dates[$row['SpecialDateID']][] = $row;
    }
}
    
CloseConnection();
$smarty->display('WorkingHours.tpl');
?>
