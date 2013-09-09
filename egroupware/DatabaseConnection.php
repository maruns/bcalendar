<?php
require_once('header.inc.php');
$host = $GLOBALS['egw_domain']['default']['db_host'];//$host = "localhost";
$username = $GLOBALS['egw_domain']['default']['db_user'];//$username = 'root';
$passwd = $GLOBALS['egw_domain']['default']['db_pass'];//$passwd = null;
$dbname = $GLOBALS['egw_domain']['default']['db_name'];//$dbname = 'egroupware';
if ($passwd == '')
{
    $passwd = null;
}
//mysql_connect($host, $username, $passwd);
//mysql_select_db($dbname);
$mysqli = new mysqli($host, $username, $passwd, $dbname);
//mysql_query('SET NAMES utf8');
$mysqli->real_query('SET NAMES utf8');
/**
 * Wysyła szybko zapytanie do bazy danych
 *
 * @param string $queries zapytanie w języku SQL
 */
function SendQueryQuickly($query)
{
    global $mysqli;
    return $mysqli->real_query($query);
}
/**
 * Wysyła zapytania do bazy danych
 *
 * @param string $queries zapytania w języku SQL oddzielone średnikiem
 */
function SendQueries($queries)
{
    global $mysqli;
    $mysqli->multi_query($queries);
    while($mysqli->next_result());
}
/**
 * Wysyła zapytanie do bazy danych
 *
 * @param string $query zapytanie w języku SQL
 * @return mixed wynik zapytania
 */
function SendQuery($query)
{
    //return mysql_query($query);
    global $mysqli;
    return $mysqli->query($query);
}
/**
 * Przygotowuje zapytanie SQL
 *
 * @param string $query zapytanie w języku SQL
 * @return mixed przygotowane zapytanie
 */
function PrepareStatement($query)
{
    global $mysqli;
    return $mysqli->prepare($query);
}
/**
 * Pobiera wynik z przygotowanego zapytania
 *
 * @param object $statement wykonane przygotowane zapytanie
 * @return mixed wynik zapytania
 */
function GetResult($statement)
{
    return $statement->get_result();
}
/**
 * Sprawdza czy wynik może być pobrany z przygotowanego zapytania jako tablica
 *
 * @return bool czy wynik może być pobrany z przygotowanego zapytania jako tablica
 */
function ResultCanBeGot()
{
    return function_exists('mysqli_stmt_get_result');
}
/**
 * Zapisuje wynik z następnego wiersza z przygotowanego zapytania do zmiennych związanych
 *
 * @param object $statement wykonane przygotowane zapytanie
 * @return bool czy został pobrany wiersz
 */
function Fetch($statement)
{
    return $statement->fetch();
}
/**
 * Zwraca następny wiersz
 *
 * @param object $result wynik zapytania do bazy danych
 * @return mixed wiersz
 */
function GetNextRow($result)
{
    //return mysql_fetch_array($result, MYSQL_ASSOC);
    if (!is_object($result))
    {
        return null;
    }
    return $result->fetch_array(MYSQLI_ASSOC);
}
/**
 * Zwraca liczbę wierszy w wyniku
 *
 * @param object $result wynik zapytania do bazy danych
 * @return int liczba wierszy
 */
function GetRowsNumber($result)
{
    if (!is_object($result))
    {
        return 0;
    }
    return $result->field_count;
}
/**
 * Dodaje ukośniki przed znakami specjalnymi w celu bezpiecznego użycia łańcucha w zapytaniu
 *
 * @param string łańcuch
 * @return string bezpieczny łańcuch
 */
function EscapeSpecialCharacters($string)
{
    //return mysql_escape_string(stripslashes($string)); 
    global $mysqli;
    return $mysqli->escape_string(stripslashes($string));
}
/**
 * Rozpoczyna transakcje
 *
 */
function BeginTransaction()
{
    global $mysqli;
    $mysqli->autocommit(false);
    //$mysqli->begin_transaction();
    SendQueryQuickly('START TRANSACTION');
}
/**
 * Zatwierdza bieżącą transakcje
 *
 */
function Commit()
{
    global $mysqli;
    $mysqli->commit();
    $mysqli->autocommit(true);
}
/**
 * Zamyka połączenie z bazą danych
 *
 */
function CloseConnection()
{
    //mysql_close();
    global $mysqli;
    $mysqli->close();
}
/**
 * Zwraca hiperłącze do pliku
 *
 * @param string $file nazwa pliku
 * @param int $cal_id identyfikator wizyty
 * @param string $comment komentarz do pliku
 * @return string hiperłącze
 */
