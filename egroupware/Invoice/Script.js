$(function()
{
	$('.date-pick').datePicker({startDate:'01/01/2012'});
        $('#to').datePicker().val(new Date().asString()).trigger('change');
});
