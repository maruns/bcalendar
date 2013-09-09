<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Wizyty pacjentów - wybór pacjenta</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link type="text/css" rel="stylesheet" href="../Windows.css" />
    <link rel="icon" type="image/png" href="../phpgwapi/templates/idots/images/PatientVisits.png"/>
</head>
<body id="pv">
    <div id="aa">
        <h1>Wizyty pacjentów</h1>
        <form action="index.php">
            <p>
                <label for="search">Zawęź listę do pacjentów zawierających w imieniu lub nazwisku ciąg znaków:</label>
                <input id="search" name="search"{if $search} value="{$search}"{/if} type="text" tabindex="1" />
                <input type="submit" value="Szukaj"/>
            </p>
        </form>      
        <p>{if $search}<input type="hidden" value="{$search}" />{/if}Kliknij na wierszu pacjenta, aby wyświetlić jego wizyty:</p>
        {if $patient}<div id="pc" class="ao">
            {html_table table_attr='id="pt"' loop=$patient tr_attr=$attr cols="Imię i nazwisko,Numer telefonu,PESEL"}
        </div>{/if}
    </div>
</body>
</html>
