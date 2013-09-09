<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html  xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Wizyty pacjenta {$name}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link type="text/css" rel="stylesheet" href="../Windows.css" />
    <link type="text/css" rel="stylesheet" href="PrintStyleSheet.css" media="print" />
    <link rel="icon" type="image/png" href="../phpgwapi/templates/idots/images/PatientVisits.png"/>
</head>
<body id="pv">
    <div id="aa">
        <h1>Wizyty pacjenta {$name}</h1>
        <form id="psf" action="PatientVisits.php?{$query}">
            <p>
                <a href="PatientVisits.php?{$query}" title="Załaduj stronę ponownie" id="refresh"><input type="button" value="Odświesz"/></a>
                <input type="button" onclick="window.print();" value="Drukuj"/>
            </p>
        </form>
        {if $visits}
            <div id="vc" class="ao">
                {html_table loop=$visits tr_attr=$attr cols="Nazwa wizyty,Opis,Dentysta,Data,Rozpoczęcie,Zakończenie,Załączniki"}
            </div>
        {else}
            <p>Brak wizyt</p>
        {/if}
    </div>
</body>
</html>
