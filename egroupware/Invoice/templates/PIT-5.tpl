<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>PIT-5 {if !$company}{$an}{/if}{$company}</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style type="text/css">
/*<![CDATA[*/
@page {
  @top-center {
    content: element(pageHeader);
  }
}
body {
    margin: 0;
    width: 19.1cm;
    padding: 1cm;
    font-family: Arial,Helvetica,Garuda,sans-serif;
}
p {
    font-size: 10px;
}
sup {
    font-size: 8px;
}
#ph {
    position: running(pageHeader);
    border-bottom: 3px solid black;
    height: 5mm;
    margin-bottom: 1px;
}
#poltax {
    width: 2.3cm;
    font-size: 15px;
    float: left;
    margin-left: 3mm;
    margin-right: 0;
}
#wtf {
    width: 18.5cm;
    font-size: 8px;
    margin-left: 1px;
}
#ft {
    font-size: 10px;
    font-weight: bold;
    margin-left: 0; 
    width: 12.1cm;
    height: 8mm;
}
#ft td {
    border-right: 1px solid black;
    vertical-align: top;
}
#ft strong {
    margin-left: 4px;
}
#nip_label {
    position: relative;
    top: -11px;
}
#nip_border {
    font-size: 4pt; 
    margin-left: 3mm;
    margin-bottom: -3px;
    position: relative;
    margin-top: -8px;
}
#nip_border span {
    position: relative;
    top: -6px;
}
#doc_num_cell {
    background-color: #C0C0C0;
    width: 3.9cm;
}
#status_cell {
    background-color: #C0C0C0;
    width: 1.5cm;
}
#doc_num_cell strong, #status_cell strong {
    position: relative;
    top: -1px;
}
h1 {
    font-size: 6mm;
    margin-top: 1mm;
    margin-bottom: 0;
    margin-left: 3mm;
}
#tfl {
    margin-left: 2.3cm;
    font-size: 15px;
    font-weight: bold;
    margin-top: 0;
}
#tsl {
    margin-left: 4cm;
    font-size: 15px;
    font-weight: bold;
    margin-bottom: -1px;
    margin-top: -4mm;
}
#for_div {
    font-weight: bold;
    width: 4.3cm;
    text-align: right;
    font-size: 15px;
    height: 8mm;
    float: left;
    margin-right: 1mm;
    line-height: 8mm;
}
#date_div {
    border: 1px solid black;
    height: 8mm;
    width: 4.3cm;
    float: left;
}
#date_div strong {
    top: -3mm;
    margin-left: 3px;
    position: relative;
}
#date {
    top: -6mm;
    margin-left: 22px;
    position: relative;
    font-size: 14px;
    letter-spacing: 12px;
}
#date span {
    font-size: 1px;
    letter-spacing: 16px;
}
#date_border {
    font-size: 4pt;
    top: -11mm;
    margin-left: 14px;
    position: relative;
}
#date_border span {
    top: -1px;
    position: relative;
}
#fpotf {
    font-weight: bold;
    margin-left: 3mm;
    margin-bottom: 0;
    margin-top: 2px;
    clear: both;
}
#mpotf {
    font-weight: bold;
    margin-left: 3mm;
    margin-bottom: 0;
    margin-top: 0;
    clear: both;
}
#lpotf {
    font-weight: bold;
    margin-left: 3mm;
    margin-bottom: 2px;
    margin-top: 0;
    clear: both;
}
.tfs {
    border-top: 1px solid black;
    border-bottom: 1px solid black;
}
h2 {
    font-size: 15px;
    margin-left: 3px;
}
h3 {
    font-weight: normal;
    color: #060606;
    margin-left: 3px;
    font-size: 15px;
}
h2 span, h3 span {
    font-weight: normal;
    font-size: 10px;
}
.ptf {   
    border-style: solid;  
}
#first_ptf {
    border-width: 2px 2px 1px 2px;
    border-color: #A5A5A5;
}
#second_ptf {
    border-color: black;
    border-width: 1px 1px 1px 1px;
}
#third_ptf {
    border-width: 1px 2px 1px 2px;
    border-color: #A5A5A5;
}
#fourth_ptf {
    border-width: 1px 1px 1px 1px;
    border-color: black black #A5A5A5 black;
}
#h_a {
    margin-top: 3px;
    margin-bottom: 3px;
}
.h_normal {
    margin-top: 2mm;
    margin-bottom: 2mm;
}
#h_d {
    margin-top: 3px;
    margin-bottom: 2px;
}
.h_ej {
    padding-top: 2px;
    margin-top: 0;
    margin-bottom: 1mm;
}
#h_jl {
    margin-top: 1px;
    margin-bottom: 3px;
}
#h_k {
    margin-top: 2px;
    margin-bottom: 3px;
}
#h_m {
    margin-top: 3mm;
    margin-bottom: 2mm;
}
#h_n {
    margin-top: 1px;
    margin-bottom: 0;
}
#h_o {
    margin-top: 3px;
    margin-bottom: 4mm;
}
#h_p {
    margin-top: 1mm;
    margin-bottom: 1mm;
}
.tfc {
    margin-top: 1px;
    margin-bottom: 1px;
    border-top: 2px solid black;
    border-bottom: 2px solid black;
    background-color: #DFDFDF;
}
.ptf .tfc:first-child {
    margin-top: 0;
    border-top: 0;
}
.ptf .tfc:last-child {
    margin-bottom: 0;
    border-bottom-width: 1px;
}
.dt {
    font-size: 10px;
    margin-left: 1mm;
    float: left;
    width: 2.5cm;
}
.definition {
    font-size: 10px;
    margin-left: 2.8cm;
}
#last_definition {
    position: relative;
    top: -3px;
    margin-bottom: -1px;
}
.definition sup {
    position: relative;
    top: 4px;
}
.fb {
    margin-left: 7mm;
    background-color: white;
    border-width: 1px 0 1px 1px;
    border-style: solid;
    border-color: black;
    height: 8mm;
    margin-bottom: -1px;
}
.fb p {
    margin-left: 3px;
    margin-top: -1px;
    font-weight: bold;
}
.fb td {
    vertical-align: top;
    padding: 0;
    height: 8mm;
}
table {
    margin-left: 7mm;
    border-width: 1px 0 1px 1px;
    border-style: solid;
    border-color: black;
    margin-bottom: -1px;
    width: 18.3cm;
    border-spacing: 0;
    border-collapse: collapse;
}
.tlb {
    border-top: 1px solid black;
    border-left: 1px solid black;
}
#address_table td {
    border-color: black;
    border-style: solid;
    border-width: 1px 0 1px 1px;
    vertical-align: top;
    background-color: white;
    height: 8mm;
}
#address_table div {
    overflow: hidden;
    height: 8mm;
}
#address_table strong {
    font-size: 10px;
    position: relative;
    top: -7px;
}
#address_table p {
    font-size: 10px;
}
#address_table div p strong {
    top: -13px !important;
}
#country_col {
    width: 3.8cm;
}
#voivodeship_commune_col {
    width: 9mm;
}
.street_postal_code_col {
    width: 3cm;
}
#post_col {
    width: 1.1cm;
}
#county_col {
    width: 2.1cm;
}
.house_flat_col {
    width: 3cm;
 }
