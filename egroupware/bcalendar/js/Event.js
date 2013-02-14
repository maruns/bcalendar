function EnableRepetition()
{
    if (document.ef.repetition.value === "no")
    {   
        document.ef.interval.disabled = true;
        document.ef.re.disabled = true;
        document.ef.odwh.disabled = true;
    }
    else
    {
        document.ef.interval.disabled = false;
        document.ef.re.disabled = false;
        document.ef.odwh.disabled = false;
    }
}
$(function()
{
    EnableRepetition();
    var match = RegExp('[?&]date=([^&]*)').exec(window.location.search);
    var date = match ? decodeURIComponent(match[1].replace(/\+/g, ' ')) : null;
    $('.date-pick').datePicker({startDate:'01/01/2013'});
    $('#start').datePicker().val(date[6] + date[7] + '.' + date[4] + date[5] + '.' + date[0] + date[1] + date[2] + date[3]).trigger('change');
});
function GetFromScript(script)
{
    xmlhttpr = new XMLHttpRequest();
    xmlhttpr.open("GET", script, true);
    xmlhttpr.onreadystatechange = function()
    {
        if (xmlhttpr.readyState == 4 && xmlhttpr.status == 200)
        {
            return xmlhttpr.responseText;
        }
    }
    xmlhttpr.send();
}
function OnLDChange()
{
    document.getElementById("dentist").innerHTML = GetFromScript('bcalendar/inc/DentistOptions.php?search=' + document.ef.ld.value);
}
