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
$title = EscapeSpecialCharacters(trim($_POST['title']));
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $title != "" && $title != null)
{
    $modified = intval($_SERVER['REQUEST_TIME']);
    $id = intval($_POST['id']);
    $dentist = intval($_POST['dentist']);
    $assistant = intval($_POST['assistant']);
    $patient = intval($_POST['patient']);
    if ($_POST['private'] == 'yes')
    {
        $public = 0;
    }
    else
    {
        $public = 1;
    }
    $sh = intval($_POST['sh']);
    $sm = intval($_POST['sm']);
    $time = intval($_POST['time']);
    $end = $sh * 60 + $sm + $time;
    foreach ($_POST as $key=>$value)
    {
        if ($key[0] == '_')
        {
            if ($value === 'yes')
            {
                $value = '1';
            }
            $acf[substr($key, 1)] = $value;
        }
    }
    if ($id > 0)
    {
        SendQueries("UPDATE `egw_cal` SET cal_title=" . $title . " `cal_owner` = " . $dentist . ", cal_public = " . $public . " `cal_modified` = " . $modified . " cal_description = " . EscapeSpecialCharacters(trim($_POST['description'])) . " cal_modifier = " . $dentist . " cal+category = '" . intval($_POST['category']) . "' WHERE cal_id = " . $id .
                    "UPDATE egw_cal_dates SET cal_start = " . strtotime($_POST['date'] . " ".sprintf("%02d",$sh).':'.sprintf("%02d",$sm)) . "," . strtotime($_POST['date'] . " ".sprintf("%02d",floor($end / 60)).':'.sprintf("%02d",$end % 60)) . ", cal_end = " . strtotime($_POST['date'] . " ".sprintf("%02d",floor($end / 60)).':'.sprintf("%02d",$end % 60))  . " WHERE cal_id = " . $id);
        if (is_array($acf))
        {
            SendQueryQuickly("DELETE FROM egw_cal_extra where cal_id = " . $id);
            $statement = PrepareStatement("INSERT INTO egw_cal_extra (cal_id, cal_extra_name, cal_extra_value) VALUES (LAST_INSERT_ID(), ?, ?)");
            $statement->bind_param('sss', 'c', $key, $value);
            foreach($acf as $key=>$value)
            {
                $statement->execute();
            }
        }
    }
    else
    {
        
        SendQueries("INSERT INTO `egw_cal` (`cal_uid`, `cal_owner`, `cal_category`, `cal_modified`, `cal_priority`, `cal_public`, `cal_title`, `cal_description`,`cal_modifier`, `cal_creator`, `cal_created`) VALUES (concat_ws('-', 'calendar', (SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'egroupware' AND TABLE_NAME = 'egw_cal'), 'e8fae07b2c2f77b2907ac91601c846fb'), " . $dentist . ", '" . intval($_POST['category']) . "', '" . $modified . "', 2, " . $public . ", " . $title . ", " . EscapeSpecialCharacters(trim($_POST['description'])) . ", " . $dentist . ", " . $dentist . ", " . $modified . "); INSERT INTO egw_cal_dates (cal_id, cal_start, cal_end) VALUES (LAST_INSERT_ID()," . strtotime($_POST['date'] . " ".sprintf("%02d",$sh).':'.sprintf("%02d",$sm)) . "," . strtotime($_POST['date'] . " ".sprintf("%02d",floor($end / 60)).':'.sprintf("%02d",$end % 60)) . ")");
        $statement = PrepareStatement("INSERT INTO egw_cal_user (cal_id, cal_user_type, cal_user_id, cal_role) VALUES (LAST_INSERT_ID(), ?, ?, ?)");
        if (is_object($statement))
        {
            $statement->bind_param('sss', 'u', $dentist, 'CHAIR');
            $statement->execute();
            if ($assistant > 0)
            {
                $statement->bind_param('sss', 'u', $assistant, 'assistant');
                $statement->execute();
            }
            if ($patient > 0)
            {
                $statement->bind_param('sss', 'c', $patient, 'REQ-PARTICIPANT');
                $statement->execute();
            }
            $statement->close();
        }
        if (is_array($acf))
        {
            $statement = PrepareStatement("INSERT INTO egw_cal_extra (cal_id, cal_extra_name, cal_extra_value) VALUES (LAST_INSERT_ID(), ?, ?)");
            $statement->bind_param('sss', 'c', $key, $value);
            foreach($acf as $key=>$value)
            {
                $statement->execute();
            }
        }
    }
}
$smarty->assign('id', $_GET['cal_id']);
if (!($id > 0))
{
    $id = intval($_GET['cal_id']);
}
if ($id > 0)
{
    $smarty->assign('PatientCanBeChanged', true);
    $smarty->assign('DentistCanBeChanged', true);
    $smarty->assign('AssistantCanBeChanged', true);
    $result = SendQuery("SELECT `egw_cal`.cal_title, `egw_cal`.cal_public, `egw_cal`.cal_description, `egw_cal`.cal_category, `egw_cal_dates`.cal_start, `egw_cal_dates`.cal_end FROM `egw_cal` LEFT JOIN egw_cal_dates ON (`egw_cal`.cal_id = `egw_cal_dates`.cal_id) WHERE `egw_cal`.`cal_id` =" . $id);
    while ($row = GetNextRow($result))
    {
         $smarty->assign('title', $row['cal_title']);
         $smarty->assign('cal_public', $row['cal_public']);
         $smarty->assign('description', $row['cal_description']);
         $smarty->assign('SelectedCategory', intval($row['cal_category']));
         $smarty->assign('start', date('d.m.Y',$row['date']));
         $smarty->assign('hour', date('G',$row['date']));
         $smarty->assign('minute', date('i',$row['date']));
    }
}
else
{
    $smarty->assign('PatientCanBeChanged', true);
    $smarty->assign('DentistCanBeChanged', true);
    $smarty->assign('AssistantCanBeChanged', true);
    $smarty->assign('owner', $_GET['owner']);
    $smarty->assign('hour', $_GET['hour']);
    $smarty->assign('minute', $_GET['minute']);
}
$smarty->assign('date', $_GET['date']);
$result = SendQuery("select `egw_addressbook`.`n_fn`, `egw_addressbook`.`account_id`, `egw_addressbook`.`contact_id` from `egw_addressbook` inner join `egw_accounts` on `egw_addressbook`.`account_id` = `egw_accounts`.`account_id` where `egw_accounts`.`account_type` = 'u' and `egw_accounts`.`account_primary_group` = -329 and (`egw_accounts`.`account_expires` = -1 or `egw_accounts`.`account_expires` > ".intval($_SERVER['REQUEST_TIME']) . ") order by `egw_addressbook`.`n_family`, `egw_addressbook`.`n_given`, `egw_addressbook`.`n_middle`"); //-329
$dentists = array();
while ($row = GetNextRow($result))
{
     $dentists[$row["account_id"]] = $row["n_fn"];
}
$smarty->assign('dentists', $dentists);
$smarty->assign('assistants', array('0' => 'Brak') + $dentists);
$CustomFields = array();
$result = SendQuery("SELECT `config_value` FROM `egw_config` WHERE `config_app` = 'calendar' AND `config_name` = 'customfields'");
while ($row = GetNextRow($result))
{
     $CustomFields = unserialize($row['config_value']);
}
/**
 * Sprawdza czy jedne pole następuje po drugim
 *
 * @param array $x pierwsze pole
 * @param array $y drugie poleuchu
 * @return int 1, jeśli pierwsze następuje po drugim; -1, jeśli drugie następuje po pierwszym; 0, jeśli są na tym samym poziomie
 */
