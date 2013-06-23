<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
      <title>Recepta dla pacjenta {$pn}</title>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <style type="text/css">
/*<![CDATA[*/
body {
    margin: 0;
}
table {
    font-family: "Times New Roman", Times, serif;
    width: 9cm;
    height: 30cm;
    border: black solid 2px;
    column-width: 100%;
    border-spacing: 0;
    border-collapse: collapse;
    empty-cells: show;
}
p {
    font-size: 8.4pt;
    color: black;
    margin: 0;
}
.sb {
    border-color: black;
    border-style: solid;
    border-width: 2px 2px 0 2px;
}
.hdb {
    border-color: black;
    border-top-style: solid;
    border-left-style: dashed;
    border-right-style: dashed;
    border-width: 2px 1px 1px 1px;
    height: 8mm;
}
.db {
    border: black dashed 1px;
    height: 1.5cm;
}
#fc {
    width: 53.33%;
}
#tc {
    width: 24.24%;
}
.bm {
    margin-bottom: 3mm;
}
.tm {
    margin-top: 3mm;
}
#ap strong {
    font-size: 5.5pt !important;
}
td:not(.db) {
    vertical-align: top;
}
.sbcb {
    vertical-align: bottom !important;
    border-color: black;
    border-style: solid;
    border-width: 0 2px 2px 2px;
}
.dh {
    text-align: center;
}
/*]]>*/
      </style>
    </head>
    <body>
        <table>
            <colgroup>
                <col id="fc"/>
                <col/>
                <col id="tc"/>
            </colgroup>
            <tr>
                <td colspan="3" class="sb">
                    <p class="bm"><strong>Recepta</strong></p>
                    <p>&nbsp;</p>
                </td>
            </tr>
            <tr>
                <td colspan="3" class="sbcb">
                    <p>{$company}&nbsp;</p>
                    <p>
                        {$street}{if $PostalPlace}{if $place} {$place}{/if}{/if}, 
                        {$PostalCode} 
                        {if $PostalPlace}{$PostalPlace}{else}{$place}{/if}, tel.: 
                        {$phone}
                        &nbsp;
                    </p>
                    <p class="tm">NIP: {$NIP}</p>
                    <p class="tm"><strong>Świadczeniodawca</strong></p>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="sb">
                    <p class="bm"><strong>Pacjent</strong></p>
                    <p>{$pn}&nbsp;</p>
                    <p>
                        {$PatientStreet}
                        {if $PatientPostalPlace}{if $PatientPlace}{$PatientPlace}{/if}{/if}
                        &nbsp;
                    </p>
                    <p>
                        {$PatientPostalCode} 
                        {if $PatientPostalPlace}{$PatientPostalPlace}{else}{$PatientPlace}{/if}
                        &nbsp;
                    </p>
                </td>
                <td class="sb">
                    <p><strong>Oddział NFZ</strong></p>
                    <p>&nbsp;</p>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="sbcb">
                    <p class="tm">{$PESEL}&nbsp;</p>
                    <p class="tm"><strong>PESEL</strong></p>
                </td>
                <td class="sb">
                    <p id="ap"><strong>Uprawnienia dodatkowe</strong></p>
                    <p>&nbsp;</p>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="hdb">
                    <p><strong>RP</strong></p>
                </td>
                <td class="hdb">
                    <p><strong>Odpłatność</strong></p>
                </td>
            </tr>
            <tr><td colspan="2" class="db"><p>{$Recipe[0]}&nbsp;</p></td><td class="db"></td></tr>
            <tr><td colspan="2" class="db"><p>{$Recipe[1]}&nbsp;</p></td><td class="db"></td></tr>
            <tr><td colspan="2" class="db"><p>{$Recipe[2]}&nbsp;</p></td><td class="db"></td></tr>
            <tr><td colspan="2" class="db"><p>{$Recipe[3]}&nbsp;</p></td><td class="db"></td></tr>
            <tr><td colspan="2" class="db"><p>{$Recipe[4]}&nbsp;</p></td><td class="db"></td></tr>
            <tr>
                <td colspan="3" class="db">
                    <p>{$Recipe[5]}&nbsp;</p>
                    {for $ri=6 to $re}<p>{$Recipe[$ri]}&nbsp;</p>{forelse}<p>&nbsp;</p>{/for}
                </td>
            </tr>
            <tr>
                <td class="sb">
                    <p class="bm"><strong>Data wystawienia:</strong></p>
                    <p>{$date}</p>
                </td>
                <td colspan="2" class="sb">
                    <p class="bm dh"><strong>Dane i podpis lekarza</strong></p>
                    <p>{$owner}&nbsp;</p>
                    <p>&nbsp;</p>
                </td>
            </tr>
            <tr>
                <td class="sb">
                    <p class="bm"><strong>Data realizacji &bdquo;od dnia&rdquo;:</strong></p>
                    <p>{$date}</p>
                </td>
                <td colspan="2" class="sbcb">
                    <p class="tm">Bluedental Anita Okoń</p>
                    <p>ul. Zamenhofa 12/4, 00-187 Warszawa</p>
                    <p class="tm dh"><strong>Dane podmiotu drukującego</strong></p>
                </td>
            </tr>
        </table>
    </body>
</html>