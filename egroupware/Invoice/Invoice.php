<?php
$GLOBALS['egw_info'] = array(
        'flags' => array(
                'currentapp' => 'Invoice',
                'noheader'   => True
        ),
);
require_once('../DatabaseConnection.php');
require_once('../SmartyConfig.php');
include_once('/var/lib/egroupware/Invoice.php');
$cid = strtok($_GET['dentist'],'α');
$id = intval(strtok('α'));
date_default_timezone_set('Europe/Warsaw');
setlocale(LC_ALL, 'pl_PL', 'pl', 'Polish_Poland.28592');
$smarty->assign('d_NAZWA_BANKU', d_NAZWA_BANKU);
$smarty->assign('d_NUMER_KONTA', d_NUMER_KONTA);
$smarty->assign('d_NIP', d_NIP);
$smarty->assign('SaleDate', $_GET['to']);
$smarty->assign('in', $_GET['in']);
$result = SendQuery("SELECT DISTINCT `contact_name`, `contact_value` FROM `egw_addressbook_extra` WHERE (`contact_name` = 'NIP' or `contact_name` = 'franczyza' or `contact_name` = 'stawka' or `contact_name` = 'podatek_asystenta') and `contact_id` = ".$cid);
while($row = GetNextRow($result))
{
    switch ($row['contact_name'])
    {
        case 'NIP':
            $smarty->assign('NIP', $row['contact_value']);
            break;
        case 'franczyza':
            $percent = $row['contact_value']*0.01;
            break;
        case 'stawka':
            $sr = $row['contact_value']/3600;
            break;
        case 'podatek_asystenta':
            $at = $row['contact_value']*0.01;
    }
}
$rp = 1 - $percent;
$result = SendQuery("SELECT DISTINCT `org_name`,`adr_one_street`,`adr_one_street2`,`adr_one_postalcode`,`adr_one_locality` FROM `egw_addressbook` WHERE `contact_id` = ".$cid);
while($row = GetNextRow($result))
{
    $smarty->assign('company', $row['org_name']);
    $smarty->assign('street', $row['adr_one_street']);
    $smarty->assign('place', $row['adr_one_street2']);
    $smarty->assign('PostalCode', $row['adr_one_postalcode']);
    $smarty->assign('PostalPlace', $row['adr_one_locality']);
}
$nbsp = utf8_encode("\xA0");
$dn = array();
$gprn = array();
$netto = 0;
$brutto = 0;
$TVAT = 0;
switch ($_GET['type'])
{
    case 'report':
        $result= SendQuery("SELECT `egw_cal`.`cal_title` , `egw_cal`.`cal_description` , `egw_categories`.`cat_name` , ( SELECT DISTINCT `egw_addressbook`.`n_fn` FROM `egw_addressbook` JOIN `egw_cal_user` ON ( `egw_addressbook`.`contact_id` = `egw_cal_user`.`cal_user_id` ) WHERE `egw_cal_user`.`cal_id` = `egw_cal`.`cal_id` AND `egw_cal_user`.`cal_role` = 'REQ-PARTICIPANT'  limit 1) AS `patient` , (SELECT DISTINCT `egw_addressbook_extra`.`contact_value` FROM `egw_addressbook_extra` JOIN (`egw_addressbook`, `egw_cal_user`) ON ( `egw_addressbook_extra`.`contact_id` = `egw_addressbook`.`contact_id` and `egw_addressbook`.`account_id` = `egw_cal_user`.`cal_user_id` ) where  `egw_cal_user`.`cal_id` = `egw_cal`.`cal_id` AND `egw_cal_user`.`cal_role` = 'assistant' and `egw_addressbook_extra`.`contact_name` = 'stawka'  limit 1) as ac, ( SELECT DISTINCT `egw_cal_dates`.`cal_start` FROM `egw_cal_dates` WHERE `egw_cal`.`cal_id` = `egw_cal_dates`.`cal_id` limit 1) AS `date`, ( SELECT DISTINCT `egw_cal_dates`.`cal_end` FROM `egw_cal_dates` WHERE `egw_cal`.`cal_id` = `egw_cal_dates`.`cal_id`  limit 1) AS `end`, (select DISTINCT `egw_cal_extra`.`cal_extra_value` from `egw_cal_extra` where `egw_cal_extra`.`cal_extra_name` = 'suma_na_wizycie' and `egw_cal_extra`.`cal_id` = `egw_cal`.`cal_id`  limit 1) as `vs`, (select DISTINCT `egw_cal_extra`.`cal_extra_value` from `egw_cal_extra` where `egw_cal_extra`.`cal_extra_name` = 'koszty_łącznie' and `egw_cal_extra`.`cal_id` = `egw_cal`.`cal_id`  limit 1) as `mc`, (select DISTINCT `egw_cal_extra`.`cal_extra_value` from `egw_cal_extra` where `egw_cal_extra`.`cal_extra_name` = 'koszty_technika' and `egw_cal_extra`.`cal_id` = `egw_cal`.`cal_id`  limit 1) as `tc`, (select DISTINCT `egw_cal_extra`.`cal_extra_value` from `egw_cal_extra` where `egw_cal_extra`.`cal_extra_name` = 'nazwa_pracowni_protetycznej' and `egw_cal_extra`.`cal_id` = `egw_cal`.`cal_id`  limit 1) as `tn` FROM `egw_cal` left JOIN ( `egw_categories` ) ON ( `egw_categories`.`cat_id` = CAST( `egw_cal`.`cal_category` AS UNSIGNED ) ) WHERE `egw_cal`.`cal_owner` = " . $id . " AND ( ( SELECT DISTINCT `egw_cal_dates`.`cal_end` FROM `egw_cal_dates` WHERE `egw_cal`.`cal_id` = `egw_cal_dates`.`cal_id` limit 1) BETWEEN ".strtotime($_GET['from']. '00:00'). ' and '.strtotime($_GET['to']. '23:59'). ") order by `date`");
    break;
    case 'ar':
        $an = strtok('α');
        $smarty->assign('an', $an);
        $result = SendQuery("select `egw_cal`.`cal_title`, ( SELECT DISTINCT `egw_cal_dates`.`cal_start` FROM `egw_cal_dates` WHERE `egw_cal`.`cal_id` = `egw_cal_dates`.`cal_id` limit 1) AS `date`, ( SELECT DISTINCT `egw_cal_dates`.`cal_end` FROM `egw_cal_dates` WHERE `egw_cal`.`cal_id` = `egw_cal_dates`.`cal_id` limit 1) AS `end`, `egw_addressbook`.`n_fn` from `egw_cal` join `egw_addressbook` on (`egw_addressbook`.`account_id` = `egw_cal`.`cal_owner`) where ( ( SELECT DISTINCT `egw_cal_dates`.`cal_end` FROM `egw_cal_dates` WHERE `egw_cal`.`cal_id` = `egw_cal_dates`.`cal_id` limit 1) BETWEEN ".strtotime($_GET['from']. '00:00'). ' and '.strtotime($_GET['to']. '23:59'). ") and (SELECT DISTINCT  `egw_cal_user`.`cal_user_id` from  `egw_cal_user` where  `egw_cal_user`.`cal_id` = `egw_cal`.`cal_id` AND `egw_cal_user`.`cal_role` = 'assistant' limit 1) in (" . $id . ", " . $cid . ")  order by `date`");
        while($row = GetNextRow($result))
        {
            $DentistTable[] = $row['cal_title'];
            $DentistTable[] = date('d.m.Y',$row['date']). ' r. '.date('G:i',$row['date']) . '&nbsp;-&nbsp;' . date('G:i',$row['end']);
            $DentistTable[] = $row['n_fn'];
            $amount = $sr*($row['end'] - $row['date']);
            $DentistTable[] = str_replace(" ", $nbsp, number_format(round($amount, 2), 2, ',', ' '));
            $DentistTable[] = str_replace(" ", $nbsp, number_format(round($amount - ($amount * $at), 2), 2, ',', ' '));
            $brutto += $amount;
            $gprn[$row['n_fn']] += $amount;
        }
        $dn = array();
        break;
    case 'invoice':
    $result= SendQuery("select (SELECT DISTINCT `egw_addressbook_extra`.`contact_value` FROM `egw_addressbook_extra` JOIN (`egw_addressbook`, `egw_cal_user`) ON ( `egw_addressbook_extra`.`contact_id` = `egw_addressbook`.`contact_id` and `egw_addressbook`.`account_id` = `egw_cal_user`.`cal_user_id` ) where  `egw_cal_user`.`cal_id` = `egw_cal`.`cal_id` AND `egw_cal_user`.`cal_role` = 'assistant' and `egw_addressbook_extra`.`contact_name` = 'stawka'  limit 1) as `ac`, ( SELECT DISTINCT `egw_cal_dates`.`cal_start` FROM `egw_cal_dates` WHERE `egw_cal`.`cal_id` = `egw_cal_dates`.`cal_id` limit 1) AS `date`, ( SELECT DISTINCT `egw_cal_dates`.`cal_end` FROM `egw_cal_dates` WHERE `egw_cal`.`cal_id` = `egw_cal_dates`.`cal_id`  limit 1) AS `end` FROM `egw_cal` WHERE `egw_cal`.`cal_owner` = " . $id . " AND ( ( SELECT DISTINCT `egw_cal_dates`.`cal_end` FROM `egw_cal_dates` WHERE `egw_cal`.`cal_id` = `egw_cal_dates`.`cal_id` limit 1) BETWEEN ".strtotime($_GET['from']. '00:00'). ' and '.strtotime($_GET['to']. '23:59'). ") order by `date`");
    $dn = array();
    while($row = GetNextRow($result))
    {
        $iac = $row['ac'] * ($row['end'] - $row['date']) / 3600;
        $dn[date('d.m.Y', $row['date'])] -= $iac;
//        $netto -= $iac;
        $brutto -= $iac;
    }
    $result = SendQuery("select `egw_cal_extra`.`cal_extra_value`, `egw_cal_extra`.`cal_extra_name`, (select `egw_cal_dates`.`cal_end` from `egw_cal_dates` where `egw_cal_extra`.`cal_id` = `egw_cal_dates`.`cal_id`) as `date` from `egw_cal_extra` where (`egw_cal_extra`.`cal_extra_name` = 'suma_na_wizycie' or `egw_cal_extra`.`cal_extra_name` = 'koszty_łącznie' or `egw_cal_extra`.`cal_extra_name` = 'koszty_technika') and (select `egw_cal`.`cal_owner` from `egw_cal` where `egw_cal_extra`.`cal_id` = `egw_cal`.`cal_id`) = ".$id." and ((select `egw_cal_dates`.`cal_end` from `egw_cal_dates` where `egw_cal_extra`.`cal_id` = `egw_cal_dates`.`cal_id`) between ".strtotime($_GET['from']. '00:00'). ' and '.strtotime($_GET['to']. '23:59'). ') order by `date`');
}
$VAT = 0.01*$_GET['vat'];