function Follows($x, $y)
{
    if ( $x['order'] == $y['order'] )
    {
        return 0;
    }
    if ( $x['order'] < $y['order'] )
    {
        return -1;
    }
    else
    {
        return 1;
    }
} 

uasort($CustomFields, 'Follows');
$additional = '';
foreach ($CustomFields as $fk=>$field)
{
    switch ($field['type'])
    {
        case 'select':
            $additional .= '<p><label class="ll" for="' . $fk . '">' . $field['label'] . ': </label><select id="' . $fk . '" name="_' . $fk . 
                           '">';
            foreach ($field['values'] as $value=>$name)
            {
                $additional .= '<option value="' . $key . '">' . $name . '</option>';
            }
            $additional .= '</select></p>';
            break;
        case 'checkbox':
            $additional .= '<label for="' . $fk . '" class="cc"><input type="checkbox" id="' . $fk . 
                           '" name="_' . $fk . 
                           '"/>' . $field['label'] . '</label>';
            break;
        default:
            $additional .= '<p><label class="ll" for="' . $fk . '">' . $field['label'] . ': </label><input type="' . $field['type'] . 
                           '" id="' . $fk . '" name="_' . $fk . '"/></p>';
    }
}
$smarty->assign('additional', $additional);
$result = SendQuery("SELECT `cat_id`, `cat_name`, `cat_appname` FROM `egw_categories` WHERE `cat_appname` = 'calendar' OR `cat_appname` = 'bcalendar'");
$categories[''] = "Brak";
while ($row = GetNextRow($result))
{
     $categories[$row['cat_id']] = $row['cat_name'] . ' - ' . $row['cat_appname'];
}
$smarty->assign('categories', $categories);
ob_start();
include 'VideosList.php';
$smarty->assign('videos', ob_get_clean());
$smarty->display('../templates/Event.tpl');
?>
