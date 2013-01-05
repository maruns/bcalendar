$(function()
{
    var match = RegExp('[?&]date=([^&]*)').exec(window.location.search);
    match ? decodeURIComponent(match[1].replace(/\+/g, ' ')) : null;
    $('.date-pick').datePicker({startDate:'01/01/2012'});alert(new Date().asString());
    $('#start').datePicker().val(new Date().asString()).trigger('change');
});