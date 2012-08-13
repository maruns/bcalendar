<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>Faktura dla dentysty {$dentist}</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style type="text/css">
/*<![CDATA[*/
body {
    width: 21cm;
    padding: 2.5cm;
    font-family: "Times New Roman", Times, serif;
}
h1 {
    font-size: 20pt;
    color: #7f7f7f;
    margin: 0 0 20pt 0;
}
p {
    font-size: 12pt;
    color: black;
    margin: 0;
}
#lc, #rc {
    width: 10.5cm;
}
#lc {
    float: left;
}
#rc {
    margin-left: 10.5cm;
    text-align: right;
}
table {
    margin: 10px 0 0 0;
    width: 100%;
    border: #A6A6A6 solid 2px;
    border-collapse: collapse;
    border-spacing: 0;
    text-align: center;
}
  /*]]>*/
  </style>
</head>

<body>
    <div id="lc"><p><strong>Blue Dental</strong></p><p>ul. Zamenhofa 12/4</p><p>00-187 Warszawa</p><p>Tel. 22 6352399</p></div>
    <div id="rc">
        <h1>FAKTURA</h1>
        <p>{$smarty.now|date_format: '%d.%m.%Y'} r.</p>
    </div>
    {html_table loop=$DentistTable cols="Imię i nazwisko, Procent franczyzy, Dzień, Franczyza netto, Procent VAT, Podatek VAT, Franczyza brutto"}
</body>
</html>
