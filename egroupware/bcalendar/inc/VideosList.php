<?php
$GLOBALS['egw_info'] = array(
        'flags' => array(
                'currentapp' => 'bcalendar',
                'noheader'   => True
        ),
);
$filenames = glob('/usr/local/apache2/htdocs/cam1/*'. $_GET['date'] . '*.mpeg');
foreach ($filenames as $filename)
{
    echo '<a target="_blank" href="Video.php?file=' . $filename . '">'. $filename . '</a>\n';
}
?>
