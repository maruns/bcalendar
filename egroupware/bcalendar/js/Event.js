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
    //EnableRepetition();
    var match = RegExp('[?&]date=([^&]*)').exec(window.location.search);
    var date = match ? decodeURIComponent(match[1].replace(/\+/g, ' ')) : null;
    $('.date-pick').datePicker({startDate:'01/01/2013'});
    $('#start').datePicker().val(date[6] + date[7] + '.' + date[4] + date[5] + '.' + date[0] + date[1] + date[2] + date[3]).trigger('change');
});
function SetContentFromScript(id, script)
{
    xmlhttpr = new XMLHttpRequest();
    xmlhttpr.open("GET", script, true);
    xmlhttpr.onreadystatechange = function()
    {
        if (xmlhttpr.readyState == 4 && xmlhttpr.status == 200)
        {
            if (id == "assistant")
            {
                document.getElementById(id).innerHTML =  '<option value="0">Brak</option>' + xmlhttpr.responseText;
            }
            else
            {
                document.getElementById(id).innerHTML =  xmlhttpr.responseText;
            }
            
        }
    };
    xmlhttpr.send();
    return result;
}
function OnLDChange()
{
    SetContentFromScript("dentist", 'DentistOptions.php?search=' + document.ef.ld.value + '&ce=' + document.ef.iad.checked);
}
function OnLAChange()
{
    SetContentFromScript("assistant", 'DentistOptions.php?search=' + document.ef.la.value + '&ce=' + document.ef.iaa.checked);
}
function OnLPChange()
{
    if (document.ef.lp.value != "")
    {
        SetContentFromScript("ep", 'ContactOptions.php?search=' + document.ef.lp.value);
    }
    else
    {
        document.getElementById("patient").innerHTML = '<option value="0">Brak</option>';
    }
}
