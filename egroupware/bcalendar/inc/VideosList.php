<?php
$GLOBALS['egw_info'] = array(
        'flags' => array(
                'currentapp' => 'bcalendar',
                'noheader'   => True
        ),
);
require_once '../../DatabaseConnection.php';
$dates = SendQuery('select `cal_start`, `cal_end` FROM `egw_cal_dates` where `cal_id` = ' . intval($_GET['cal_id']) . 
                   ' order by `cal_start`, `cal_end`');
while ($row = GetNextRow($dates))
{
    $days[] = date('Ymd', $row['cal_start']);
    $d[] = array('cal_start' => date('His', $row['cal_start']), 'cal_end' => date('His', $row['cal_end']));
    $ad[]= array('cal_start' => date('H-i-s', $row['cal_start']), 'cal_end' => date('H-i-s', $row['cal_end']));
}
$filenames = glob('/usr/local/apache2/htdocs/cam1/*'. $_GET['date'] . '*.{avi,mpeg},/usr/local/etc/Monitoring/*.{avi,mpeg},/tmp/motion/*.{avi,mpeg}', GLOB_BRACE);
if ($filenames === false)
{
    $filenames = array();
}
foreach ($filenames as $filename)
{
    foreach($days as $di)
    {
        if ($filename == $di . "-timelapse")
        {
            echo '<a target="_blank" class="ael" href="Video.php?file=' . $filename . '">'. $filename . '</a> ';
            $LinkIsAdded = true;
            break;
        }
    }
    if (!$LinkIsAdded)
    {
        $vd = substr($filename, -11, 6);
        foreach($d as $di)
        {
            if ($vd >= $di['cal_start'] && $vd <= $di['cal_end'])
            {
                echo '<a target="_blank" class="ael" href="Video.php?file=' . $filename . '">'. $filename . '</a> ';
                $LinkIsAdded = true;
                break;
            }
        }
    }
    if (!$LinkIsAdded)
    {
        $vd = substr($filename, -9, 8);
        foreach($ad as $di)
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
