<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Zdarzenia usunięte</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script type="text/javascript" src="../DatePicker/jquery.min.js"></script>
    <script type="text/javascript" src="../DatePicker/jquery.datePicker.js"></script>
    <script type="text/javascript" src="../DatePicker/date.js"></script>
    <script type="text/javascript" src="Script.js"></script>
    <link type="text/css" rel="stylesheet" href="../DatePicker/datePicker.css" />
    <link type="text/css" rel="stylesheet" href="../Windows.css" />
    <link rel="icon" type="image/png" href="../phpgwapi/templates/idots/images/DeletedEvents.png"/>
</head>
<body id="pv">
    <div id="aa">
        <h1>Zdarzenia usunięte</h1>
        <form action="index.php" id="def" name="def">
            <p>
                <label for="dentist">Właściciel zdarzenia: </label>
                {html_options options=$dentists selected=$owner id=owner name=owner}
                <label for="iad"><input type="checkbox" id="iad" name="iad" onchange="OnLDChange()"{$iad}/>Pokaż użytkowników nieaktualnych i nieaktywnych</label>
            </p> 
            <div id="desdd">
                <p><label>Od dnia: </label></p>
                <p><input type="text" id="from" name="from" class="date-pick" value="{$from}"/></p>
            </div>
            <div>
                <p id="sd"><label for="to">Do dnia: </label></p>
                <p><input type="text" id="to" name="to" class="date-pick" value="{$to}"/></p>
            </div>
            <p id="desp">
                <input type="submit" value="Pokaż zdarzenia"/>
            </p>
        </form>      
        {if $visits}
            <div id="dec" class="ao">
                {html_table loop=$visits cols="Tytuł,Opis,Właściciel,Pacjent,Data,Godzina,Załączniki"}
            </div>
            {else}
            {if $from && $to}
                <p>Brak zdarzeń spełniający kryteria</p>
                {else}
                <p>Proszę wybrać przedział, w którym zdarzenia miały się odbywać</p>
            {/if}
        {/if}    
    </div>
</body>
</html>
