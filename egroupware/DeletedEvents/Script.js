$(function()
{
    $('.date-pick').datePicker({startDate:'01/01/2012'});
});
function OnLDChange()
{
    xmlhttpr = new XMLHttpRequest();
    xmlhttpr.open("GET", 'UserOptions.php?aia=' + document.forms.def.iad.checked, true);
    xmlhttpr.onreadystatechange = function()
    {
        if (xmlhttpr.readyState == 4 && xmlhttpr.status == 200)
        {
            
                document.getElementById("owner").innerHTML =  xmlhttpr.responseText;
        }
    };
    xmlhttpr.send();
}
