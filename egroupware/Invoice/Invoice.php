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
$result = SendQuery("SELECT DISTINCT `contact_name`, `contact_value` FROM `egw_addressbook_extra` WHERE `contact_name` = 'NIP' and `contact_id` = ".$cid);
while($row = GetNextRow($result))
{
    $smarty->assign('NIP', $row['contact_value']);
}
$result = SendQuery("SELECT DISTINCT `org_name`,`adr_one_street`,`adr_one_street2`,`adr_one_postalcode`,`adr_one_locality` FROM `egw_addressbook` WHERE `contact_id` = ".$cid);
while($row = GetNextRow($result))
{
    $smarty->assign('company', $row['org_name']);
    $smarty->assign('street', $row['adr_one_street']);
    $smarty->assign('PostalPlace', $row['adr_one_street2']);
    $smarty->assign('PostalCode', $row['adr_one_postalcode']);
    $smarty->assign('place', $row['adr_one_locality']);
}
if ($_GET['type'] == 'report')
{
    $result = SendQuery("select `egw_cal_extra`.`cal_extra_value`, `egw_cal_extra`.`cal_extra_name`, (select `egw_cal_dates`.`cal_end` from `egw_cal_dates` where `egw_cal_extra`.`cal_id` = `egw_cal_dates`.`cal_id`) as `date`, (select `egw_cal`.`cal_title` from `egw_cal` where `egw_cal_extra`.`cal_id` = `egw_cal`.`cal_id`) as `title`, (select `egw_cal`.`cal_description` from `egw_cal` where `egw_cal_extra`.`cal_id` = `egw_cal`.`cal_id`) as `description`,

(select distinct `egw_addressbook`.`n_fn` from `egw_addressbook` join `egw_cal_user` on (`egw_addressbook`.`contact_id` = `egw_cal_user`.`cal_id`) where `egw_cal_user`.`cal_id` = `egw_cal_extra`.`cal_id` and `egw_cal_user`.`cal_role` = 'REQ-PARTICIPANT') 

as `patient`,
(select `egw_categories`.`cat_name` from `egw_categories` join `egw_cal` on (`egw_categories`.`cat_id` = CAST(`egw_cal`.`cal_category` AS UNSIGNED)) where `egw_cal_extra`.`cal_id` = `egw_cal`.`cal_id`) as `category`
from `egw_cal_extra` where (`egw_cal_extra`.`cal_extra_name` = 'suma_na_wizycie' or `egw_cal_extra`.`cal_extra_name` = 'koszty_łącznie' or `egw_cal_extra`.`cal_extra_name` = 'koszty_technika' or `egw_cal_extra`.`cal_extra_name` = 'nazwa_pracowni_protetycznej') and (select `egw_cal`.`cal_owner` from `egw_cal` where `egw_cal_extra`.`cal_id` = `egw_cal`.`cal_id`) = ".$id." and ((select `egw_cal_dates`.`cal_end` from `egw_cal_dates` where `egw_cal_extra`.`cal_id` = `egw_cal_dates`.`cal_id`) between ".strtotime($_GET['from']. '00:00'). ' and '.strtotime($_GET['to']. '23:59'). ") order by `date`");
}
 else
{
    $result = SendQuery("select `egw_cal_extra`.`cal_extra_value`, `egw_cal_extra`.`cal_extra_name`, (select `egw_cal_dates`.`cal_end` from `egw_cal_dates` where `egw_cal_extra`.`cal_id` = `egw_cal_dates`.`cal_id`) as `date` from `egw_cal_extra` where (`egw_cal_extra`.`cal_extra_name` = 'suma_na_wizycie' or `egw_cal_extra`.`cal_extra_name` = 'koszty_łącznie' or `egw_cal_extra`.`cal_extra_name` = 'koszty_technika') and (select `egw_cal`.`cal_owner` from `egw_cal` where `egw_cal_extra`.`cal_id` = `egw_cal`.`cal_id`) = ".$id." and ((select `egw_cal_dates`.`cal_end` from `egw_cal_dates` where `egw_cal_extra`.`cal_id` = `egw_cal_dates`.`cal_id`) between ".strtotime($_GET['from']. '00:00'). ' and '.strtotime($_GET['to']. '23:59'). ') order by `date`');
}
$netto = 0;
$tc = 0;
$mc = 0;
$dn = array();
$dmc = array();
$dtc = array();
$gprn = array();
while($row = GetNextRow($result))
{
    switch($row['cal_extra_name'])
    {
        case 'suma_na_wizycie':
            $netto += $row['cal_extra_value'];
            $dn[date('d.m.Y', $row['date'])] += $row['cal_extra_value'];
            $patients[] = $row['patients'];
            $titles[] = $row['title'];
            $descriptions[] = $row['description'];
            $cat[] = $row['category'];
            break;
        case 'koszty_łącznie':
            $netto -= $row['cal_extra_value'];
            $dn[date('d.m.Y', $row['date'])] -= $row['cal_extra_value'];
            $mc += $row['cal_extra_value'];
            $dmc[] = $row['cal_extra_value'];
            break;
        case 'koszty_technika':
            $netto -= $row['cal_extra_value'];
            $dn[date('d.m.Y', $row['date'])] -= $row['cal_extra_value'];
            $tc += $row['cal_extra_value'];
            $dtc[] = $row['cal_extra_value'];
            break;
        case 'nazwa_pracowni_protetycznej':
            $prn[] = $row['cal_extra_value'];
            $gprn[$row['cal_extra_value']] = $row['cal_extra_value'];
    }
}
CloseConnection();
$percent = $_GET['percent']*0.01;
$VAT = 0.01*$_GET['vat'];
$lp = 1;
$nbsp = utf8_encode("\xA0");
$i = 0;
foreach($dn as $key => $value)
{
    if ($_GET['type'] == 'report')
    {
        $DentistTable[] = $patients[$i];
        $DentistTable[] = $titles[$i];
        $DentistTable[] = $descriptions[$i];
        $DentistTable[] = $cat[$i];
        $DentistTable[] = str_replace(" ", $nbsp, number_format($value, 2, ',', ' ')); 
        $DentistTable[] = $dmc[$i];
        $DentistTable[] = $prn[$i];
        $DentistTable[] = $dtc[$i];
    }
    else
    {
        $DentistTable[] = $lp;
        ++$lp;
        $DentistTable[] = 'Franczyza Bluedental ' . $key . '&nbsp;r.';
        $DentistTable[] = '1,000';
        $DentistTable[] = 'szt.';
        $DentistTable[] = '0,00'; 
    }
    $dv = $value*$percent;
    $dvt = round($dv, 2);
    $fdvt = str_replace(" ", $nbsp, number_format($dvt, 2, ',', ' '));
    $DentistTable[] = $fdvt;
    if ($_GET['type'] != 'report')
    {
       $DentistTable[] = $_GET['vat']; 
    }
    $DentistTable[] = $fdvt;
    $DVAT = $dvt*$VAT;
    $DentistTable[] = str_replace(" ", $nbsp, number_format(round($DVAT, 2), 2, ',', ' '));
    $DentistTable[] = str_replace(" ", $nbsp, number_format(round($dvt + $DVAT, 2), 2, ',', ' '));
}
if (!isset($DentistTable))
{
    $DentistTable = array('Nie pracował(a)!');
}
$smarty->assign('DentistTable', $DentistTable);

 
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
$netto = $netto*$percent;
$TVAT = $netto*$VAT;
$brutto = round($netto + $TVAT, 2);
$sum = str_replace(" ", utf8_encode("\xA0"), number_format($brutto, 2, ',', ' '));
if ($_GET['type'] == 'report')
{
    foreach($gprn as $value)
    {
        
    }
    $smarty->assign('RateTable', array('Podstawowy podatek VAT '. $_GET['vat'].'%',
                                       str_replace(" ", utf8_encode("\xA0"), number_format(round($netto, 2), 2, ',', ' ')),
                                       str_replace(" ", utf8_encode("\xA0"), number_format(round($TVAT, 2), 2, ',', ' ')), $sum,
                                       'Razem: ', str_replace(" ", utf8_encode("\xA0"), number_format(round($netto, 2), 2, ',', ' ')),
                                       str_replace(" ", utf8_encode("\xA0"), number_format(round($TVAT, 2), 2, ',', ' ')), $sum));
}
else
{
    $smarty->assign('RateTable', array('Podstawowy podatek VAT '. $_GET['vat'].'%',
                                       str_replace(" ", utf8_encode("\xA0"), number_format(round($netto, 2), 2, ',', ' ')),
                                       str_replace(" ", utf8_encode("\xA0"), number_format(round($TVAT, 2), 2, ',', ' ')), $sum,
                                       'Razem: ', str_replace(" ", utf8_encode("\xA0"), number_format(round($netto, 2), 2, ',', ' ')),
                                       str_replace(" ", utf8_encode("\xA0"), number_format(round($TVAT, 2), 2, ',', ' ')), $sum));
}
$smarty->assign('sum', $sum);
$SumParts = explode(',', $brutto);
$smarty->assign('InWords', slownie($SumParts[0]));
if ($SumParts[1] == '' || $SumParts[1] == null)
{
    $SumParts[1] = 0;
}
$smarty->assign('FractionalPart', $SumParts[1]);
if ($_GET['type'] == 'report')
{
    $smarty->display('Report.tpl');
}
 else
{
    $smarty->display('Invoice.tpl');
}
?>
