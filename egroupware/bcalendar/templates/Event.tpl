<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
      <title>Zdarzenie</title>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <script type="text/javascript" src="../../DatePicker/jquery.min.js"></script>
      <script type="text/javascript" src="../../DatePicker/jquery.datePicker.js"></script>
      <script type="text/javascript" src="../../DatePicker/date.js"></script>
      <script type="text/javascript" src="../js/Event.js"></script>
      <link type="text/css" rel="stylesheet" href="../../DatePicker/datePicker.css" />
      <link type="text/css" rel="stylesheet" href="../../Windows.css" />
    </head>
    <body>
        <form action="../inc/Event.php" method="POST" enctype="multipart/form-data">
            <p>Tytuł: <input type="text" name="title" />&nbsp;{if $id}#{$id}{/if}</p>
            <p>Start: <input type="text" id="start" name="start" class="date-pick" /></p>
            <p>Czas trwania: <input type="text" name="start" class="date-pick" /></p>
            <p><input type="checkbox" id="private" name="private"><label for="private">Zdarzenie prywatne</label></input></p>
            <p><input type="submit" value="OK" name="ok" /><input type="submit" value="Zastosuj" name="apply" /></p>
        </form>
    </body>
</html>