#source_col {
    width: 4.9cm;
}
.data {
    margin: 0;
    text-align: center;
    margin-top: -22px;
    font-size: 12pt !important;
}
.expenses_col {
    width: 3.2cm;
}
#determination_header td, #determination_header th {
    font-size: 10pt;
    text-align: center;
    border-left: 1px solid black;
    vertical-align: top;
    padding-top: 5px;
    height: 9mm;
}
.income_loss {
    padding-top: 3px !important;
}
#determination_header strong {   
    line-height: 14px;
    display: inline-block;
}
#determination_header sub {
    font-size: 8px;
}
#determination_header p {
    font-size: 8pt;
    margin-bottom: 0;
    margin-top: 2px;
}
#expenses_currency td {
    font-size: 8px;
    border-left: 1px solid black;
    padding-left: 73px;
}
#letter_row td {
    font-size: 8px;
    border-top: 1px solid black;
    border-left: 1px solid black;
    padding-left: 49px;
    padding-top: 0;
    padding-bottom: 0;
    height: 4mm;
}
#letter_row td:first-child {
    padding-left: 91px;
}
.income_source_top {
    font-size: 6.2pt;
    border-top: 1px solid black;
    border-right: 1px solid black;
    text-align: left;
    padding-left: 4px;
}
.income_source_top span {
    margin-left: 9px;
    text-indent: -9px;
    display: inline-block;
}
.income_source_bottom {
    font-size: 6.2pt;
    padding-left: 4px;
    border-bottom: 1px solid black;
    border-right: 1px solid black;
    text-align: left;
    vertical-align: bottom;
}
.amount_top_row td {
    font-size: 6.2pt;
    border-left: 1px solid black;
    border-top: 1px solid black;
    padding-left: 5px;
    vertical-align: top;
    background-color: white;
}
.amount_bottom_row td, #sum_bottom_row td:not(.income_source_bottom) {
    border-left: 1px solid black;
    padding-left: 78px;
    font-size: 8pt;
    background-color: white;
}
tr#sum_top_row td, tr#sum_top_row th {
    border-top: 2px solid black !important;
    border-right: 2px solid black !important;
    vertical-align: top;
    font-size: 6.2pt;
}
tr#sum_top_row td {
    padding-left: 5px;
    background-color: white;
}
#sum_bottom_row td {
    border-right: 2px solid black;
}
#total_deductions_info {
    font-size: 8pt;
    float: left;
    width: 640px;
    margin-left: 26px;
}
#income_currency {
    font-size: 8px;
}
#income_table {
    border-top: 2px solid #7F7F7F;
}
#income_table td {
    border-left: 1px solid black;
    border-bottom: 1px solid black;
    padding-top: 0;
    padding-bottom: 0;
}
#income_table p {
    font-size: 6.7pt;
    margin-top: 0;
    margin-bottom: 0;
}
#income_values_col {
    width: 3.9cm;
}
#income_table tr td:nth-child(2) strong {
    margin-left: 7px;
}
.tax-exempt_i_losses {
    margin-left: 13px;
}
#income_after_deduction {
    padding-left: 10px;
}
.income_value {
    margin-left: 101px;
}
ol {
    counter-reset: mojalista;
    padding-left: 12px;
    margin-top: 0;
}
ol > li {
    list-style: none;
    counter-increment:  mojalista;
    margin-top: 6px;
    margin-bottom: 5px;
    font-size: 8pt;
    line-height: 10.5pt;
}
ol > li:before {
    content: counter(mojalista) ")\0000a0";
    text-indent: -30px;
    display: inline-block;
    text-align: right;
}
#max_sum_info {
    margin-left: 26px;
    margin-bottom: 0;
    margin-top: 0;
}
#taxpayer_signature {
    display: inline-block;
    font-weight: bold;
    border: 1px solid black;
    height: 8mm;
    padding-left: 3px;
}
#tax_office_notes {
    font-weight: bold;
    font-size: 10px;
    height: 1.8cm;
    background-color: #C0C0C0;
    border: 1px solid black;
    padding-left: 3px;
}
#taking {
    background-color: #C0C0C0;
    font-weight: bold;
    font-size: 10px;
    height: 8mm;
    float: left;
    border: 1px solid #767676;
    width: 8.8cm;
    padding-left: 3px;
}
#taking_signature {
    background-color: #C0C0C0;
    font-weight: bold;
    font-size: 10px;
    height: 8mm;
    border: 1px solid #767676;
    padding-left: 3px;
}
 /*]]>*/
  </style>
