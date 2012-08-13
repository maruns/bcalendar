<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Tworzenie faktury</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script type="text/javascript" src="../DataPicker/jquery.min.js"></script>
    <script type="text/javascript" src="../DataPicker/jquery.datePicker.js"></script>
    <script type="text/javascript" src="../DataPicker/date.js"></script>
    <script type="text/javascript" src="Script.js"></script>
    <link type="text/css" rel="stylesheet" href="../DataPicker/datePicker.css" />
    <link type="text/css" rel="stylesheet" href="../Windows.css" />
</head>
<body>
    <h1>Tworzenie faktury</h1>
    <form target="_blank" action="Invoice.php">
        <p><label for="dentist">Dentysta: </label></p>
        <p>{html_options onchange="document.getElementById('submit').disabled = false;" options=$dentists id=dentist size="4" name=dentist}</p>
        <p>
            <label for="percent">Procent franczyzny: </label>
            <input id="percent" size="3" maxlength="3" value="{$d_FRANCZYZA}" name="percent" type="text" />%
        </p>
        <p><label for="date-pick">Za dzień: </label></p>
        <p><input type="text" id="date-pick" name="date-pick" class="date-pick" /></p>
        <p>&nbsp;</p>
        <p><label for="vat">Podatek VAT: </label><input type="text" id="vat" name="vat" value="{$d_VAT}" size="2" maxlength="2" />%</p>
        <p><input type="submit" id="submit" value="Utwórz fakturę" disabled="disabled"/></p>
    </form>
</body>
</html>
