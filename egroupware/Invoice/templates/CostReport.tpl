<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>Raport kosztów {if !$company}{$an}{/if}{$company}</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style type="text/css">
/*<![CDATA[*/
body {
    width: 18.2cm;
    padding: 1.4cm;
    font-family: Arial,Helvetica,Garuda,sans-serif;
}
h1 {
    font-size: 14pt;
    color: black;
    text-align: center;
    margin: 0.5cm 0 0 0;
}
p {
    font-size: 10pt;
    color: black;
    margin: 0;
}
.lc, .rc {
    width: 8.8cm;
}
.lc {
    float: left;
}
.rc, #rt, #sst {
    float: right;
}
div.rc {
    margin-bottom: -0.2cm;
}
table {
    margin: 0.5cm 0 0 0;
    font-size: 10pt;
    width: 100%;
    border: black solid 1px;
    border-collapse: collapse;
    border-spacing: 0;
    text-align: center;
}
th {
    background-color: lightgrey;
    font-weight: normal;
}
.simple {
    border: 0;
}
.simple td {
    border-bottom: black solid 1px;
}
.simple th {
    border-top: black solid 2px;
}
#tlt {
    width: 6.6cm;
    text-align: center;
    float: right;
    margin-bottom: 1.3cm;
}
.signature {
    width: 8.3cm;
}
.signature th {
    font-weight: bold;
    width: 100%;
    border-top: black solid 1px;
    border-left: black solid 1px;
    border-right: black solid 1px;
}
.signature .sp {
    height: 2cm;
    width: 100%;
    border-left: black solid 1px;
    border-right: black solid 1px;
    vertical-align: top;
}
.signature .sd {
    font-size: 8pt;
    width: 100%;
    border-bottom: 0;
    height: 0.3cm;
}
#sst {
    width: 10cm;
    border-left: 0;
    border-right: 0;
    clear: both;
}
#sst th {
    border-top: black solid 2px;
    font-weight: bold;
    font-size: 12pt;
}
.bc {
    clear: both;
}
#rt {
    width: 11cm;
    border: 0;
}
#rt td, #rt th {
    border: black solid 1px;
}
#rt tr:last-child td {
    border-width: 1px 0 0 0;
}
#separator {
    height: 0;
}
  /*]]>*/
  </style>
</head>

<body>
    <div>
        <div class="lc">
            <p>Bluedental Anita Okoń</p>
            <p>ul. Zamenhofa 12/4, 00-187 Warszawa</p>
            <p>Tel.: 22 635 23 99, NIP: {$d_NIP}</p>
            <p>{$d_NAZWA_BANKU},</p>
            <p>{$d_NUMER_KONTA}</p>
        </div>
        <div class="rc">
            <table class="simple" id="tlt">
                <tr><th>Miejsce wystawienia:</th></tr>
                <tr><td>Warszawa</td></tr>
                <tr><th>Data sprzedaży:</th></tr>
                <tr><td>{$SaleDate} r.</td></tr>
                <tr><th>Data wystawienia:</th></tr>
                <tr><td>{$SaleDate} r.</td></tr>
            </table>
        </div>
    </div>
    <div class="bc">
        <table class="simple lc">
            <tr><th>Sprzedawca:</th></tr>
            <tr>
                <td>
                    <p>Bluedental Anita Okoń</p>
                    <p>ul. Zamenhofa 12/4</p>
                    <p>00-187 Warszawa</p>
                    <p>NIP: {$d_NIP}</p>
                </td>
            </tr>
        </table>
        <table class="simple rc">
            <tr><th>Nadawca:</th></tr>
            <tr>
                <td>
                    <p>{if !$company}{$an}{/if}{$company}</p>
                    <p>{$street}</p>
                    {if $PostalPlace}<p>{$place}</p>{/if}
                    <p>{$PostalCode} {if $PostalPlace}{$PostalPlace}{else}{$place}{/if}</p>
                    {if $NIP}<p>NIP: {$NIP}</p>{/if}
                </td>
            </tr>
        </table>
    </div>
    <div class="bc" id="separator">&nbsp;</div>
    <h1 class="bc">Raport kosztów   {if $in}{$in}{else}<sub>. . .</sub>  {/if}/{$smarty.now|date_format: '%m/%Y'} oryginał</h1>
    {html_table
     loop=$DentistTable
     cols="Tytuł, Opis, Data, Czas, Koszt"}
    <div>
        <table id="sst">
            <tr><th>Razem:</th><th>{$sum}</th></tr>
            <tr><td><strong>Słownie: </strong>{$InWords}</td><td>PLN {$FractionalPart}/100</td></tr>
        </table>
    </div>
    <p class="bc"><strong>Zapłacono gotówką:   {$sum}</strong></p>
    <table class="simple lc signature bc">
        <tr><th>Wystawił(a):</th></tr>
        <tr>
            <td class="sp">
                Adam Okoń
            </td>
        </tr>
        <tr>
            <td class="sd">
                Podpis osoby upoważnionej do wystawienia faktury VAT
            </td>
        </tr>
    </table>
    <table class="simple rc signature">
        <tr><th>Odebrał(a):</th></tr>
        <tr>
            <td class="sp">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td class="sd">
                Podpis osoby upoważnionej do odbioru faktury VAT
            </td>
        </tr>
    </table>
</body>
</html>