</head>

<body>
    <div id="ph">
        <div id="poltax">POLTAX</div>
        <div id="wtf">POLA JASNE WYPEŁNIA PODATNIK, POLA CIEMNE WYPEŁNIA URZĄD SKARBOWY. WYPEŁNIAĆ NA MASZYNIE, KOMPUTEROWO LUB RĘCZNIE, DUŻYMI,
    DRUKOWANYMI LITERAMI, CZARNYM LUB NIEBIESKIM KOLOREM.</div>
    </div>
    <table id="ft">
        <tr>
            <td>
                <p id="nip_label"><strong>1. Numer Identyfikacji Podatkowej</strong></p>
                <p id="nip_border">└────┴────┴────┘<span>¯</span>└────┴────┴────┘<span>¯</span>└────┴────┘<span>¯</span>└────┴────┘</p>
            </td>
            <td id="doc_num_cell"><strong>2. Nr dokumentu</strong></td>
            <td id="status_cell"><strong>3. Status</strong></td>
        </tr>
    </table>
    <h1>PIT-5</h1>
    <p id="tfl">DEKLARACJA NA ZALICZKĘ MIESIĘCZNĄ</p>
    <p id="tsl">NA PODATEK DOCHODOWY</p>
    <div id="for_div">za </div>
    <div id="date_div">
        <p><strong>4. Miesiąc - rok</strong></p>
        <p id="date">
            {if $SaleDate}
                {$SaleDate[3]}{$SaleDate[4]}<span>&nbsp;</span>{$SaleDate[6]}{$SaleDate[7]}{$SaleDate[8]}{$SaleDate[9]}
                {else}
                {$to[3]}{$to[4]}<span>&nbsp;</span>{$to[6]}{$to[7]}{$to[8]}{$to[9]}
            {/if}
        </p>
        <p id="date_border">└────┴────┘ &nbsp;&nbsp;&nbsp;<span>─</span>&nbsp;&nbsp;&nbsp; └────┴────┴────┴────┘</p> 
    </div>
    <p id="fpotf">Formularz jest przeznaczony dla podatników:</p>
    <p id="mpotf">- prowadzących pozarolniczą działalność gospodarczą,</p>
    <p id="lpotf">- osiągających przychody z najmu, podnajmu lub dzierżawy oraz innych umów o podobnym charakterze.</p>
    <div class="ptf" id="first_ptf">
        <div class="tfc"><div>
            <div class="dt">Podstawa prawna:</div>
            <div class="definition">Art.44 ust.6 ustawy z dnia 26 lipca 1991 r. o podatku dochodowym od osób fizycznych (Dz.U. z 2000 r. Nr 14, poz.176, z późn. zm.),