if ($_GET['type'] == 'report')
{
    $sum = 0;
    $ts = 0;
    $ms = 0;
    $as = 0;
    $cs = 0;
    $VATVS = 0;
    $tns = array();
    $tbs = array();                                         
    $tms = array();
    $tas = array();
    $tcs = array();
    $TVATVS = array();
}
$BruttoToNettoMultiplier = 1 / (1 + 0.01 * $_GET['vat']);
$ss = 0;
$vn = 0;
$vs = 0;
while($row = GetNextRow($result))
{
    switch ($_GET['type'])
    {
    case 'report':
        $vn += 1;
        $ss += $row['end'] - $row['date'];
        $DentistTable[] = date('d.m.Y',$row['date']). ' r. '.date('G:i',$row['date']) . '&nbsp;-&nbsp;' . date('G:i',$row['end']);
        $DentistTable[] = $row['patient'];
        $DentistTable[] = $row['cal_title'];
        $DentistTable[] = $row['cal_description'];
        $DentistTable[] = $row['cat_name'];
        $DentistTable[] = str_replace(" ", $nbsp, number_format(floatval($row['vs']), 2, ',', ' ')); //Kwota zapłacona 
        $DentistTable[] = $row['tn'];
        $DentistTable[] = str_replace(" ", $nbsp, number_format(floatval($row['tc']), 2, ',', ' ')); //Koszt technika
        $DentistTable[] = str_replace(" ", $nbsp, number_format(floatval($row['mc']), 2, ',', ' ')); //Materiały
        $ac = $row['ac']*($row['end'] - $row['date'])/3600;
        $DentistTable[] = str_replace(" ", $nbsp, number_format($ac, 2, ',', ' ')); //koszt asystenta
        $costs = $row['mc'] + $row['tc'] + $ac;
        $DentistTable[] = str_replace(" ", $nbsp, number_format($costs, 2, ',', ' ')); //suma kosztów
        $vs += $row['vs'];
        $avbv = $percent * ($row['vs'] - $costs);
        $vbv = round($avbv, 2);
        $avnv = $avbv * $BruttoToNettoMultiplier;
        $vnv = round($avnv, 2);
        $DentistTable[] = str_replace(" ", $nbsp, number_format($vnv, 2, ',', ' ')); //wartość netto
//        $VVAT = $vnv * $VAT;
        $VVAT = round($avbv - $avnv, 2);
        $DentistTable[] = str_replace(" ", $nbsp, number_format($VVAT, 2, ',', ' ')); //VAT
//        $vbv = $vnv + $VVAT;
        $DentistTable[] = str_replace(" ", $nbsp, number_format($vbv, 2, ',', ' ')); //wielkość brutto
        $gprn[$row['tn']] += $row['tc'];
        $sum += $vbv;
        $ts += $row['tc'];
        $ms += $row['mc'];
        $as += $ac;
        $cs += $costs;
        $netto += $vnv;
        $VATVS += $VVAT;
        $tns[$row['tn']] += $vnv;
        $tbs[$row['tn']] += $vbv;
        $tms[$row['tn']] += $row['mc'];
        $tas[$row['tn']] += $ac;
        $tcs[$row['tn']] += $costs;
        $TVATVS[$row['tn']] += $VVAT;
        break;
    case 'invoice':
        switch($row['cal_extra_name'])
        {
            case 'suma_na_wizycie':
//                $netto += $row['cal_extra_value'];
                $brutto += $row['cal_extra_value'] * $percent;
                $dn[date('d.m.Y', $row['date'])] += $row['cal_extra_value'] * $percent;
                break;
            case 'koszty_łącznie':
//                $netto -= $row['cal_extra_value'];
                $brutto += $row['cal_extra_value'] * $rp;
                $dn[date('d.m.Y', $row['date'])] += $row['cal_extra_value'] * $rp;
                break;
            case 'koszty_technika':
//                $netto -= $row['cal_extra_value'];
                $brutto -= $row['cal_extra_value'] * $percent;
                $dn[date('d.m.Y', $row['date'])] -= $row['cal_extra_value'] * $percent;
                
        }
        $LastDate = date('m/Y', $row['date']);
        break;              
    }
}
CloseConnection();
$smarty->assign('LastDate', $LastDate);
$lp = 1;
foreach($dn as $key => $value)
{
    if ($value != 0)
    {        
        if ($value > 0)
        {
            $DentistTable[] = $lp;
            ++$lp;
            $DentistTable[] = 'Franczyza Bluedental ' . $key . '&nbsp;r.';
            $DentistTable[] = '1,000';
            $DentistTable[] = 'szt.';
            $DentistTable[] = '0,00'; 
//            $dv = $value*$percent;
            $DayNetto = round($value * $BruttoToNettoMultiplier, 2);
            $netto += $DayNetto;
            $dvt = round($value, 2);
            $fdvt = str_replace(" ", $nbsp, number_format(/*$dvt*/$DayNetto, 2, ',', ' '));
            $DentistTable[] = $fdvt; //Cena netto
            if ($_GET['type'] != 'report')
            {
               $DentistTable[] = $_GET['vat']; 
            }
            $DentistTable[] = $fdvt; //Wartość netto
            $DVAT = /*$dvt*$VAT*/round($dvt - $DayNetto, 2);
            $TVAT += $DVAT;
            $DentistTable[] = str_replace(" ", $nbsp, number_format($DVAT, 2, ',', ' ')); //VAT
            $DentistTable[] = str_replace(" ", $nbsp, number_format(round($dvt/* + $DVAT*/, 2), 2, ',', ' ')); //Wielkość brutto
        }
        else
        {
            $brutto -= $value;
        }
    }
}
if (!isset($DentistTable))
{
    $DentistTable = array('Nie pracował(a)!');
}
$smarty->assign('DentistTable', $DentistTable);
$hs = $ss / 3600;
$smarty->assign('hs', floor($hs));
$smarty->assign('mins', ($ss / 60) % 60); 
$smarty->assign('vn', $vn); 
if ($vn == 0)
{
    $smarty->assign('ac', 0);
}
else
{
    $smarty->assign('ac', $vs / $vn);
}
if ($hs == 0)
{
    $smarty->assign('ah', 0);
}
else
{
    $smarty->assign('ah', $vs / $hs);
}


