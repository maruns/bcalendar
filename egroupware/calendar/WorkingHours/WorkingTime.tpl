<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html  xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Czas pracy dentysty {$name}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link type="text/css" rel="stylesheet" href="../../Windows.css" />
</head>
<body>
    {if $dentists}<ul id="al">{foreach $dentists as $dentist}<li>{$dentist}</li>{/foreach}</ul>{/if}
    <div id="wts">
        <h1>Czas pracy dentysty {$name}</h1>
        <p>Poniedziałek:</p>
        <p>Wtorek:</p>
        <p>Sroda:</p>
        <p>Czwartek:</p>
        <p>Piątek:</p>
        <p>Sobota:</p>
        <p>Niedziela:</p>
        <p>Terminy szczególne:</p>
    </div>
</body>