zwanej dalej ”ustawą”.</div>
        </div>
        <div>
            <div class="dt">Składający:</div>
            <div class="definition">Podatnik podatku dochodowego od osób fizycznych.</div>
        </div>
        <div>
            <div class="dt">Termin składania:</div>
            <div class="definition">Za
miesiące
od
stycznia
do
listopada
-
do
dnia
20
każdego
miesiąca
za
miesiąc
poprzedni
(za
liczkę
za
grudzień
w
wysokości należnej za listopad uiszcza się w terminie do 20 grudnia, bez
składania deklaracji).</div>
        </div>
        <div>
            <div class="dt">Miejsce składania:</div>
            <div class="definition" id="last_definition">Urząd skarbowy
<sup>1)</sup>
według miejsca zamieszkania lub pobytu podatnika w ostatnim dniu okresu rozliczeniowego, którego
deklaracja
dotyczy.</div>
        </div></div>
        <div class="tfc"><h2 id="h_a">A
.
MIEJSCE SKŁADANIA
DEKLARACJI</h2><div class="fb"><p>5. Urząd skarbowy, do którego adresowana jest deklaracja</p></div></div>
        <div class="tfc">
            <h2 class="h_normal">B. DANE PODATNIKA</h2>
            <div class="tfs">
                <h3 class="h_normal">B.1. DANE IDENTYFIKACYJNE</h3>
                <table class="fb">
                    <tr><td colspan="2"><p>6. Nazwisko</p></td></tr>
                    <tr><td class="tlb"><p>7. Pierwsze imię</p></td><td class="tlb"><p>8. Data urodzenia (dzień - miesiąc - rok)</p></td></tr>
                </table>
            </div>
            <h3 class="h_normal">B.2. ADRES ZAMIESZKANIA</h3>
            <table id="address_table">
                <colgroup>
                    <col id="country_col"/>
                    <col id="voivodeship_commune_col"/>
                    <col class="street_postal_code_col"/>
                    <col class="street_postal_code_col"/>
                    <col id="post_col"/>
                    <col id="county_col"/>
                    <col class="house_flat_col"/>
                    <col class="house_flat_col"/>
                </colgroup>
                <tr>
                    <td><div><p><strong>9. Kraj</strong></p><p class="data">Polska</p></div></td>
                    <td colspan="4"><strong>10. Województwo</strong></td>
                    <td colspan="3"><strong>11. Powiat</strong></td>
                </tr>
                <tr>
                    <td colspan="2"><strong>12. Gmina</strong></p></td>
                    <td colspan="4"><strong>13. Ulica</strong></td>
                    <td><strong>14. Nr domu</strong></td>
                    <td><strong>15. Nr lokalu</strong></td>
                </tr>
                <tr>
                    <td colspan="3"><strong>16. Miejscowość</strong></td>
                    <td><strong>17. Kod pocztowy</strong></td>
                    <td colspan="4"><strong>18. Poczta</strong></td>
                </tr>
            </table>
        </div>
        <div class="tfc">
            <h2 class="h_normal">C. USTALENIE DOCHODU / STRATY</h2>
            <table>
                <colgroup>
                    <col id="source_col"/>
                    <col class="expenses_col"/>
                    <col class="expenses_col"/>
                    <col class="expenses_col"/>
                    <col class="expenses_col"/>
               </colgroup>
                <tr id="determination_header">
                    <th>Źródło przychodów</th>
                    <th>Przychód <sup>2)</sup></th>
                    <th><strong>Koszt uzyskania przychodu</strong></th>
                    <td class="income_loss"><strong>Dochód</strong> <sup>3)</sup><p>(b - c)</p></td>
                    <th class="income_loss">Strata</th>
                </tr>
                <tr id="expenses_currency">
                    <td>&nbsp;</td>
                    <td>zł,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;gr</td>
                    <td>zł,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;gr</td>
                    <td>zł,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;gr</td>
                    <td>zł,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;gr</td>
                </tr>
                <tr id="letter_row">
                    <td><div><span>a</span></div></td>
                    <td><div><span>b</span></div></td>
                    <td><div><span>c</span></div></td>
                    <td><div><span>d</span></div></td>
                    <td><div><span>e</span></div></td>
                </tr>
                <tr class="amount_top_row">
                    <th class="income_source_top"><span>1. Pozarolnicza działalność gospodarcza</span></th>
                    <td>19.</td>
                    <td>20.</td>
                    <td>21.</td>
                    <td>22.</td>
                </tr>
                <tr class="amount_bottom_row">
                    <th class="income_source_bottom">&nbsp;</th>
                    <td>,</td>
                    <td>,</td>
                    <td>,</td>
                    <td>,</td>
                </tr>
                <tr class="amount_top_row">
                    <th class="income_source_top"><span>2. Najem, podnajem lub dzierżawa oraz
