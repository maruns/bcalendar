<?php
/*require_once('header.inc.php');*/
$host = $GLOBALS['egw_domain']['default']['db_host'];$host = "localhost";
$username = $GLOBALS['egw_domain']['default']['db_user'];$username = 'root';
$passwd = $GLOBALS['egw_domain']['default']['db_pass'];$passwd = null;
$dbname = $GLOBALS['egw_domain']['default']['db_name'];$dbname = 'egroupware';
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
 * Zamyka połączenie z bazą danych
 *
 */
function CloseConnection()
{
    //mysql_close();
    global $mysqli;
    $mysqli->close();
}
?>
