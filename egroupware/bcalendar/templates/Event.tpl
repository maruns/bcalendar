<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
      <title>Zdarzenie</title>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <script type="text/javascript" src="../../DatePicker/jquery.min.js"></script>
      <script type="text/javascript" src="../../DatePicker/jquery.datePicker.js"></script>
      <script type="text/javascript" src="../../DatePicker/date.js"></script>
      <script type="text/javascript" src="../js/Event.js"></script>
      <link type="text/css" rel="stylesheet" href="../../DatePicker/datePicker.css" />
      <link type="text/css" rel="stylesheet" href="../../Windows.css" />
    </head>
    <body>
        <form id="ef" name="ef" action="Event.php" method="POST" enctype="multipart/form-data">
            <p><label for="title">Tytuł: </label><input type="text" name="title" />&nbsp;{if $id}#{$id}{/if}</p>
            <p><label for="start">Data: </label></p>
            <p><input type="text" id="date" name="date" class="date-pick" /></p><p>&nbsp;</p>
            <p><label for="start">Rozpoczęcie: <input type="text" name="title" />:<input type="text" name="title" /></label></p>
            <p><label for="time">Czas trwania: </label><input type="text" name="time" /></p>
            {if $PatientCanBeChanged}<p>
                <label for="lp">Pacjent: </label>
                <input id="lp" type="text" name="lp" />
                <select id="patient" name="patient"><option></option></select>             
            </p>{/if}
            {if $DentistCanBeChanged}
                <p>
                    <label for="dentist">Dentysta: </label>
                    {html_options id="dentist" name=dentist options=$dentists}
                    <input type="checkbox" id="iad" name="iad"><label for="iad">Pokaż nieaktywnych</label></input>
                    <label for="ld">Ogranicz do: </label>
                    <input id="ld" type="text" name="ld" onchange="OnLDChange()" />
                </p>
                <p id="overlapping">&nbsp;<p>
                <p id="whi">&nbsp;</p>
            {/if}
            {if $AssistantCanBeChanged}<p>
                <label for="assistant">Asystent: </label>
                {html_options id="assistant" name=assistant options=$dentists}
                <input type="checkbox" id="iaa" name="iaa"><label for="iaa">Pokaż nieaktywnych</label></input>
                <label for="la">Ogranicz do: </label>
                <input id="la" type="text" name="la" onchange="OnLAChange()" />
            </p>{/if}
            <p><label for="description">Opis: </label><textarea name="description" rows="4" cols="20"></textarea></p>
            <p><input type="checkbox" id="private" name="private"><label for="private">Zdarzenie prywatne</label></input></p>
            <p>
                <label for="repetition">Powtarzane </label>
                <select id="repetition" name="repetition" onchange="EnableRepetition()">
                    <option value="no">Nie</option>
                    <option value="daily">coddziennie</option>
                    <option value="weekly">co tydzień</option>
                    <option value="monthly">co miesiąc</option>
                    <option value="yearly">co rok</option>        
                </select>
                <label for="repetition"> z odstępami </label>
                <input type="text" name="interval" id="disabled" value="0" disabled="disabled" />
                <label for="re"> do </label>
                <input type="text" id="re" name="re" class="date-pick" disabled="disabled" />
                <input type="checkbox" id="monday" name="monday" checked="checked" disabled="disabled"><label for="monday">Pn</label></input>
                <input type="checkbox" id="tuesday" name="tuesday" checked="checked" disabled="disabled"><label for="tuesday">Wt</label></input>
                <input type="checkbox" id="wednesday" name="wednesday" checked="checked" disabled="disabled">
                    <label for="wednesday">Śr</label>
                </input>
                <input type="checkbox" id="thursday" name="thursday" checked="checked" disabled="disabled">
                    <label for="thursday">Cz</label>
                </input>
                <input type="checkbox" id="friday" name="friday" checked="checked" disabled="disabled"><label for="friday">Pt</label></input>
                <input type="checkbox" id="saturday" name="saturday" checked="checked" disabled="disabled">
                    <label for="saturday">Sb</label>
                </input>
                <input type="checkbox" id="sunday" name="private" checked="checked" disabled="disabled"><label for="sunday">Nd</label></input>
                <input type="checkbox" id="odwh" name="odwh" disabled="disabled"><label for="odwh">Tylko w czasie pracy</label></input>
            </p>
            <p>
                <input type="submit" value="OK" name="ok" />
                <input type="submit" value="Zastosuj" name="apply" />
                <input type="submit" title="Wyświetl okno zaawansowane edycji zdarzeń" value="Więcej{$loss}" name="more" />
            </p>
        </form>
        {if $id}<p>
            <a href="VisitInfo.php?date={$date}&amp;id={$id}"
               title="Pokaż informacje o wizycie przeznaczone do druku">Pokaż informacje do druku</a>
        </p>{/if}
        <p>{$videos}</p>
    </body>
</html>