inne umowy o podobnym charakterze</span></th>
                    <td>23.</td>
                    <td>24.</td>
                    <td>25.</td>
                    <td>26.</td>
                </tr>
                <tr class="amount_bottom_row">
                    <th class="income_source_bottom">&nbsp;</th>
                    <td>,</td>
                    <td>,</td>
                    <td>,</td>
                    <td>,</td>
                </tr>
                <tr id="sum_top_row">
                    <th class="income_source_top">RAZEM</th>
                    <td>27.</td>
                    <td>28.</td>
                    <td>29.</td>
                    <td>30.</td>
                </tr>
                <tr id="sum_bottom_row">
                    <td class="income_source_bottom">Suma kwot z wierszy 1 i 2.</td>
                    <td>,</td>
                    <td>,</td>
                    <td>,</td>
                    <td>,</td>
                </tr>
            </table>
        </div>
        <div class="tfc">
            <h2 id="h_d">D. DOCHÓD PO ODLICZENIU
DOCHODU ZWOLNIONEGO I STRAT</h2>
            <div id="total_deductions_info">Suma
odliczeń
nie
może
przekroczyć
kwoty
dochodu
z
poz.
29.</div>
            <div id="income_currency">zł,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;gr</div>
            <table id="income_table">
                <colgroup>
                    <col/>
                    <col id="income_values_col"/>
                </colgroup>
                <tr>
                    <td>
                        <p class="tax-exempt_i_losses"><strong>Dochód zwolniony od podatku - na podstawie art.21 ust.1 pkt 63a
