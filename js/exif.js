
$(function() {
	if (typeof FileActions!=='undefined') {
		FileActions.register('image', 'exif', OC.PERMISSION_READ, '/apps/exif/img/exif.png', function(filename) {
			var file = $('#dir').val() + "/" + filename;

			$.getJSON(OC.filePath('exif', 'ajax', 'get_data.php'), {file:file}).then(function(data) {
				var file_tr = FileActions.currentFile.parent();

				var title = "";
				$.each(data.exif, function(key, value) {
					title += value.name +": " + value.value + "<br />";
				});

				file_tr.attr('title', title).tipsy({'html': true}).tipsy('show');
			});
		});
	}
});


