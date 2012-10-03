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
                    ."%' order by `n_family`, `n_given`");
if (GetRowsNumber($result) > 0)
{
    echo '<ul>';
    while ($row = GetNextRow($result))
    {
        echo '<li>'.$row['n_family'].', '.$row['n_given'].'</li>';
    }
    echo '</ul>';
}
CloseConnection();
?>