ustawy</strong></p>
                        <p class="tax-exempt_i_losses">Zwolnienie przysługuje wyłącznie z tytułu dochodów uzyskanych z
działalności gospodarczej prowadzonej na terenie specjalnej
strefy&nbsp;ekonomicznej.&nbsp;Pozycji tej nie wypełniają podatnicy korzystający ze zwolnienia, o którym mowa w poz.41.</p>
                    </td>
                    <td><p><strong>31.</strong></p><p>&nbsp;</p><p class="income_value">,</p></td>
                </tr>
                <tr>
                    <td>
                        <p class="tax-exempt_i_losses"><strong>Straty z lat ubiegłych zgodnie z art.9 ust.3 i 3a ustawy z uwzględnieniem art.3 ustawy
z dnia 11 sierpnia 2001 r.
o&nbsp;szczególnych rozwiązaniach prawnych związanych z usuwaniem skutków powodzi z lipca i sierpnia 2001 r. oraz
o&nbsp;zmianie niektórych ustaw (Dz.U. Nr 84, poz.907, z późn. zm.)</strong></p>
                    </td>
                    <td><p><strong>32.</strong></p><p>&nbsp;</p><p class="income_value">,</p></td>
                </tr>
                <tr>
                    <td id="income_after_deduction">
                        <p><strong>Dochód po odliczeniu dochodu zwolnionego i strat</strong></p>
                        <p>&nbsp;</p>
                        <p>Od
kwoty
z poz.29 należy
odjąć
kwoty
z
poz.31
i
32.</p>
                    </td>
                    <td><p><strong>33.</strong></p><p>&nbsp;</p><p class="income_value">,</p></td>
                </tr>
            </table>
        </div>
    </div>
    <ol>
        <li>Ilekroć mowa o urzędzie skarbowym - oznacza to urząd skarbowy, którym kieruje właściwy dla podatnika naczelnik urzędu skarbowego.</li>
        <li>W poz.19 należy również wykazać kwoty zwiększające przychód
z tytułu utraty przez podatnika prawa do ulg inwestycyjnych, w
związku z art.7
ust.19 i 20 ustawy z dnia 9 listopada 2000 r. o zmianie ustawy
o podatku dochodowym od osób fizycznych oraz o zmianie niektórych innych ustaw
(Dz.U. Nr 104, poz.1104, z późn. zm.).</li>
        <li>Jeżeli podatnik uzyskuje z pozarolniczej działalności gospodarczej przychody opodatkowane i zwolnione od podatku, strata z
działalności objętej
zwolnieniem nie pomniejsza dochodu z działalności podlegającej
opodatkowaniu.</li>
    </ol>
    <div class="ptf" id="second_ptf">
        <div class="tfc">
            <h2 class="h_ej">E. ODLICZENIA OD DOCHODU</h2>
            <p id="max_sum_info">Suma kwot z części od E.1. do E.3. oraz F. i G. nie może przekroczyć kwoty z poz.33.</p>
            <div class="tfs">
                <h3 id="h_normal">E.1. ODLICZENIA OD DOCHODU