$slowa = Array(
  'minus',
 
  Array(
    'zero',
    'jeden',
    'dwa',
    'trzy',
    'cztery',
    'pięć',
    'sześć',
    'siedem',
    'osiem',
    'dziewięć'),
 
  Array(
    'dziesięć',
    'jedenaście',
    'dwanaście',
    'trzynaście',
    'czternaście',
    'piętnaście',
    'szesnaście',
    'siedemnaście',
    'osiemnaście',
    'dziewiętnaście'),
 
  Array(
    'dziesięć',
    'dwadzieścia',
    'trzydzieści',
    'czterdzieści',
    'pięćdziesiąt',
    'sześćdziesiąt',
    'siedemdziesiąt',
    'osiemdziesiąt',
    'dziewięćdziesiąt'),
 
  Array(
    'sto',
    'dwieście',
    'trzysta',
    'czterysta',
    'pięćset',
    'sześćset',
    'siedemset',
    'osiemset',
    'dziewięćset'),
 
  Array(
    'tysiąc',
    'tysiące',
    'tysięcy'),
 
  Array(
    'milion',
    'miliony',
    'milionów'),
 
  Array(
    'miliard',
    'miliardy',
    'miliardów'),
 
  Array(
    'bilion',
    'biliony',
    'bilionów'),
 
  Array(
    'biliard',
    'biliardy',
    'biliardów'),
 
  Array(
    'trylion',
    'tryliony',
    'trylionów'),
 
  Array(
    'tryliard',
    'tryliardy',
    'tryliardów'),
 
  Array(
    'kwadrylion',
    'kwadryliony',
    'kwadrylionów'),
 
  Array(
    'kwintylion',
    'kwintyliony',
    'kwintylionów'),
 
  Array(
    'sekstylion',
    'sekstyliony',
    'sekstylionów'),
 
  Array(
    'septylion',
    'septyliony',
    'septylionów'),
 
  Array(
    'oktylion',
    'oktyliony',
    'oktylionów'),
 
  Array(
    'nonylion',
    'nonyliony',
    'nonylionów'),
 
  Array(
    'decylion',
    'decyliony',
    'decylionów')
);
 
