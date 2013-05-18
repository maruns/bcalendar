<!DOCTYPE html PUBLIC "-//W3C//DD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
      <title>Zdarzenie</title>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <script type="text/javascript" src="../../DatePicker/jquery.min.js"></script>
      <script type="text/javascript" src="../../DatePicker/jquery.datePicker.js"></script>
      <script type="text/javascript" src="../../DatePicker/date.js"></script>
      <script type="text/javascript" src="../js/combobox-min.js"></script>
      <script type="text/javascript" src="../js/Event.js"></script>
      <script type="text/javascript" src="../../phpgwapi/js/jsapi/jsapi.js"></script>
      <link type="text/css" rel="stylesheet" href="../../DatePicker/datePicker.css" />
      <link type="text/css" rel="stylesheet" href="../../Windows.css" />
    </head>
    <body{if $OpenerShouldBeRefreshed || $WindowShouldBeClosed} onload="{if $OpenerShouldBeRefreshed}opener.location.href = opener.location.href + '&amp;msg=' + {$msg};{/if}{if $WindowShouldBeClosed}{if $StandardWindowShouldBeOpened}egw_openWindowCentered2('/egroupware/index.php?menuaction=bcalendar.bcalendar_uiforms.edit&{$OldQueryString}&cal_id={$id}','_blank',800,410,'yes');{/if}window.close();{/if}"{/if}>
        {if $products}<div id="multi-level">
            <ul class="menu">
                <li class="top">
                    <a class="top_link"><span class="span_top drop">Wstaw dane ze sklepu</span></a>
                    <ul class="sub">{foreach $products as $product}
                        <li><a onclick="InsertData('{$product['product_id']}', '{$product['title']}', '{$product['commerce_price_amount']}', '{$product['field_koszty_00cznie_amount']}', '{$product['field_koszty_technika_amount']}', '{$product['name']}')">
                                <span>{$product['title']}</span>
                        </a></li>
                    {/foreach}</ul>
                </li>
            </ul>
        </div>{/if}
        <form id="ef" action="Event.php" method="post" enctype="multipart/form-data">
            <p id="tp">
                <label class="ll" for="title">Tytuł: </label>
                <input onkeydown="OnTitleChange()" onchange="OnTitleChange()" type="text"{if $title} value="{$title}"{/if} id="title" name="title" />&nbsp;{if $id}#{$id}<input type="hidden" name="id" value="{$id}"/>{/if}
                {if $CurrentQueryString}
                    <input type="hidden" name="old_qs" value="{$CurrentQueryString}"/>
                    {else}
                    {if $OldQueryString}<input type="hidden" name="old_qs" value="{$OldQueryString}"/>{/if}
                {/if}
            </p>
            <p><label for="date" class="ll">Data: </label></p>
            <p id="dpp"><input type="text" id="date" name="date" class="date-pick"{if $start} value="{$start}"{/if} /></p>
            <p>&nbsp;{if $patient}<input type="hidden" name="old_patient" value="{$patient}"/>{/if}</p>
            <p>
                <label class="ll" for="sh">Rozpoczęcie: </label>
                <input type="text" id="sh" name="sh" maxlength="2" size="2"{if $hour} value="{$hour}"{/if} />
                <label for="sm">:</label>
                <input type="text" name="sm" id="sm" maxlength="2" size="2"{if $minute} value="{$minute}"{/if} />
            </p>
            <div class="combobox">
                <label class="ll" for="time">Czas trwania: </label> 
                <input type="text" id="time" name="time" value="{if $time}{$time}{else}30{/if}" />
                <span>▼</span>
                <div class="dropdownlist">
                    <a>5</a>
                    <a>10</a>
                    <a>15</a>
                    <a>30</a>
                    <a>45</a>
                    <a title="1 h">60</a>
                    <a title="1 h 30 min">90</a>
                    <a title="2 h">120</a>
                    <a title="2 h 30 min">150</a>
                    <a title="3 h">180</a>
                    <a title="3 h 30 min">210</a>
                    <a title="4 h">240</a>
                    <a title="4 h 30 min">270</a>
                    <a title="5 h">300</a>
                    <a title="5 h 30 min">330</a>
                    <a title="6 h">360</a>
                    <a title="6 h 30 min">390</a>
                    <a title="7 h">420</a>
                    <a title="7 h 30 min">450</a>
                    <a title="8 h">480</a>
                </div>
            </div>      
            <script type="text/javascript" charset="utf-8">
                var no = new ComboBox('time');
            </script>
            {*if $PatientCanBeChanged*}
            <p>
                
                <label for="lp" class="ll">Pacjent: </label>
                <input id="lp" type="text" name="lp" onchange="OnLPChange()" onkeydown="OnLPChange()" />
                <select id="ep" name="patient" onchange="OnPatientChange()">
                    <option value="0">Brak</option>
                    <option value="-1">Nowy</option>
                    {if $patient}<option value="{$patient}" selected="selected">{$pn}</option>{/if}
                </select>
                <label for="status">&nbsp;</label>
                {html_options id="status" name=status options=$so selected=$status}
            </p>
            <p id="nup">
                
                <label for="npn">Imię: </label>
                <input id="npn" type="text" name="npn" />
                <label for="nps">Nazwisko: </label>
                <input id="nps" type="text" name="nps" />
                <label for="phone">Pref. numer kom.: </label>
                <input id="phone" type="text" name="phone" />
                <label for="pesel">PESEL:</label>
                <input id="pesel" type="text" name="pesel" />
            </p>
            {*/if*}
            {*if $DentistCanBeChanged*}
                <p>
                    <label class="ll" for="dentist">Dentysta: </label>
                    {html_options id="dentist" name=dentist options=$dentists selected=$owner}
                    <label for="iad"><input type="checkbox" id="iad" name="iad" onchange="OnLDChange()"/>Pokaż innych użytkowników</label>
                    <label for="ld">Ogranicz do: </label>
                    <input id="ld" type="text" name="ld" onchange="OnLDChange()" onkeydown="OnLDChange()" />
                </p>
                <!--<p id="overlapping">&nbsp;</p>
                <p id="whi">&nbsp;</p>-->
            {*/if*}
            {*if $AssistantCanBeChanged*}<p>
                <label class="ll" for="assistant">Asystent: </label>
                {html_options id="assistant" name=assistant options=$assistants selected=$assistant}
                <label for="iaa"><input type="checkbox" id="iaa" name="iaa" onchange="OnLAChange()"/>Pokaż innych użytkowników</label>
                <label for="la">Ogranicz do: </label>
                <input id="la" type="text" name="la" onchange="OnLAChange()" onkeydown="OnLAChange()" />
                {if $assistant}<input type="hidden" name="old_assistant" value="{$assistant}"/>{/if}
            </p>{*/if*}
            <div id="atad">
                <p>
                    <label for="agreement">Zgoda pacjenta: </label>
                    {if $_id}
                        <a href="Agreement.php?date={$date}&amp;id={$id}&amp;pn={$pn}"
                           title="Pokaż zgodę pacjenta na zabieg przeznaczone do druku" target="_blank">Pokaż zgodę pacjenta</a>
                    {/if}
                </p>
                <p>
                    <textarea id="agreement" name="agreement" rows="4" >{$Agreement}</textarea>
                </p>
            </div>
            <div id="ptad">
                <p>
                    <label for="plan"{if $id} id="lbi"{/if}>Plan zabiegu: </label>
                    {if $_id}
                        <a title="Pokaż plan do druku" href="Plan.php?date={$date}&amp;id={$id}">
                            <img src="../templates/default/images/Plan.png" alt="Do druku" />
                        </a>
                    {/if}
                    <a id="rda" href="javascript:ReplaceDescription()">Zastąp opis planem ►</a>
                </p>
                <p>
                    <textarea id="plan" name="plan" rows="4" >{$Plan}</textarea>
                </p>
            </div>
            <div id="dtad">
                <p>
                    <label for="description">Opis: </label>
                    {if $id}
                        <a href="VisitInfo.php?date={$date}&amp;id={$id}"
                           title="Pokaż informacje o wizycie przeznaczone do druku" target="_blank">Pokaż informacje do druku</a>
                    {/if}
                </p>
                <p>
                    <textarea id="description" name="description" rows="4" >{$description}</textarea>
                </p>
            </div>
 
                

            <fieldset>
                <legend>Pola niestandardowe</legend>
                {$additional}
            </fieldset>
            <p>
                <label for="private">
                    <input class="ll" type="checkbox" id="private" name="private"{if $cal_public == '0'} checked="checked"{/if}/>Zdarzenie prywatne
                </label>
                <span id="cs">
                    <label for="category">Kategoria: </label>&nbsp;
                    {html_options id="category" name=category options=$categories selected=$SelectedCategory}
                </span>
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
            {if $id}<a id="erl" href="?remove={$id}">Usuń zdarzenie</a>{/if}
            </p>
        </form>
        
        <p id="vl">{$videos}</p>
    </body>
</html>