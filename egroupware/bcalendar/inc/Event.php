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
    $smarty->assign('id', $id);
    $dentist = intval($_POST['dentist']);
    $assistant = intval($_POST['assistant']);
    $patient = intval($_POST['patient']);
    if ($_POST['private'] == 'on')
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
    $nps = EscapeSpecialCharacters(trim($_POST['nps']));
    $npn = EscapeSpecialCharacters(trim($_POST['npn']));
    $phone = EscapeSpecialCharacters(trim($_POST['phone']));
    $pesel = EscapeSpecialCharacters(trim($_POST['pesel']));
    $recipe = EscapeSpecialCharacters(trim($_POST['recipe']));
    $agreement = EscapeSpecialCharacters(trim($_POST['agreement']));
    $plan = EscapeSpecialCharacters(trim($_POST['plan']));
    $cl = EscapeSpecialCharacters(trim($_POST['cl']));
    foreach ($_POST as $key=>$value)
    {
        switch($key[0])
        {
            case '_':
                if ($value === 'on')
                {
                    $value = '1';
                }
                $acf[substr($key, 1)] = $value;
                break;
            case '#':
                $comment[intval(substr($key, 1))] = $value;
        }
    }
    if ($id > 0)
    {
        if ($patient > 0)
        {
            if ($_POST['old_patient'] > 0)
            {
                $PatientUpdateQuery = "; UPDATE egw_cal_user SET cal_user_type = 'c', cal_user_id = '" . $patient . "', cal_status = '" . EscapeSpecialCharacters($_POST['status']) . "' WHERE cal_role = 'REQ-PARTICIPANT' AND cal_id = " . $id;
            }
            else
            {
                $PatientUpdateQuery = "; INSERT INTO egw_cal_user (cal_id, cal_user_type, cal_user_id, cal_role, cal_status) VALUES (" . $id . ", 'c', '" . $patient . "', 'REQ-PARTICIPANT', '" . EscapeSpecialCharacters($_POST['status']) . "')";
            }
        }
        else
        {
            if ($_POST['patient'] == "-1")
            {
                $PatientUpdateQuery = "; INSERT INTO `egw_addressbook` (contact_owner, n_family, n_given, n_fn, n_fileas, tel_cell, tel_prefer, contact_created, contact_creator, contact_modified, contact_uid) VALUES (-329, '" . $nps . "', '" . $npn . "', '" . $npn . " " . $nps . "', '" . $nps . ", " . $npn . "', '" . $phone . "', 'tel_cell', " . $modified . ", -329, " . $modified . ", concat_ws('-', 'addressbook', (SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'egroupware' AND TABLE_NAME = 'egw_addressbook'), 'd2dad1acdc42885bfaa1a4046cb5c5ec'));";
                if ($pesel && !is_null($pesel) && $pesel != '')
                {
                    $PatientUpdateQuery .= " INSERT INTO `egw_addressbook_extra`  (`contact_id`, `contact_owner`, `contact_name`, `contact_value`) VALUES (LAST_INSERT_ID(), 0, 'PESEL', " . $pesel . "); ";
                }
                if ($_POST['old_patient'] > 0)
                {
                    $PatientUpdateQuery .= " UPDATE egw_cal_user SET cal_user_type = 'c', cal_user_id = LAST_INSERT_ID(), cal_status = '" . EscapeSpecialCharacters($_POST['status']) . "' WHERE cal_role = 'REQ-PARTICIPANT' AND cal_id = " . $id . ";";
                }
                else
                {
                    $PatientUpdateQuery .= " INSERT INTO egw_cal_user (cal_id, cal_user_type, cal_user_id, cal_role, cal_status) VALUES (" . $id . ", 'c', LAST_INSERT_ID(), 'REQ-PARTICIPANT', '" . EscapeSpecialCharacters($_POST['status']) . "'); ";
                }
            }
            else
            {
                $PatientUpdateQuery = ""; 
            }
        }
        if (($recipe != "" && $recipe != null) || ($agreement != "" && $agreement != null) || ($plan != "" && $plan != null) || 
            ($cl != "" && $cl != null))
        {
            $AdditionalQuery = "; INSERT INTO Visits (cal_id, Recipe, Agreement, Plan) VALUES (" . $id . ", '" . $recipe . "', '" . 
                               $agreement . "', '" . $plan .
                               "') ON DUPLICATE KEY UPDATE Recipe = '" . $recipe . "', Agreement = '" . $agreement . "', Plan = '" . $plan . 
                               "', CheckList = '" . $cl . "'";
        }
        else
        {
            $AdditionalQuery = "; DELETE FROM Visits WHERE cal_id = " . $id;
        }
        $FileIsUploaded = file_exists($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name']);
        if ($FileIsUploaded)
        {
            $FileQuery = "; INSERT IGNORE INTO egw_sqlfs (fs_dir, fs_name, fs_mode, fs_uid, fs_gid, fs_created, fs_modified, fs_mime, fs_creator, fs_active) SELECT (SELECT fs_id FROM egw_sqlfs WHERE fs_name = 'calendar' LIMIT 1), " . $id . ", 0, 0, 0, NOW(), NOW(), 'httpd/unix-directory', 0, 1 FROM egw_sqlfs WHERE NOT EXISTS (SELECT fs_id FROM egw_sqlfs WHERE fs_name = " . $id . ") LIMIT 1";
            BeginTransaction();
        }
        SendQueries("UPDATE `egw_cal` SET cal_title='" . $title . "', `cal_owner` = " . $dentist . ", cal_public = " . $public . ", `cal_modified` = " . $modified . ", cal_description = '" . EscapeSpecialCharacters(trim($_POST['description'])) . "', cal_modifier = " . $dentist . ", cal_category = '" . intval($_POST['category']) . "' WHERE cal_id = " . $id .
                    "; UPDATE egw_cal_dates SET cal_start = " . strtotime($_POST['date'] . " ".sprintf("%02d",$sh).':'.sprintf("%02d",$sm)) . ", cal_end = " . strtotime($_POST['date'] . " ".sprintf("%02d",floor($end / 60)).':'.sprintf("%02d",$end % 60))  . " WHERE cal_id = " . $id . $PatientUpdateQuery . $AdditionalQuery . $FileQuery);
        if ($FileIsUploaded)
        {
            $result = SendQuery("SELECT fs_id FROM egw_sqlfs WHERE fs_name = " . $id . " LIMIT 1");
            $nfc = EscapeSpecialCharacters(trim($_POST['comment']));
            $creator = EscapeSpecialCharacters($_COOKIE['last_loginid']);
            while($row = GetNextRow($result))
            {
                if ($nfc)
                {
                    SendQueries("INSERT INTO egw_sqlfs (fs_dir, fs_name, fs_mode, fs_uid, fs_gid, fs_created, fs_modified, fs_mime, fs_size, fs_creator, fs_modifier, fs_active) VALUES (" . $row['fs_id'] . ", '" . basename($_FILES['file']['name']) . "', 0, (SELECT account_id FROM egw_accounts WHERE account_lid = '" . $creator . "' LIMIT 1), 0, NOW(), NOW(), '" . EscapeSpecialCharacters($_FILES['file']['type']) . "', " . intval($_FILES['file']['size']) . ", (SELECT account_id FROM egw_accounts WHERE account_lid = '" . $creator . "' LIMIT 1), (SELECT account_id FROM egw_accounts WHERE account_lid = '" . $creator . "' LIMIT 1), 1); INSERT INTO egw_sqlfs_props (fs_id, prop_namespace, prop_name, prop_value) VALUES (LAST_INSERT_ID(), 'http://egroupware.org/', 'comment', '" . $nfc . "')");
                }
                else
                {
                    SendQueryQuickly("INSERT INTO egw_sqlfs (fs_dir, fs_name, fs_mode, fs_uid, fs_gid, fs_created, fs_modified, fs_mime, fs_size, fs_creator, fs_modifier, fs_active) VALUES (" . $row['fs_id'] . ", '" . basename($_FILES['file']['name']) . "', 0, (SELECT account_id FROM egw_accounts WHERE account_lid = '" . $creator . "' LIMIT 1), 0, NOW(), NOW(), '" . EscapeSpecialCharacters($_FILES['file']['type']) . "', " . intval($_FILES['file']['size']) . ", (SELECT account_id FROM egw_accounts WHERE account_lid = '" . $creator . "' LIMIT 1), (SELECT account_id FROM egw_accounts WHERE account_lid = '" . $creator . "' LIMIT 1), 1)");
                }
            }
            $result = SendQuery("SELECT LAST_INSERT_ID() as ID");
            while($row = GetNextRow($result))
            {
                $l = strlen($row['ID']);
                $dir = '/var/lib/egroupware/default/files/sqlfs/' . intval($row['ID'][$l - 4]) . intval($row['ID'][$l - 3]);
                if (!is_dir($dir))
                {
                    mkdir($dir, 0700, true);
                }
                move_uploaded_file($_FILES['file']['tmp_name'], $dir . '/' . $row['ID']);
            }
            Commit();
        }
        $statement = PrepareStatement("UPDATE egw_cal_user SET cal_user_type = 'u', cal_user_id = ? WHERE cal_role = ? AND cal_id = " . $id);
        if (is_object($statement))
        {
            $type = 'CHAIR';
            $statement->bind_param('ss', $dentist, $type);
            
            $statement->execute();
            if ($assistant > 0)
            {
                if ($_POST['old_patient'] > 0)
                {
                    $type = 'assistant';
                    $statement->bind_param('ss', $assistant, $type);
                    $statement->execute();
                }
                else
                {
                    SendQueryQuickly("INSERT INTO egw_cal_user (cal_id, cal_user_type, cal_user_id, cal_role) VALUES (" . $id . ", 'u', '" . $assistant . "', 'assistant')");
                }
            }
            
            $statement->close();
        }
        if (is_array($acf))
        {
            SendQueryQuickly("DELETE FROM egw_cal_extra where cal_id = " . $id);
            $statement = PrepareStatement("INSERT INTO egw_cal_extra (cal_id, cal_extra_name, cal_extra_value) VALUES (" . $id . ", ?, ?)");
            $statement->bind_param('ss', $key, $value);
            foreach($acf as $key=>$value)
            {
                $statement->execute();
            }
        }
        if (is_array($comment))
        {
            $statement = PrepareStatement("INSERT INTO egw_sqlfs_props (fs_id, prop_namespace, prop_name, prop_value) VALUES (?, 'http://egroupware.org/', 'comment', ?) ON DUPLICATE KEY UPDATE prop_value = ?");
            $statement->bind_param('iss', $key, $value, $value);
            $DeleteStatement = PrepareStatement("DELETE FROM egw_sqlfs_props WHERE fs_id = ?");
            $DeleteStatement->bind_param('i', $key);
            foreach($comment as $key=>$value)
            {
                $value = trim($value);
                if ($value)
                {
                    $statement->execute();
                }
                else
                {
                    $DeleteStatement->execute();
                }
            }
        }
        $rf = EscapeSpecialCharacters($_POST['rf']);
        if ($rf)
        {
            SendQueries('DELETE FROM egw_sqlfs WHERE fs_id = ' . $rf . '; DELETE FROM egw_sqlfs_props WHERE fs_id = ' . $rf);
            $l = strlen($rf);
            $dir = '/var/lib/egroupware/default/files/sqlfs/' . intval($rf[$l - 4]) . intval($rf[$l - 3]);
            $FileToDelete = $dir . '/' . $rf;
            if (file_exists($FileToDelete))
            {
                unlink($FileToDelete);
            }
        }
    }
    else
    {
        if (!$_POST['more'])
        {
            if (($recipe != "" && $recipe != null) || ($agreement != "" && $agreement != null) || ($plan != "" && $plan != null) || 
                ($cl != "" && $cl != null))
            {
                $AdditionalQuery = "; INSERT INTO Visits (cal_id, Recipe, Agreement, Plan, CheckList) VALUES (LAST_INSERT_ID(), '" . $recipe . "', '" . 
                                   $agreement . "', '" . $plan . "', '" . $cl . "')";
            }
            else
            {
                $AdditionalQuery = "";
            }
            $FileIsUploaded = file_exists($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name']);
            if ($FileIsUploaded)
            {
                $AdditionalQuery .= "; ";
            }
            $creator = EscapeSpecialCharacters($_COOKIE['last_loginid']);
            BeginTransaction();
            SendQueries("INSERT INTO `egw_cal` (tz_id, caldav_name, `cal_uid`, `cal_owner`, `cal_category`, `cal_modified`, `cal_priority`, `cal_public`, `cal_title`, `cal_description`,`cal_modifier`, `cal_creator`, `cal_created`) VALUES (316, concat((SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'egroupware' AND TABLE_NAME = 'egw_cal'), '.ics'), concat_ws('-', 'calendar', (SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'egroupware' AND TABLE_NAME = 'egw_cal'), 'e8fae07b2c2f77b2907ac91601c846fb'), " . $dentist . ", '" . intval($_POST['category']) . "', '" . $modified . "', 2, " . $public . ", '" . $title . "', '" . EscapeSpecialCharacters(trim($_POST['description'])) . "', " . $dentist . ", (SELECT account_id FROM egw_accounts WHERE account_lid = '" . $creator . "' LIMIT 1), " . $modified . "); INSERT INTO egw_cal_dates (cal_id, cal_start, cal_end) VALUES (LAST_INSERT_ID()," . strtotime($_POST['date'] . " ".sprintf("%02d",$sh).':'.sprintf("%02d",$sm)) . "," . strtotime($_POST['date'] . " ".sprintf("%02d",floor($end / 60)).':'.sprintf("%02d",$end % 60)) . ")" . $AdditionalQuery);
            $LIIDS = PrepareStatement("SELECT LAST_INSERT_ID()");
            if (is_object($LIIDS))
            {
                $LIIDS->execute();
                $LIIDS->bind_result($id);
                while ($LIIDS->fetch())
                {
                }
                $statement = PrepareStatement("INSERT INTO egw_cal_user (cal_id, cal_user_type, cal_user_id, cal_role, cal_status) VALUES (".$id.", ?, ?, ?, ?)");
                if (is_object($statement))
                {
                    $type = 'u';
                    $role = 'CHAIR';
                    $status = 'A';
                    $statement->bind_param('ssss', $type, $dentist, $role, $status);
                    $statement->execute();
                    if ($assistant > 0)
                    {
                        $role = 'assistant';
                        $statement->bind_param('ssss', $type, $assistant, $role, $status);
                        $statement->execute();
                    }
                    if ($patient == -1)
                    {
                        $modified = intval($_SERVER['REQUEST_TIME']);
                        SendQueryQuickly("INSERT INTO `egw_addressbook` (contact_owner, n_family, n_given, n_fn, n_fileas, tel_cell, tel_prefer, contact_created, contact_creator, contact_modified, contact_uid) VALUES (-329, '" . $nps . "', '" . $npn . "', '" . $npn . " " . $nps . "', '" . $nps . ", " . $npn . "', '" . $phone . "', 'tel_cell', " . $modified . ", -329, " . $modified . ", concat_ws('-', 'addressbook', (SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'egroupware' AND TABLE_NAME = 'egw_addressbook'), 'd2dad1acdc42885bfaa1a4046cb5c5ec'))");
                        $LIIDS->execute();
                        $LIIDS->bind_result($patient);
                        while ($LIIDS->fetch())
                        {
                        }
                        if ($pesel && !is_null($pesel) && $pesel != '')
                        {
                            SendQueryQuickly("INSERT INTO `egw_addressbook_extra`  (`contact_id`, `contact_owner`, `contact_name`, `contact_value`) VALUES (" . $patient . ", 0, 'PESEL', " . $pesel . ")");
                        }
                    }
                    if ($patient > 0)
                    {
                        $type = 'c';
                        $role = 'REQ-PARTICIPANT';
                        $statement->bind_param('ssss', $type, $patient, $role, $_POST['status']);
                        $statement->execute();
                    }
                    $statement->close();
                }
                if (is_array($acf))
                {
                    $statement = PrepareStatement("INSERT INTO egw_cal_extra (cal_id, cal_extra_name, cal_extra_value) VALUES (?, ?, ?)");
                    if (is_object($statement))
                    {
                        $statement->bind_param('iss', $id, $key, $value);
                        foreach($acf as $key=>$value)
                        {
                            $statement->execute();
                        }
                        $statement->close();
                    }            
                }
                if ($FileIsUploaded)
                {
                    SendQueryQuickly("INSERT IGNORE INTO egw_sqlfs (fs_dir, fs_name, fs_mode, fs_uid, fs_gid, fs_created, fs_modified, fs_mime, fs_creator, fs_active) SELECT (SELECT fs_id FROM egw_sqlfs WHERE fs_name = 'calendar' LIMIT 1), " . $id . ", 0, 0, 0, NOW(), NOW(), 'httpd/unix-directory', 0, 1 FROM egw_sqlfs WHERE NOT EXISTS (SELECT fs_id FROM egw_sqlfs WHERE fs_name = '" . $id . "') LIMIT 1");
                    $result = SendQuery("SELECT fs_id FROM egw_sqlfs WHERE fs_name = " . $id . " LIMIT 1");
                    $nfc = EscapeSpecialCharacters(trim($_POST['comment']));               
                    while($row = GetNextRow($result))
                    {
                        if ($nfc)
                        {
                            SendQueries("INSERT INTO egw_sqlfs (fs_dir, fs_name, fs_mode, fs_uid, fs_gid, fs_created, fs_modified, fs_mime, fs_size, fs_creator, fs_modifier, fs_active) VALUES (" . $row['fs_id'] . ", '" . basename($_FILES['file']['name']) . "', 0, (SELECT account_id FROM egw_accounts WHERE account_lid = '" . $creator . "' LIMIT 1), 0, NOW(), NOW(), '" . EscapeSpecialCharacters($_FILES['file']['type']) . "', " . intval($_FILES['file']['size']) . ", (SELECT account_id FROM egw_accounts WHERE account_lid = '" . $creator . "' LIMIT 1), (SELECT account_id FROM egw_accounts WHERE account_lid = '" . $creator . "' LIMIT 1), 1); INSERT INTO egw_sqlfs_props (fs_id, prop_namespace, prop_name, prop_value) VALUES (LAST_INSERT_ID(), 'http://egroupware.org/', 'comment', '" . $nfc . "')");
                        }
                        else
                        {
                            SendQueryQuickly("INSERT INTO egw_sqlfs (fs_dir, fs_name, fs_mode, fs_uid, fs_gid, fs_created, fs_modified, fs_mime, fs_size, fs_creator, fs_modifier, fs_active) VALUES (" . $row['fs_id'] . ", '" . basename($_FILES['file']['name']) . "', 0, (SELECT account_id FROM egw_accounts WHERE account_lid = '" . $creator . "' LIMIT 1), 0, NOW(), NOW(), '" . EscapeSpecialCharacters($_FILES['file']['type']) . "', " . intval($_FILES['file']['size']) . ", (SELECT account_id FROM egw_accounts WHERE account_lid = '" . $creator . "' LIMIT 1), (SELECT account_id FROM egw_accounts WHERE account_lid = '" . $creator . "' LIMIT 1), 1)");
                        }
                    }
                    $LIIDS->execute();
                    $LIIDS->bind_result($fid);
                    while ($LIIDS->fetch())
                    {
                    }
                    $fidt = strval($fid);
                    $l = strlen($fidt);
                    $dir = '/var/lib/egroupware/default/files/sqlfs/' . intval($fidt[$l - 4]) . intval($fidt[$l - 3]);
                    if (!is_dir($dir))
                    {
                        mkdir($dir, 0700, true);
                    }
                    move_uploaded_file($_FILES['file']['tmp_name'], $dir . '/' . $fidt);
                }
                $LIIDS->close();
            }
            Commit();
        }
    }
    if ($_POST['apply'])
    {
        $smarty->assign('id', $id);
    }
    $smarty->assign('OpenerShouldBeRefreshed', true);
    if (!$_POST['more'] || $id > 0)
    {
        $smarty->assign('msg', "'Zdarzenie zostało zapisane'");
        $smarty->assign('OpenerShouldBeRefreshed', true);
    }
}
else
{
    if ($_SERVER["QUERY_STRING"])
    {
        $smarty->assign('CurrentQueryString', $_SERVER["QUERY_STRING"]);
    }
    $smarty->assign('id', $_GET['cal_id']);
}
$smarty->assign('OldQueryString', $_POST["old_qs"]);
if ($_POST['ok'] || $_POST['more'])
{
    $smarty->assign('WindowShouldBeClosed', true);
    if ($_POST['more'])
    {
        $smarty->assign('StandardWindowShouldBeOpened', true);
        
    }
}
else
{
    $smarty->assign('so', array('U' => 'Brak odpowiedzi', 'A' => 'Zaakceptowano', 'R' => 'Odrzucone', 'T' => 'Niezobowiązujący',
                                'D' => 'Oddelegowane'));
}
if (!($id > 0))
{
    $id = intval($_GET['cal_id']);
}
if ($id > 0)
{
    
    $smarty->assign('PatientCanBeChanged', true);
    $smarty->assign('DentistCanBeChanged', true);
    $smarty->assign('AssistantCanBeChanged', true);
    $result = SendQuery("SELECT `egw_cal`.cal_title, `egw_cal`.cal_owner, `egw_cal`.cal_public, `egw_cal`.cal_description, `egw_cal`.cal_category, `egw_cal_dates`.cal_start, `egw_cal_dates`.cal_end, (SELECT DISTINCT `egw_cal_user`.cal_user_id FROM `egw_cal_user` WHERE `egw_cal_user`.`cal_id` = `egw_cal`.`cal_id` AND `egw_cal_user`.`cal_role` = 'REQ-PARTICIPANT' limit 1) AS `patient`, (SELECT DISTINCT `egw_cal_user`.cal_status FROM `egw_cal_user` WHERE `egw_cal_user`.`cal_id` = `egw_cal`.`cal_id` AND `egw_cal_user`.`cal_role` = 'REQ-PARTICIPANT' limit 1) AS `status`, (SELECT DISTINCT `egw_addressbook`.`n_fn` FROM `egw_addressbook` JOIN `egw_cal_user` ON ( `egw_addressbook`.`contact_id` = `egw_cal_user`.`cal_user_id` ) WHERE `egw_cal_user`.`cal_id` = `egw_cal`.`cal_id` AND `egw_cal_user`.`cal_role` = 'REQ-PARTICIPANT' limit 1) AS `pn`, (SELECT DISTINCT `egw_cal_user`.cal_user_id FROM `egw_cal_user` WHERE `egw_cal_user`.`cal_id` = `egw_cal`.`cal_id` AND `egw_cal_user`.`cal_role` = 'assistant' limit 1) AS `assistant`, (SELECT DISTINCT Visits.Recipe FROM Visits WHERE Visits.cal_id = egw_cal.cal_id LIMIT 1) AS `Recipe`, (SELECT DISTINCT Visits.Agreement FROM Visits WHERE Visits.cal_id = egw_cal.cal_id LIMIT 1) AS `Agreement`, (SELECT Visits.Plan FROM Visits WHERE Visits.cal_id = egw_cal.cal_id LIMIT 1) AS `Plan`, (SELECT Visits.CheckList FROM Visits WHERE Visits.cal_id = egw_cal.cal_id LIMIT 1) AS `CheckList`, (SELECT egw_accounts.account_lid FROM egw_accounts WHERE account_id = `egw_cal`.cal_creator LIMIT 1) AS Creator FROM `egw_cal` LEFT JOIN (egw_cal_dates) ON (`egw_cal`.cal_id = `egw_cal_dates`.cal_id) WHERE `egw_cal`.`cal_id` = " . $id);
    while ($row = GetNextRow($result))
    {
         $smarty->assign('title', $row['cal_title']);
         $smarty->assign('cal_public', $row['cal_public']);
         $smarty->assign('description', $row['cal_description']);
         $smarty->assign('SelectedCategory', intval($row['cal_category']));        
         $smarty->assign('start', date('d.m.Y',$row['cal_start']));
         $smarty->assign('hour', date('G',$row['cal_start']));
         $smarty->assign('minute', date('i',$row['cal_start']));
         $smarty->assign('time', ($row['cal_end'] - $row['cal_start']) / 60 );
         $smarty->assign('patient', $row['patient']);
         $smarty->assign('pn', $row['pn']);
         $smarty->assign('status', $row['status']);
         $smarty->assign('assistant', $row['assistant']);
         $assistant = $row['assistant'];
         $smarty->assign('owner', $row['cal_owner']);
         $owner = $row['cal_owner'];
         $smarty->assign('Recipe', $row['Recipe']);
         $smarty->assign('Agreement', $row['Agreement']);
         $smarty->assign('Plan', $row['Plan']);
         $smarty->assign('CheckList', $row['CheckList']);
         $smarty->assign('Creator', $row['Creator']);
    }
    $result = SendQuery("SELECT `cal_extra_name`, `cal_extra_value` FROM `egw_cal_extra` WHERE `cal_id` = " . $id);
    while ($row = GetNextRow($result))
    {
         $cfv[$row['cal_extra_name']] = $row['cal_extra_value'];
    }
    $result = SendQuery("select s.fs_name AS FileName, prop_value, s.fs_id as ID FROM egw_sqlfs as f join egw_sqlfs as s on (f.fs_id = s.fs_dir) LEFT JOIN egw_sqlfs_props ON (s.fs_id = egw_sqlfs_props.fs_id) where f.fs_name = " . $id);
    $mask = ENT_XHTML | ENT_QUOTES;
    while ($row = GetNextRow($result))
    {
        $row['image'] = GetIcon($row['FileName'], $id);
        $row['FileName'] = htmlspecialchars($row['FileName'], $mask);
        $files[] = $row;
    }
    $smarty->assign('files', $files);
}
else
{
    $smarty->assign('PatientCanBeChanged', true);
    $smarty->assign('DentistCanBeChanged', true);
    $smarty->assign('AssistantCanBeChanged', true);
    $smarty->assign('owner', $_GET['owner']);
    $smarty->assign('hour', $_GET['hour']);
    $smarty->assign('minute', $_GET['minute']);
    $smarty->assign('loss', ' (utrata danych)');
}
/**
 * Sprawdza czy jedne pole następuje po drugim
 *
 * @param array $x pierwsze pole
 * @param array $y drugie pole
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
$ToRemove = intval($_GET['remove']);
if ($ToRemove > 0)
{
    SendQueryQuickly("UPDATE `egw_cal` SET `cal_deleted` = " . intval($_SERVER['REQUEST_TIME']) . " WHERE `cal_id` = " . $ToRemove);
    $smarty->assign('WindowShouldBeClosed', true);
    $smarty->assign('OpenerShouldBeRefreshed', true);
    $smarty->assign('msg', "'Zdarzenie zostało usunięte'");
}
else
{
    $smarty->assign('date', $_GET['date']);
    if (!($owner > 0))
    {
        $owner = $_GET['owner'];
    }
    if ($owner > 0)
    {
        $OwnerQuery = "`egw_addressbook`.`account_id` = " . $owner . " OR ";
    }
    else
    {
        $OwnerQuery = '';
    }
    if ($assistant > 0)
    {
        $AssistantQuery = "`egw_addressbook`.`account_id` = " . $assistant . " OR ";
    }
    else
    {
        $AssistantQuery = '';
    }
    $result = SendQuery("select `egw_addressbook`.`n_fn`, `egw_addressbook`.`account_id` from `egw_addressbook` inner join `egw_accounts` on `egw_addressbook`.`account_id` = `egw_accounts`.`account_id` where `egw_accounts`.`account_type` = 'u' and (" . $OwnerQuery . $AssistantQuery . "(`egw_accounts`.`account_primary_group` = -329 and (`egw_accounts`.`account_expires` = -1 or `egw_accounts`.`account_expires` > ".intval($_SERVER['REQUEST_TIME']) . "))) order by `egw_addressbook`.`n_family`, `egw_addressbook`.`n_given`, `egw_addressbook`.`n_middle`"); //-329
    $dentists = array();
    while ($row = GetNextRow($result))
    {
         $dentists[$row["account_id"]] = $row["n_fn"];
    }
    $smarty->assign('dentists', $dentists);
    $smarty->assign('assistants', array('0' => 'Brak') + $dentists);
    $CustomFields = array();
    $result = SendQuery("SELECT `config_value` FROM `egw_config` WHERE (`config_app` = 'calendar' OR `config_app` = 'bcalendar') AND `config_name` = 'customfields'");
    while ($row = GetNextRow($result))
    {
         $CustomFields = unserialize($row['config_value']);
    }

 

    uasort($CustomFields, 'Follows');
    $additional = '';
    foreach ($CustomFields as $fk=>$field)
    {
        $value = '';
        switch ($field['type'])
        {
            case 'select':
                $additional .= '<p><label class="ll" for="' . $fk . '">' . $field['label'] . ': </label><select id="' . $fk . 
                               '" name="_' . $fk . 
                               '">';
                foreach ($field['values'] as $value=>$name)
                {
                    if (($id > 0 && $cfv[$fk] == $value) || (!($id > 0) && (trim($value) == '' || trim($name) == '')))
                    {
                        $additional .= '<option value="' . $value . '" selected="selected">' . $name . '</option>';
                    }
                    else
                    {
                        $additional .= '<option value="' . $value . '">' . $name . '</option>';
                    }
                }
                $additional .= '</select></p>';
                break;
            case 'checkbox':
                if ($id > 0 && $cfv[$fk] == '1')
                {
                    $value = ' checked="checked"';
                }
                $additional .= '<label for="' . $fk . '" class="cc"><input type="checkbox" id="' . $fk . 
                               '" name="_' . $fk . 
                               '"' . $value . '/>' . $field['label'] . '</label>';
                break;
            case 'date':
                if ($id > 0)
                {
                    $value = ' value="' . $cfv[$fk] . '"';
                }
                $additional .= '<p><label class="ll" for="' . $fk . '">' . $field['label'] . 
                               ': </label></p><p class="dpp"><input type="text" id="' . $fk . 
                               '" name="_' . $fk . '"' . $value . ' class="date-pick"/></p><p>&nbsp;</p>';
                break;
            default:
                if ($id > 0)
                {
                    $value = ' value="' . $cfv[$fk] . '"';
                }
                $additional .= '<p><label class="ll" for="' . $fk . '">' . $field['label'] . ': </label><input type="' . $field['type'] . 
                               '" id="' . $fk . '" name="_' . $fk . '"' . $value . '/></p>';
        }
    }
    $smarty->assign('additional', $additional);
    $result = SendQuery("SELECT `cat_id`, `cat_name`, `cat_appname` FROM `egw_categories` WHERE `cat_appname` = 'calendar' OR `cat_appname` = 'bcalendar' OR `cat_appname` = 'phpgw'");
    $categories[''] = "Brak";
    while ($row = GetNextRow($result))
    {
         $categories[$row['cat_id']] = $row['cat_name'] . ' - ' . $row['cat_appname'];
    }
    $smarty->assign('categories', $categories);
    $result = SendQuery("SELECT `commerce_product`.title, `commerce_product`.product_id, `field_data_commerce_price`.commerce_price_amount, `field_data_field_koszty_00cznie`.`field_koszty_00cznie_amount`, `field_data_field_koszty_technika`.`field_koszty_technika_amount`, `taxonomy_term_data`.`name` FROM Bluedental.`commerce_product`
LEFT JOIN Bluedental.`field_data_commerce_price` ON `commerce_product`.product_id = Bluedental.`field_data_commerce_price`.entity_id 
LEFT JOIN Bluedental.`field_data_field_koszty_00cznie` ON `commerce_product`.product_id = `field_data_field_koszty_00cznie`.entity_id
LEFT JOIN Bluedental.`field_data_field_koszty_technika` ON `commerce_product`.product_id = `field_data_field_koszty_technika`.entity_id
LEFT JOIN (Bluedental.`taxonomy_term_data`, Bluedental.`field_data_field_category`) ON (`commerce_product`.product_id = `field_data_field_category`.`entity_id` AND `taxonomy_term_data`.`tid` = `field_data_field_category`.`field_category_tid`)
WHERE Bluedental.`commerce_product`.`type` = 'visits'");
    while ($row = GetNextRow($result))
    {
         $products[] = $row;
  
    }
    $smarty->assign('products', $products);
    ob_start();
    include 'VideosList.php';
    $smarty->assign('videos', ob_get_clean());
}
$smarty->display('../templates/Event.tpl');
?>
