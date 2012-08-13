<?php
mysql_connect('localhost', 'egroupware');
mysql_select_db('egroupware');
mysql_query('SET NAMES utf8');
/**
 * Wysyła zapytanie do bazy danych
 *
 * @param string $query zapytanie w języku SQL
 * @return wynik zapytania
 */
function SendQuery($query)
{
    return mysql_query($query);
}
/**
 * Zwraca następny wiersz
 *
 * @param $result wynik zapytania do bazy danych
 * @return wiersz
 */
function GetNextRow($result)
{
    return mysql_fetch_array($result, MYSQL_ASSOC);
}
/**
 * Dodaje ukośniki przed znaki specjalnymi w celu bezpiecznego użycia łańcucha w zapytaniu
 *
 * @param $string łańcuch
 * @return bezpieczny łańcuch
 */
function EscapeSpecialCharacters($string)
{
    return mysql_escape_string(stripslashes($string));
}
/**
 * Zamyka połączenie z bazą danych
 *
 */
function CloseConnection()
{
    mysql_close();
}
?>
