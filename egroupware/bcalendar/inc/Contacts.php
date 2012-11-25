<?php
$GLOBALS['egw_info'] = array(
        'flags' => array(
                'currentapp' => 'calendar',
                'noheader'   => True
        ),
);
require_once('../../DatabaseConnection.php');
$search = EscapeSpecialCharacters($_GET['search']);
$result = SendQuery("SELECT `n_family`, `n_given` FROM `egw_addressbook` WHERE `n_family` like '%".$search
                    ."%' OR `n_given` like '%".$search
                    ."%' order by `n_family`, `n_given` LIMIT 5");
if (GetRowsNumber($result) > 0)
{echo $_GET['left'];
    echo '<ul id="ch" style="top:' . $_GET['top'] . 'px;left:' . $_GET['left'] . 'px">';
    while ($row = GetNextRow($result))
    {
        if ($row['n_family'] != '' && $row['n_family'] != null && $row['n_given'] != '' && $row['n_given'] != null)
        {
            $separator = ', ';
        }
        else
        {
            $separator  = '';
        }
        echo '<li onclick="(elem=document.getElementById(\'ch\')).parentNode.removeChild(elem);document.getElementById(\'exec[participants][resource][query]\').value=\''.$row['n_family'].$separator.
             $row['n_given'].'\'">'.$row['n_family'].$separator.$row['n_given'].'</li>';
    }
    echo '</ul>';
}
CloseConnection();
?>
