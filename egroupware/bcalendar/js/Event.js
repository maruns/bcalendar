function EnableRepetition()
{
    if (document.forms.ef.repetition.value === "no")
    {   
        document.forms.ef.interval.disabled = true;
        document.forms.ef.re.disabled = true;
        document.forms.ef.odwh.disabled = true;
    }
    else
    {
        document.forms.ef.interval.disabled = false;
        document.forms.ef.re.disabled = false;
        document.forms.ef.odwh.disabled = false;
    }
}
function OnTitleChange()
{
    if (($.trim(document.forms.ef.title.value)).length === 0)
    {
        document.getElementById("tp").style.backgroundColor = 'red';
    }
    else
    {
        document.getElementById("tp").style.backgroundColor = '';
    }
}
$(function()
{
    //EnableRepetition();
    OnTitleChange();
    var match = RegExp('[?&]date=([^&]*)').exec(window.location.search);
    var date = match ? decodeURIComponent(match[1].replace(/\+/g, ' ')) : null;
    $('.date-pick').datePicker({startDate:'01/01/2013'});
    if (date)
    {
        $('#date').datePicker().val(date[6] + date[7] + '.' + date[4] + date[5] + '.' + date[0] + date[1] + date[2] + date[3])
                                .trigger('change');
    }
    else
    {
        if (($.trim(document.forms.ef.title.value)).length === 0)
        {
            $('#date').datePicker().val(new Date().asString()).trigger('change');
        }
    }
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
    SetContentFromScript("dentist", 'DentistOptions.php?search=' + document.forms.ef.ld.value + '&ce=' + document.forms.ef.iad.checked);
}
function OnLAChange()
{
    SetContentFromScript("assistant", 'DentistOptions.php?search=' + document.forms.ef.la.value + '&ce=' + document.forms.ef.iaa.checked);
}
function OnLPChange()
{
    if (document.forms.ef.lp.value != "")
    {
        SetContentFromScript("ep", 'ContactOptions.php?search=' + document.forms.ef.lp.value);
    }
    else
    {
        document.getElementById("patient").innerHTML = '<option value="0">Brak</option>';
    }
}
