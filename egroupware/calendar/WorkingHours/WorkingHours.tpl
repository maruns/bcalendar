<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html  xmlns="http://www.w3.org/1999/xhtml" id="wh">
<head>
    <title>{if $name}Czas pracy dentysty {$name}{else}Czas pracy dentystów{/if}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script type="text/javascript" src="../../DatePicker/jquery.min.js"></script>
    <script type="text/javascript" src="../../DatePicker/jquery.datePicker.js"></script>
    <script type="text/javascript" src="../../DatePicker/date.js"></script>
    <script type="text/javascript" src="Script.js"></script>
    <link type="text/css" rel="stylesheet" href="../../DatePicker/datePicker.css" />
    <link type="text/css" rel="stylesheet" href="../../Windows.css" />
    <link rel="icon" type="image/png" href="../../phpgwapi/templates/idots/images/WorkingHours.png"/>
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
                            Od <input type="text" name="SHEN{$period['ID']}" maxlength="2" value="{$period['Start']|date_format:"%H"}"
                                      size="2"/>:
                            <input type="text" name="SMEN{$period['ID']}" maxlength="2" value="{$period['Start']|date_format:"%M"}" 
                                   size="2"/> 
                            do 
                            <input type="text" name="EHEN{$period['ID']}" maxlength="2" value="{$period['End']|date_format:"%H"}" size="2"/>:
                            <input type="text" name="MEEN{$period['ID']}" maxlength="2" value="{$period['End']|date_format:"%M"}" size="2"/>
                            <a href="?dentist={$did}&amp;rnp={$period['ID']}" title="Usuń"><img src="Remove.png" alt="Usuń"/></a>
                        </p>
                    {/foreach}
                    <p>
                        Nowy przedział: od <input type="text" name="SHNN10" maxlength="2" size="2"/>:
                        <input type="text" name="SMNN10" maxlength="2" size="2"/>
                        do <input type="text" name="EHNN10" maxlength="2" size="2"/>:<input name="EMNN10" type="text" maxlength="2" size="2"/>
                    </p>
                </fieldset>
                <fieldset>
                    <legend>Wtorek</legend>
                    {foreach $Tuesday as $period}
                        <p>
                            Od <input type="text" name="SHEN{$period['ID']}" maxlength="2" value="{$period['Start']|date_format:"%H"}"
                                      size="2"/>:
                            <input type="text" name="SMEN{$period['ID']}" maxlength="2" value="{$period['Start']|date_format:"%M"}" 
                                   size="2"/> 
                            do 
                            <input type="text" name="EHEN{$period['ID']}" maxlength="2" value="{$period['End']|date_format:"%H"}" size="2"/>:
                            <input type="text" name="MEEN{$period['ID']}" maxlength="2" value="{$period['End']|date_format:"%M"}" size="2"/>
                            <a href="?dentist={$did}&amp;rnp={$period['ID']}" title="Usuń"><img src="Remove.png" alt="Usuń"/></a>
                        </p>
                    {/foreach}
                    <p>
                        Nowy przedział: od <input type="text" name="SHNN10" maxlength="2" size="2"/>:
                        <input type="text" name="SMNN20" maxlength="2" size="2"/>
                        do <input type="text" name="EHNN20" maxlength="2" size="2"/>:<input name="EMNN10" type="text" maxlength="2" size="2"/>
                    </p>
                </fieldset>
                <fieldset>
                    <legend>Środa</legend>
                    {foreach $Wednesday as $period}
                        <p>
                            Od <input type="text" name="SHEN{$period['ID']}" maxlength="2" value="{$period['Start']|date_format:"%H"}"
                                      size="2"/>:
                            <input type="text" name="SMEN{$period['ID']}" maxlength="2" value="{$period['Start']|date_format:"%M"}" 
                                   size="2"/> 
                            do 
                            <input type="text" name="EHEN{$period['ID']}" maxlength="2" value="{$period['End']|date_format:"%H"}" size="2"/>:
                            <input type="text" name="MEEN{$period['ID']}" maxlength="2" value="{$period['End']|date_format:"%M"}" size="2"/>
                            <a href="?dentist={$did}&amp;rnp={$period['ID']}" title="Usuń"><img src="Remove.png" alt="Usuń"/></a>
                        </p>
                    {/foreach}
                    <p>
                        Nowy przedział: od <input type="text" name="SHNN30" maxlength="2" size="2"/>:
                        <input type="text" name="SMNN30" maxlength="2" size="2"/>
                        do <input type="text" name="EHNN30" maxlength="2" size="2"/>:<input name="EMNN10" type="text" maxlength="2" size="2"/>
                    </p>
                </fieldset>
                <fieldset>
                    <legend>Czwartek</legend>
                    {foreach $Thursday as $period}
                        <p>
                            Od <input type="text" name="SHEN{$period['ID']}" maxlength="2" value="{$period['Start']|date_format:"%H"}"
                                      size="2"/>:
                            <input type="text" name="SMEN{$period['ID']}" maxlength="2" value="{$period['Start']|date_format:"%M"}" 
                                   size="2"/> 
                            do 
                            <input type="text" name="EHEN{$period['ID']}" maxlength="2" value="{$period['End']|date_format:"%H"}" size="2"/>:
                            <input type="text" name="MEEN{$period['ID']}" maxlength="2" value="{$period['End']|date_format:"%M"}" size="2"/>
                            <a href="?dentist={$did}&amp;rnp={$period['ID']}" title="Usuń"><img src="Remove.png" alt="Usuń"/></a>
                        </p>
                    {/foreach}
                    <p>
                        Nowy przedział: od <input type="text" name="SHNN40" maxlength="2" size="2"/>:
                        <input type="text" name="SMNN40" maxlength="2" size="2"/>
                        do <input type="text" name="EHNN40" maxlength="2" size="2"/>:<input name="EMNN10" type="text" maxlength="2" size="2"/>
                    </p>
                </fieldset>
                <fieldset>
                    <legend>Piątek</legend>
                    {foreach $Friday as $period}
                        <p>
                            Od <input type="text" name="SHEN{$period['ID']}" maxlength="2" value="{$period['Start']|date_format:"%H"}"
                                      size="2"/>:
                            <input type="text" name="SMEN{$period['ID']}" maxlength="2" value="{$period['Start']|date_format:"%M"}" 
                                   size="2"/> 
                            do 
                            <input type="text" name="EHEN{$period['ID']}" maxlength="2" value="{$period['End']|date_format:"%H"}" size="2"/>:
                            <input type="text" name="MEEN{$period['ID']}" maxlength="2" value="{$period['End']|date_format:"%M"}" size="2"/>
                            <a href="?dentist={$did}&amp;rnp={$period['ID']}" title="Usuń"><img src="Remove.png" alt="Usuń"/></a>
                        </p>
                    {/foreach}
                    <p>
                        Nowy przedział: od <input type="text" name="SHNN10" maxlength="2" size="2"/>:
                        <input type="text" name="SMNN50" maxlength="2" size="2"/>
                        do <input type="text" name="EHNN50" maxlength="2" size="2"/>:<input name="EMNN10" type="text" maxlength="2" size="2"/>
                    </p>
                </fieldset>
                <fieldset>
                    <legend>Sobota</legend>
                    {foreach $Saturday as $period}
                        <p>
                            Od <input type="text" name="SHEN{$period['ID']}" maxlength="2" value="{$period['Start']|date_format:"%H"}"
                                      size="2"/>:
                            <input type="text" name="SMEN{$period['ID']}" maxlength="2" value="{$period['Start']|date_format:"%M"}" 
                                   size="2"/> 
                            do 
                            <input type="text" name="EHEN{$period['ID']}" maxlength="2" value="{$period['End']|date_format:"%H"}" size="2"/>:
                            <input type="text" name="MEEN{$period['ID']}" maxlength="2" value="{$period['End']|date_format:"%M"}" size="2"/>
                            <a href="?dentist={$did}&amp;rnp={$period['ID']}" title="Usuń"><img src="Remove.png" alt="Usuń"/></a>
                        </p>
                    {/foreach}
                    <p>
                        Nowy przedział: od <input type="text" name="SHNN10" maxlength="2" size="2"/>:
                        <input type="text" name="SMNN60" maxlength="2" size="2"/>
                        do <input type="text" name="EHNN60" maxlength="2" size="2"/>:<input name="EMNN10" type="text" maxlength="2" size="2"/>
                    </p>
                </fieldset>
                <fieldset>
                    <legend>Niedziela</legend>
                    {foreach $Sunday as $period}
                        <p>
                            Od <input type="text" name="SHEN{$period['ID']}" maxlength="2" value="{$period['Start']|date_format:"%H"}"
                                      size="2"/>:
                            <input type="text" name="SMEN{$period['ID']}" maxlength="2" value="{$period['Start']|date_format:"%M"}" 
                                   size="2"/> 
                            do 
                            <input type="text" name="EHEN{$period['ID']}" maxlength="2" value="{$period['End']|date_format:"%H"}" size="2"/>:
                            <input type="text" name="MEEN{$period['ID']}" maxlength="2" value="{$period['End']|date_format:"%M"}" size="2"/>
                            <a href="?dentist={$did}&amp;rnp={$period['ID']}" title="Usuń"><img src="Remove.png" alt="Usuń"/></a>
                        </p>
                    {/foreach}
                    <p>
                        Nowy przedział: od <input type="text" name="SHNN10" maxlength="2" size="2"/>:
                        <input type="text" name="SMNN70" maxlength="2" size="2"/>
                        do <input type="text" name="EHNN70" maxlength="2" size="2"/>:<input name="EMNN10" type="text" maxlength="2" size="2"/>
                    </p>
                </fieldset>
                <fieldset>
                    <legend>Terminy szczególne</legend>
                    {foreach $dates as $date}<fieldset>                        
                        <legend>
                            <input type="text" id="ED{$date[0]['SpecialDateID']}" name="ED{$date[0]['SpecialDateID']}" class="date-pick"
                                   value="{$date[0]['Day']}.{$date[0]['Month']}.{$date[0]['YearToShow']}"/>
                            <input id="CED{$date[0]['SpecialDateID']}" name="CED{$date[0]['SpecialDateID']}" 
                                   type="checkbox"{if !$date[0]['Year']} checked="checked"{/if}/>
                            <label for="CED{$date[0]['SpecialDateID']}" class="ad">Termin coroczny</label>
                            <a href="?dentist={$did}&amp;rd={$date[0]['SpecialDateID']}" title="Usuń"><img src="Remove.png" alt="Usuń"/></a>
                        </legend>
                        {foreach $date as $p}{if $p['Start']}<p>
                            Od <input type="text" name="SHES1{$p['ID']}" maxlength="2" value="{$p['Start']|date_format:"%H"}"
                                      size="2"/>:
                            <input type="text" name="SMES1{$p['ID']}" maxlength="2" value="{$p['Start']|date_format:"%M"}" size="2"/> 
                            do 
                            <input type="text" name="EHES1{$p['ID']}" maxlength="2" value="{$p['End']|date_format:"%H"}" size="2"/>:
                            <input type="text" name="MEES1{$p['ID']}" maxlength="2" value="{$p['End']|date_format:"%M"}" size="2"/>
                            <a href="?dentist={$did}&amp;rsp={$p['ID']}" title="Usuń"><img src="Remove.png" alt="Usuń"/></a>
                        </p>{/if}{/foreach}
                        <p>
                            Nowy przedział: od <input type="text" name="SHNS{$p['SpecialDateID']}" maxlength="2" size="2"/>:
                            <input type="text" name="SMNS{$p['SpecialDateID']}" maxlength="2" size="2"/>
                            do <input type="text" name="EHNS{$p['SpecialDateID']}" maxlength="2" size="2"/>:
                            <input name="EMNS{$p['SpecialDateID']}" type="text" maxlength="2" size="2"/>
                        </p>
                    </fieldset>{/foreach}
                    <p><label for="ND">Nowy termin:</label></p>
                    <p><input type="text" id="ND" name="ND" class="date-pick" /></p>
                    <input id="CND" name="CND" type="checkbox"/>
                    <label for="CND" class="ad">Termin coroczny</label>
                </fieldset>
                <p><input type="hidden" name="dentist" value="{$did}" /><input name="submit" type="submit" value="Zapisz" /></p>
            </form>
        {else}
            <p>Wybierz dentystę</p>
        {/if}
    </div>
</body>
</html>