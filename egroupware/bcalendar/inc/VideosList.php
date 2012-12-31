<?php
$GLOBALS['egw_info'] = array(
        'flags' => array(
                'currentapp' => 'bcalendar',
                'noheader'   => True
        ),
);
require '../../DatabaseConnection.php';
$dates = SendQuery('select `cal_start`, `cal_end` FROM `egw_cal_dates` where `cal_id` = ' . intval($_GET['id']) . 
                   ' order by `cal_start`, `cal_end`');
while ($row = GetNextRow($dates))
{
    $d[] = array('cal_start' => date('His', $row['cal_start']), 'cal_end' => date('His', $row['cal_end']));
}
$filenames = glob('/usr/local/apache2/htdocs/cam1/*'. $_GET['date'] . '*.mpeg');
foreach ($filenames as $filename)
{
    $vd = substr($filename, -11, 6);
    if ($vd == 'elapse')
    {
        echo '<a target="_blank" href="Video.php?file=' . $filename . '">'. $filename . '</a> ';
    }
    else
    {
        foreach($d as $di)
        {
            if ($vd >= $di['cal_start'] && $vd <= $di['cal_end'])
            {
                echo '<a target="_blank" class="ael" href="Video.php?file=' . $filename . '">'. $filename . '</a> ';
                break;
            }
        }
    }
}
CloseConnection();
?>
