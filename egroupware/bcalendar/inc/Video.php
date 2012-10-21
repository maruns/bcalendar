<?php
$GLOBALS['egw_info'] = array(
        'flags' => array(
                'currentapp' => 'calendar',
                'noheader'   => True
        ),
);
$file = '/usr/local/apache2/htdocs/cam1/';
$filenames = glob('/usr/local/apache2/htdocs/cam1/'. $username . '\*.jpg');
foreach ($filenames as $filename) {
    echo $filename ."\n";
}
header('Content-type: ' . mime_content_type($file));
header('Content-Disposition: attachment; filename="' . pathinfo('/www/htdocs/inc/lib.inc.php', PATHINFO_BASENAME) . '"');
readfile($file);
?>
