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
            switch(id)
            {
                case "assistant":
                    document.getElementById(id).innerHTML =  '<option value="0">Brak</option>' + xmlhttpr.responseText;
                    break;
                case "description":
                    if (xmlhttpr.responseText != '')
                    {
                        document.forms.ef.description.value = xmlhttpr.responseText;
                    }
                    break;
                default:
                    document.getElementById(id).innerHTML =  xmlhttpr.responseText;
            }
        }
    };
    xmlhttpr.send();
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
function InsertData(id, title, price, TotalCost, TechnicianCosts, category)
{
    document.forms.ef.title.value = title;
    OnTitleChange();
    document.forms.ef.suma_na_wizycie.value = price / 100;
    if (TotalCost != '')
    {
        document.forms.ef.koszty_łącznie.value = TotalCost / 100;
    }
    if (TechnicianCosts != '')
    {
        document.forms.ef.koszty_technika.value = TechnicianCosts / 100;
    }
    if (category != '')
    {
        for (var i = 1; i < document.forms.ef.category.length; i++)
        {
            if (document.forms.ef.category.options[i].text.indexOf(category) == 0)
            {
                document.forms.ef.category.options[i].selected = true;
            }
            else
            {
                document.forms.ef.category.options[i].selected = false;
            }
        }
    }
    SetContentFromScript("description", 'ProductDescription.php?id=' + id);
}