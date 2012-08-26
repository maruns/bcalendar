<?php
do
{
    session_start();
    if (!isset($_SESSION['IsInitiated']))
    {
            session_regenerate_id();
            $_SESSION['IsInitiated'] = true;
            $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
    }
    if($_SESSION['ip'] != $_SERVER['REMOTE_ADDR'])
    {
            $_SESSION = array();
            session_destroy();
            $SessionHijackingOccurred = true;
    }
    else
    {
        if(isset($_SESSION['user']))
        {
                
        }
        else
        {
            
        }
    }
}
while($SessionHijackingOccurred);
?>
