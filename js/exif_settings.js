
$(function() {

	$('#exif_settings_toggle').click(function(){
		$('#exif_settings').toggle();
	});

	$('#exif_edit_button').click(function() {

		var active_parameters = [];
		$('.exif_active_parameters:checked').each(function() {
			active_parameters.push($(this).val());
		});

		active_parameters = active_parameters.join(',');

		console.log(active_parameters);

		$.post(OC.filePath('exif', 'ajax', 'settings.php'), {active_parameters:active_parameters}).then(function(data) {
			console.log(data);
		});

	});
});