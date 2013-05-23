<?php
$GLOBALS['egw_info'] = array(
    'flags' => array(
            'currentapp' => 'bcalendar',
            'noheader'   => True
    ),
);
require_once '../../DatabaseConnection.php';
$result = SendQuery("SELECT `field_plan_value` FROM Bluedental.`field_data_field_plan` WHERE `entity_id` = " . intval($_GET['id']));
while ($row = GetNextRow($result))
{
     echo $row['field_plan_value'];
}
?>