<span>- NA PODSTAWIE ART.26 UST.1 PKT 2, 5-6a i 9 USTAWY</span></h3>
                <table>
                    <tr>
                        <td>1. Składki na ubezpieczenia społeczne - na podstawie art.26 ust
.1 pkt 2 lit.a, z zastrzeżeniem ust.13a ustawy
<sup>4)</sup></td>
                        <td>
                            <strong>34.</strong>
                            <p>zł,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;gr</p>
                        </td>
                    </tr>
                    <tr><td>2. Odliczenia od dochodu - na podsta
wie art.26 ust.1 pkt 5-6a i
9 ustawy</td><td>
                            <strong>35.</strong>
                            <p>zł,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;gr</p>
                        </td></tr>
                </table>
            </div>
            <div class="tfs">
                <h3 id="h_normal">E.2. ODLICZENIA OD DOCHODU WYDATKÓW MIESZKANIOWYCH
<span>- NA ZASADZIE PRAW NABYTYCH</span></h3>
                
            </div>
            <div>
                <h3 id="h_normal">E.3. INNE ODLICZENIA NIE WYMIENIONE W CZĘŚCIACH E.1. DO E.2.</h3>
                
            </div>
        </div>
        <div class="tfc">
            <h2 id="h_normal">F. ODLICZENIA Z TYTUŁU WYDATKÓW INWESTYCYJNYCH</h2>
            
        </div>
        <div class="tfc">
            <h2 id="h_normal">G. DOCHÓD ZWOLNIONY OD PODATKU <span>-
NA PODSTAWIE PRZEPISÓW WYKONAWCZYCH DO USTAWY
Z DNIA 20.10.1994 R. O SPECJALNYCH STREFACH EKONOMICZNYCH (DZ.U. NR 123, POZ.600, Z PÓŹN. ZM.</span></h2>
            
        </div>
        <div class="tfc">
            <h2 id="h_normal">H. DOCHÓD PO ODLICZENIACH</h2>
            
        </div>
        <div class="tfc">
            <h2 id="h_normal">I. USTALENIE PODSTAWY
OBLICZENIA PODATKU</h2>
            <table></table>
        </div>
        <div class="tfc">
            <h2 id="h_normal">J. OBLICZENIE NALEŻNEGO PODATKU</h2>
            <div class="tfs">
                <h3 id="h_normal">J.1. OBLICZENIE PODATKU</h3>
                
            </div>
            <div>
                <h3 class="h_ej">J.2. ODLICZENIA OD PODATKU</h3>
                <p>Suma
odliczeń
nie
może
przekroczyć kwoty
z
poz.45.</p>
                <table>
                    <tr>
                        <th colspan="2">1. Składka na ubezpieczenie zdrowotne, o której mowa w art.27b
ust.1 pkt 1 ustawy, z zast
rzeżeniem art.27b ust.2
ustawy
4)
, opłacona od początku roku</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th colspan="2">2. Ulgi inwestycyjne przyznane przed dniem 1 stycznia 1992 r. i
niewykorzystane w latach ubiegłych</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th colspan="2">3. Ulgi za wyszkolenie uczniów lub z tytułu zatrudnienia pracow
ników w celu przygotowania
zawodowego przyznane na
podstawie decyzji organu podatkowego</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th>4. Inne odliczenia
niewymienione
w wierszach od 1 do 3</th>
                        <td>49. Podać rodzaj:</td>
                        <td>50.</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="ptf" id="third_ptf">
        <div class="tfc">
            <div>
                <h3 id="h_normal">J.3. OBLICZENIE NALEŻNEJ ZALICZKI</h3>
                <table></table>
            </div>
            <div>
                <h3 id="h_jl">J.4. <span>OGRANICZENIE POBORU
