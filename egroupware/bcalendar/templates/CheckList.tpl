<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
      <title>Lista kontrolna - {$pn} - {$date}</title>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <style type="text/css">
/*<![CDATA[*/
body {
    width: 16cm;
    padding: 1.59cm 2.5cm 1.59cm 2.5cm;
    font-family: "Times New Roman", Times, serif;
}
h1 {
    font-size: 11.5pt;
    color: black;
    margin: 0 0 24pt 0;
    text-decoration: underline;
}
p {
    font-size: 11pt;
    color: black;
    text-align: justify;
}
strong {
    font-size: 11.5pt;
}
/*]]>*/
      </style>
    </head>
    <body>
        <h1>Lista kontrolna</h1>
        <p><strong>Imię i nazwisko pacjenta oraz nr PESEL:</strong> {$pn} {$PESEL}</p>
        <p><strong>Data i godzina wizyty:</strong> {$date}</p>
        <p>{$CheckList|regex_replace:"/[\r\n]/":"</p><p>"}</p>
    </body>
</html>