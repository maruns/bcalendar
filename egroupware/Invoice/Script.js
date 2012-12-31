function CheckType() //sprawdza typ faktury i blokuje lub odblokowuje pole tekstowe na VAT
{
    var radios = document.getElementsByTagName('input');
    var value;
    for (var i = 0; i < radios.length; i++)
    {
        if (radios[i].type === 'radio' && radios[i].checked)
        {
            value = radios[i].value;       
        }
    }
    if (value === 'ar')
    {
        document.getElementById('vat').disabled = true;
    }
    else
    {
        document.getElementById('vat').disabled = false;
    }
}
$(function()
{
    $('.date-pick').datePicker({startDate:'01/01/2012'});
    $('#to').datePicker().val(new Date().asString()).trigger('change');
    CheckType();
});