ZALICZEK – NA PODSTAWIE ART.22 §2A USTAWY
Z
DNIA 29.08.1997 R. –
ORDYNACJA PODATKOWA (DZ.U. Z 2005 R. NR 8, POZ.60, Z PÓŹN. ZM.)</span></h3>
                <table>
                    <colgroup>
                        <col/>
                        <col/>
                        <col/>
                    </colgroup>
                    <tr>
                        <td>55. Numer(-y) decyzji organu podatkowego</td>
                        <td colspan="2"><strong>56. Data(-y) decyzji organu podatkowego</strong>
(dzień - miesiąc - rok)</td>
                    </tr>
                    <tr>
                        <th colspan="2">Kwota wynikająca z decyzji organu podatkowego</th>
                        <td>57.</td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                        <td>zł</td>
                    </tr>
                    <tr>
                        <th colspan="2">Kwota zrealizowana w poprzednich miesiącach</th>
                        <td>58.</td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                        <td>zł</td>
                    </tr>
                    <tr>
                        <th colspan="2">Kwota do zrealizowania w niniejszej deklaracji</th>
                        <td>59.</td>
                    </tr>
                    <tr>
                        <td colspan="2">Podana kwota nie może przekroczyć kwoty wykazanej w poz. ”
<strong>Należna zaliczka za miesiąc</strong>
”.</td>
                        <td>zł</td>
                    </tr>
                </table>
            </div>
            <div>
                <h3 id="h_normal">J.5. OBLICZENIE ZOBOWIĄZANIA PRZYPADAJĄCEGO DO ZAPŁATY</h3>
                <table>
                    
                </table>
            </div>
        </div>
        <div class="tfc">
            <h2 id="h_k">K. POZAROLNICZA DZIAŁALNOŚĆ GOSPODARCZA <span>(W TYM RÓWNIEŻ UDZIAŁ W SPÓŁKACH NIEMAJĄCY OSOBOWOŚCI PRAWNEJ)</span></h2>
            <table></table>
        </div>
    </div>
    <div class="ptf" id="fourth_ptf">
        <div>
            <h2 class="h_jl">L. POZAROLNICZA DZIAŁALNOŚĆ GOSPODARCZA</h2>
            
        </div>
        <div class="tfc">
            <h2 id="h_m">M. NAJEM, PODNAJEM, DZIERŻAWA ORAZ INNE O PODOBNYM CHARAKTERZE, A TAKŻE WSPÓŁWŁASNOŚĆ I WSPÓLNE POSIADANIE</h2>
            
        </div>
        <div class="tfc">
            <h2 id="h_n">N. INFORMACJA O ZAŁĄCZNIKACH</h2>
            
        </div>
        <div class="tfc">
            <h2 id="h_o">O. OŚWIADCZENIE I PODPIS PODATNIKA</h2>
            <p>Oświadczam, że są mi znane przepisy Kodeksu karnego skarbowego
o odpowiedzialności za podanie danych niezgodnie z rzeczywistością i przez to
narażenie na uszczuplenie podatku.</p>
            <p id="taxpayer_signature">150. Podpis podatnika</p>
        </div>
        <div class="tfc">
            <h2 id="h_p">P. ADNOTACJE URZĘDU SKARBOWEGO</h2>
            <div id="tax_office_notes">151. Uwagi do urzędu skarbowego</div>
            <div id="taking">152. Identyfikator przyjmującego formularz</div>
            <div id="taking_signature">153. Podpis przyjmującego formularz</div>
        </div>
    </div>
    <p>*) Pouczenie</p>
    <p>W wypadku niewpłacenia w obowiązującym terminie kwot z poz.62 i
63 lub wpłacenia ich w niepełnej wysokości, niniejsza deklaracja stanowi
podstawę do wystawienia tytułu wykonawczego, zgodnie z przepisami ustawy z dnia 17 czerwca 1966 r. o postępowaniu egzekucyjnym
w administracji (Dz.U. z 2005 r. Nr 229, poz.1954).</p>
</body>
</html>
  