<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html  xmlns="http://www.w3.org/1999/xhtml" id="wh">
<head>
    <title>{if $name}Czas pracy dentysty {$name}{else}Czas pracy dentystów{/if}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script type="text/javascript" src="../../DataPicker/jquery.min.js"></script>
    <script type="text/javascript" src="../../DataPicker/jquery.datePicker.js"></script>
    <script type="text/javascript" src="../../DataPicker/date.js"></script>
    <script type="text/javascript" src="Script.js"></script>
    <link type="text/css" rel="stylesheet" href="../../DataPicker/datePicker.css" />
    <link type="text/css" rel="stylesheet" href="../../Windows.css" />
</head>
<body id="whb">
    {if $dentists}<ul id="al">{foreach $dentists as $dentist}<li>{$dentist}</li>{/foreach}</ul>{/if}
    <div id="wts">
        {if $name}
            <h1>Czas pracy dentysty {$name}</h1>
            <form method="post" action="index.php">
                <fieldset>
                    <legend>Poniedziałek</legend>
                    {foreach $Monday as $period}
                        <p>
                            Od <input type="text" name="SHEN1{$period['ID']}" maxlength="2" value="{$period['Start']|date_format:"%H"}"
                                      size="2"/>:
                            <input type="text" name="SMEN1{$period['ID']}" maxlength="2" value="{$period['Start']|date_format:"%M"}" size="2"/> 
                            do 
                            <input type="text" name="EHEN1{$period['ID']}" maxlength="2" value="{$period['End']|date_format:"%H"}" size="2"/>:
                            <input type="text" name="MEEN1{$period['ID']}" maxlength="2" value="{$period['End']|date_format:"%M"}" size="2"/>
                            <a href="?dentist={$did}&amp;rnp={$period['ID']}" title="Usuń"><img src="Remove.png" alt="Usuń"/></a>
                        </p>
                    {/foreach}
                    <p>Nowy przedział: od <input type="text" name="SHNN10" maxlength="2" size="2"/>:<input type="text" name="SMNN10" maxlength="2" size="2"/> do <input type="text" name="EHNN10" maxlength="2" size="2"/>:<input name="EMNN10" type="text" maxlength="2" size="2"/></p>
                </fieldset>
                <p>Wtorek:</p>
                <p>Środa:</p>
                <p>Czwartek:</p>
                <p>Piątek:</p>
                <p>Sobota:</p>
                <p>Niedziela:</p>
                <fieldset><legend>Terminy szczególne:</legend></fieldset>
                
                {foreach $dates as $date}
                <p><input type="text" id="date{$date['SpecialDateID']}" name="date-pick" class="date-pick" /></p>
                {foreach $date as $p}
                    <p>Od <input type="text" maxlength="2" value="{$period['Start']}" size="2"/> do <input type="text" maxlength="2" value="{$period['End']}" size="2"/></p>
                    {/foreach}
                    <p>Nowy przedział: od <input type="text" maxlength="2" value="{$period['Start']}" size="2"/> do <input type="text" maxlength="2" value="{$period['End']}" size="2"/></p>
                {/foreach}
                <p><input type="hidden" name="dentist" value="{$did}" /><input name="submit" type="submit" value="Zapisz" /></p>
            </form>
        {else}
            <p>Wybierz dentystę</p>
        {/if}
    </div>
</body>
</html>