<?php
$GLOBALS['egw_info'] = array(
        'flags' => array(
                'currentapp' => 'bcalendar',
                'noheader'   => True
        ),
);
$file = '/usr/local/apache2/htdocs/cam1/' + $_GET['file'];
header('Content-type: ' . mime_content_type($file));
header('Content-Disposition: attachment; filename="' . $_GET['file'] . '"');
readfile($file);
?>