function odmiana($odmiany, $int){ // $odmiany = Array('jeden','dwa','pięć')
  $txt = $odmiany[2];
  if ($int == 1) $txt = $odmiany[0];
  $jednosci = (int) substr($int,-1);
  $reszta = $int % 100;
  if (($jednosci > 1 && $jednosci < 5) &! ($reszta > 10 && $reszta < 20))
    $txt = $odmiany[1];
  return $txt;
}
 
function liczba($int){ // odmiana dla liczb < 1000
  global $slowa;
  $wynik = '';
  $j = abs((int) $int);
 
  if ($j == 0) return $slowa[1][0];
  $jednosci = $j % 10;
  $dziesiatki = ($j % 100 - $jednosci) / 10;
  $setki = ($j - $dziesiatki*10 - $jednosci) / 100;
 
  if ($setki > 0) $wynik .= $slowa[4][$setki-1].' ';
 
  if ($dziesiatki > 0)
        if ($dziesiatki == 1) $wynik .= $slowa[2][$jednosci].' ';
  else
    $wynik .= $slowa[3][$dziesiatki-1].' ';
 
  if ($jednosci > 0 && $dziesiatki != 1) $wynik .= $slowa[1][$jednosci].' ';
  return $wynik;
}
 
function slownie($int){
 
  global $slowa;
 
  $in = preg_replace('/[^-\d]+/','',$int);
  $out = '';
 
  if ($in{0} == '-'){
    $in = substr($in, 1);
    $out = $slowa[0].' ';
  }
 
  $txt = str_split(strrev($in), 3);
 
  if ($in == 0) $out = $slowa[1][0].' ';
 
  for ($i = count($txt) - 1; $i >= 0; $i--){
    $liczba = (int) strrev($txt[$i]);
    if ($liczba > 0)
      if ($i == 0)
        $out .= liczba($liczba).' ';
          else
        $out .= ($liczba > 1 ? liczba($liczba).' ' : '')
          .odmiana( $slowa[4 + $i], $liczba).' ';
  }
  return trim($out);
}
switch ($_GET['type'])
{
    case 'report':
    $trt = array();
    foreach($gprn as $key=>$value)
    {
        $trt[] = $key;
        $trt[] = str_replace(" ", $nbsp, number_format(round($value, 2), 2, ',', ' '));
        $trt[] = str_replace(" ", $nbsp, number_format(round($tms[$key], 2), 2, ',', ' '));
        $trt[] = str_replace(" ", $nbsp, number_format(round($tas[$key], 2), 2, ',', ' '));
        $trt[] = str_replace(" ", $nbsp, number_format(round($tcs[$key], 2), 2, ',', ' '));
        $trt[] = str_replace(" ", $nbsp, number_format(round($tns[$key], 2), 2, ',', ' '));
        $trt[] = str_replace(" ", $nbsp, number_format(round($TVATVS[$key], 2), 2, ',', ' '));
        $trt[] = str_replace(" ", $nbsp, number_format(round($tbs[$key], 2), 2, ',', ' '));
    }
    $trt[] = 'Razem:';
    $trt[] = str_replace(" ", $nbsp, number_format(round($ts, 2), 2, ',', ' '));
    $trt[] = str_replace(" ", $nbsp, number_format(round($ms, 2), 2, ',', ' '));
    $trt[] = str_replace(" ", $nbsp, number_format(round($as, 2), 2, ',', ' '));
    $trt[] = str_replace(" ", $nbsp, number_format(round($cs, 2), 2, ',', ' '));
    $trt[] = str_replace(" ", $nbsp, number_format(round($netto, 2), 2, ',', ' '));
    $trt[] = str_replace(" ", $nbsp, number_format(round($VATVS, 2), 2, ',', ' '));
    $brutto = round($sum, 2);
    $sum = str_replace(" ", $nbsp, number_format($brutto, 2, ',', ' '));
    $trt[] = str_replace(" ", $nbsp, $sum);
    $smarty->assign('RateTable', $trt);
    break;
    case 'ar':
        foreach($gprn as $key=>$value)
        {
            $trt[] = $key;
            $trt[] = str_replace(" ", $nbsp, number_format(round($value, 2), 2, ',', ' '));
            $trt[] = str_replace(" ", $nbsp, number_format(round($value - ($value*$at), 2), 2, ',', ' '));
        }
        $trt[] = 'Razem:';
        $sum = str_replace(" ", $nbsp, number_format(round($brutto, 2), 2, ',', ' '));
        $trt[] = str_replace(" ", $nbsp, $sum);
        $sum = str_replace(" ", $nbsp, number_format(round($brutto - $brutto*$at, 2), 2, ',', ' '));
        $trt[] = str_replace(" ", $nbsp, $sum);
        $smarty->assign('RateTable', $trt);
        break;  
  case 'invoice':
//    $netto = $netto*$percent;
//    $TVAT = $netto*$VAT;
//      $brutto = $brutto * $percent;
//      $netto = $brutto * $BruttoToNettoMultiplier;
//    $TVAT = $brutto - $netto;
    $brutto = round($brutto/*$netto + $TVAT*/, 2);
    $sum = str_replace(" ", $nbsp, number_format($brutto, 2, ',', ' '));
    $smarty->assign('RateTable', array('Podstawowy podatek VAT '. $_GET['vat'].'%',
                                       str_replace(" ", $nbsp, number_format(round($netto, 2), 2, ',', ' ')),
                                       str_replace(" ", $nbsp, number_format(round($TVAT, 2), 2, ',', ' ')), $sum,
                                       'Razem: ', str_replace(" ", $nbsp, number_format(round($netto, 2), 2, ',', ' ')),
                                       str_replace(" ", $nbsp, number_format(round($TVAT, 2), 2, ',', ' ')), $sum));
}
$smarty->assign('sum', $sum);
$SumParts = explode(',', $sum);
$smarty->assign('InWords', slownie($SumParts[0]));
if ($SumParts[1] == '' || $SumParts[1] == null)
{
    $SumParts[1] = 0;
}
$smarty->assign('FractionalPart', $SumParts[1]);
switch ($_GET['type'])
{
  case 'report':
    $smarty->display('templates/Report.tpl');
    break;
  case 'ar':
      $smarty->display('templates/Assistants.tpl');
      break;
  case 'invoice':
    $smarty->display('templates/Invoice.tpl');
      break;
  case 'pit':
    $smarty->display('templates/PIT-5.tpl');
      break;
  case 'cr':
    $smarty->display('templates/CostReport.tpl');
}
?>
