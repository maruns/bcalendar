<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html  xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Wizyty pacjentów</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link type="text/css" rel="stylesheet" href="../Windows.css" />
</head>
<body id="pv" onload="document.getElementById('{$focus}').focus()">
    <h1>Wizyty pacjentów</h1>
    <form action="index.php">
        <p>
            <label for="search">Zawęź listę do pacjentów zawierających w imieniu lub nazwisku ciąg znaków:</label>
            <input id="search" name="search"{if $search} value="{$search}"{/if} type="text" />
            <input type="submit" value="Szukaj"/>
        </p>
    </form>
    <form id="psf" action="index.php">       
        <p>{if $search}<input type="hidden" value="{$search}" />{/if}<label for="patient">Pacjent: </label></p>
        <p>{html_options onchange="document.getElementById('psf').submit();" selected=$sp options=$patient id=patient size="4" name=patient}</p>
        {if $sp}<p><input type="submit" value="Odświesz"/></p>{/if}
    </form>
    {if $sp}
        {if $visits}
            <div id="vc">
                {html_table loop=$visits cols="Nazwa wizyty, Opis, Dentysta, Data, Rozpoczęcie, Zakończenie"}
            </div>
        {else}
            <p>Brak wizyt</p>
        {/if}
     {/if}
</body>
</html>
