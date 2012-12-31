<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
      <title>Wizyta „{$title}”</title>
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
    margin: 10pt 0 10pt 0;
}
p {
    font-size: 12pt;
    color: black;
    margin: 6pt 0 6pt 0;
}
#right {
    text-align: right;
}
#description {
    text-indent: 0.5cm;
    text-align: justify;
}
/*]]>*/
      </style>
    </head>
    <body>
        <p id="right">Warszawa, {$smarty.now|date_format: '%d.%m.%Y'} r.</p>
        <h1>{$title}</h1>
        <p>Pacjent: {$patient}</p>
        <p>Dentysta: {$dentist}</p>
        <p>Czas: {$date} r. {$start} - {$end}</p>
        <p id="description">{$description}</p>
    </body>
</html>