function ShowLinkToFile($file, $cal_id, $comment)
{
    $ending = strtolower(substr($file, -4));
    switch($ending)
    {
        case '.png':
        case '.jpg':
        case 'jpeg':
        case '.gif':
        case '.bmp':
            $icon = '"><img src="../etemplate/thumbnail.php?path=%2Fapps%2Fcalendar%2F70%2F' . urlencode($file);
            break;                       
        case '.doc':
        case '.dot':
        case 'docx':
        case 'docm':
        case 'dotx':
        case 'dotm':
            $icon = '"><img src="../etemplate/templates/default/images/mime16_application_msword.gif';
            break;
        case '.bin':
        case '.dms':
        case '.exe':
        case '.lha':
        case '.lzh':
            $icon = '"><img src="../etemplate/templates/default/images/mime16_application_octet-stream.gif';
            break;
        case '.pdf':
            $icon = '"><img src="../etemplate/templates/default/images/mime16_application_pdf.gif';
            break;
        case '.eps':
            $icon = '"><img src="../etemplate/templates/default/images/mime16_application_postscript.png';
            break;
        case '.rtf':
            $icon = '"><img src="../etemplate/templates/default/images/mime16_application_rtf.png';
            break;
        case '.xls':
        case '.xst':
        case '.xlm':
        case 'xlsx':
        case 'xlsm':
        case 'xltx':
        case 'xltm':       
        case 'xlsb':
        case '.xlw':
            $icon = '"><img src="../etemplate/templates/default/images/mime16_application_vnd.ms-excel.gif';
            break; 
        case '.ppt':
        case '.pps':
        case 'pptx':
        case 'ppam':
        case 'ppsx':
        case 'pptm':       
        case 'ppsm':
        case '.ppa':
            $icon = '"><img src="../etemplate/templates/default/images/mime16_application_vnd.ms-powerpoint.gif';
            break; 
        case '.odb':
            $icon = '"><img src="../etemplate/templates/default/images/mime16_application_vnd.oasis.opendocument.database.gif';
            break;
        case '.odg':
        case 'fodg':
            $icon = '"><img src="../etemplate/templates/default/images/mime16_application_vnd.oasis.opendocument.graphics.gif';
            break;
        case '.odp':
        case 'fodp':
            $icon = '"><img src="../etemplate/templates/default/images/mime16_application_vnd.oasis.opendocument.presentation.gif';
            break;
        case '.ods':
        case 'fods':
            $icon = '"><img src="../etemplate/templates/default/images/mime16_application_vnd.oasis.opendocument.spreadsheet.gif';
            break;
        case '.odt':
        case 'fodt':
            $icon = '"><img src="../etemplate/templates/default/images/mime16_application_vnd.oasis.opendocument.text.gif';
            break;
        case 'gtar':
            $icon = '"><img src="../etemplate/templates/default/images/mime16_application_x-gtar.png';
            break;
        case '.php':
        case 'php5':
            $icon = '"><img src="../etemplate/templates/default/images/mime16_application_x-httpd-php.png';
            break;
        case '.tar':
            $icon = '"><img src="../etemplate/templates/default/images/mime16_application_x-tar.png';
            break;
        case '.zip':
            $icon = '"><img src="../etemplate/templates/default/images/mime16_application_zip.gif';
            break;
        case '.aif':
        case 'aifc':
        case 'aiff':
        case '.wav':
        case '.mid':
        case '.mp3':
        case '.ram':       
        case '.rmi':
        case '.snd':
        case '.wma':
            $icon = '"><img src="../etemplate/templates/default/images/mime16_audio.png';
            break;
        case '.cmx':
        case '.cod':
        case '.ico':
        case '.ief':
        case 'jfif':
        case '.pbm':
        case '.pgm':
        case '.pnm':
        case '.ppm':
        case '.ras':
        case '.rgb':
        case '.svg':
        case '.tif':
        case 'tiff':
        case '.xbm':
        case '.xpm':
        case '.xwd':
            $icon = '"><img src="../etemplate/templates/default/images/mime16_image.png';
            break;
        case 'jpe':
            $icon = '"><img src="../etemplate/templates/default/images/mime16_image_jpeg.gif';
            break;
        case '.323':
        case '.bas':
        case '.css':
        case '.etx':
        case '.htc':
        case '.htt':       
        case '.rtx':
        case '.txt':
            $icon = '"><img src="../etemplate/templates/default/images/mime16_text.png';
            break;
        case 'ical':
        case '.ics':
        case '.ifb':
            $icon = '"><img src="../etemplate/templates/default/images/mime16_text_calendar.png';
            break;
        case '.htm':
        case 'html':
        case '.stm':
            $icon = '"><img src="../etemplate/templates/default/images/mime16_text_html.png';
            break;
        case '.vcf':
            $icon = '"><img src="../etemplate/templates/default/images/mime16_text_x-vcard.png';
            break;
        case '.asf':
        case '.asr':
        case '.asx':
        case '.avi':
        case '.lsf':
        case '.lsx':
        case '.mp2':
        case '.mpa':
        case '.mpe':
        case 'mpeg':
        case '.mpg':
        case 'mpv2':
        case '.wmv':
        case 'rmvb':
            $icon = '"><img src="../etemplate/templates/default/images/mime16_video.png';
            break;
        default:
            switch (substr($ending, -3))
            {
                case '.ai':
                case '.ps':
                    $icon = '"><img src="../etemplate/templates/default/images/mime16_application_postscript.png';
                    break;
                case '.sh':
                    $icon = '"><img src="../etemplate/templates/default/images/mime16_application_x-sh.png';
                    break;
                case '.au':
                case '.ra':
                    $icon = '"><img src="../etemplate/templates/default/images/mime16_audio.png';
                    break;
                default:
                    $LongerEnding = strtolower(substr($file, -10));
                    if ($LongerEnding == '.icalendar')
                    {
                        $icon = '"><img src="../etemplate/templates/default/images/mime16_text_calendar.png';
                        break;
                    }
                    if (substr($LongerEnding, -6) == '.movie')
                    {
                        $icon = '"><img src="../etemplate/templates/default/images/mime16_video.png';
                        break;
                    }
                    $icon = '"><img src="../etemplate/templates/default/images/mime16_unknown.png';
            }
    }
    if ($comment)
    {
        $alt = $comment;
        $title = $comment . ' - ';
    }
    else
    {
        $alt = $file;
        $title = '';
    }
    return '<a target="_blank" title="' . $title . $file .'" href="../webdav.php/apps/calendar/' . $cal_id . '/' . $file . $icon . '" alt="'
           . $alt . '"/></a>';
}
?>
