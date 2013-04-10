<?php
$GLOBALS['egw_info'] = array(
    'flags' => array(
            'currentapp' => 'bcalendar',
            'noheader'   => True
    ),
);
require_once '../../DatabaseConnection.php';
$result = SendQuery("SELECT `field_description_value` FROM Bluedental.`field_data_field_description` WHERE `entity_id` = " . intval($_GET['id']));
while ($row = GetNextRow($result))
{
     echo $row['field_description_value'];
}
?>
