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
    <body {if $rShouldBeRefreshed}onload="{if $WindowShouldBeClosed}window.close();{/if}"{/if}>
        <form id="ef" action="Event.php" method="post" enctype="multipart/form-data">
            <p>
                <label class="ll" for="title">Tytuł: </label>
                <input type="text"{if $title}value="{$title}"{/if} id="title" name="title" />&nbsp;{if $id}#{$id}<input type="hidden" name="id" value="{$id}"/>{/if}
            </p>
            <p><label class="ll" for="date">Data: </label></p>
            <p><input type="text" id="date" name="date" class="date-pick"{if $start} value="{$start}"{/if} /></p>
            <p>&nbsp;</p>
            <p>
                <label class="ll" for="sh">Rozpoczęcie: </label>
                <input type="text" id="sh" name="sh" maxlength="2" size="2"{if $hour} value={$hour}{/if} />
                <label for="sm">:</label>
                <input type="text" name="sm" id="sm" maxlength="2" size="2"{if $minute} value={$minute}{/if} />
            </p>
            <p><label class="ll" for="time">Czas trwania: </label> <input type="text" id="time" name="time" /> min</p>
            {if $PatientCanBeChanged}<p>
                <label class="ll" for="lp">Pacjent: </label>
                <input id="lp" type="text" name="lp" onkeydown="OnLPChange()" />
                <select id="ep" name="patient"><option value="0">Brak</option></select>             
            </p>{/if}
            {if $DentistCanBeChanged}
                <p>
                    <label class="ll" for="dentist">Dentysta: </label>
                    {html_options id="dentist" name=dentist options=$dentists selected=$owner}
                    <label for="iad"><input type="checkbox" id="iad" name="iad" onchange="OnLDChange()"/>Pokaż nieaktywnych</label>
                    <label for="ld">Ogranicz do: </label>
                    <input id="ld" type="text" name="ld" onkeydown="OnLDChange()" />
                </p>
                <!--<p id="overlapping">&nbsp;</p>
                <p id="whi">&nbsp;</p>-->
            {/if}
            {if $AssistantCanBeChanged}<p>
                <label class="ll" for="assistant">Asystent: </label>
                {html_options id="assistant" name=assistant options=$assistants}
                <label for="iaa"><input type="checkbox" id="iaa" name="iaa" onchange="OnLAChange()"/>Pokaż nieaktywnych</label>
                <label for="la">Ogranicz do: </label>
                <input id="la" type="text" name="la" onkeydown="OnLAChange()" />
            </p>{/if}
            <p id="dp">
                <label class="ll" for="description">Opis: </label>
                <textarea id="description" name="description"{if $description}value="{$description}"{/if} rows="4" cols="20"></textarea>
            </p>
            <p>
                <label for="private">
                    <input class="ll" type="checkbox" id="private" name="private"{if $cal_public == '0'} checked="checked"{/if}/>Zdarzenie prywatne
                </label>
            </p>
            <fieldset>
                <legend>Pola niestandardowe</legend>
                {$additional}
            </fieldset>
            <p>
                <label class="ll" for="category">Kategoria: </label>
                {html_options id="category" name=category selected=$SelectedCategory options=$categories}
            </p>
            <!--<fieldset>
                <legend>Powtarzanie</legend>
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
                 </p>
                 <p>
                    <label for="re"> do </label>
                 </p>
                 <p>
                    <input type="text" id="re" name="re" class="date-pick" disabled="disabled" />
                 </p>
                <p>&nbsp;</p>
                 <p>
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
                
            </fieldset>-->
            <p>
                <input type="submit" value="OK" name="ok" />
                <input type="submit" value="Zastosuj" name="apply" />
                <input type="submit" title="Wyświetl okno zaawansowane edycji zdarzeń" value="Więcej{$loss}" name="more" />
                {if $id}<a id="erl" href="?remove={$id}"><button>Usuń zdarzenie</button></a>{/if}
            </p>
        </form>
        {if $id}<p>
            <a href="VisitInfo.php?date={$date}&amp;id={$id}"
               title="Pokaż informacje o wizycie przeznaczone do druku">Pokaż informacje do druku</a>
        </p>{/if}
        <p>{$videos}</p>
    </body>
</html>