<fieldset class="personalblock">
	<legend>Exif Settings <input type="button" value="Toggle" id="exif_settings_toggle"/></legend>
	<div id="exif_settings" style="display:none">
		<? foreach($_['supported_types'] as $exif_type => $name): ?>
			<input type="checkbox" class="exif_active_parameters" value="<?= p($exif_type)?>" <?= in_array($exif_type, $_['active_parameters']) ? 'checked="checked"': ''?>/>  <?= p($name)?><br />
		<? endforeach ?>
		<input id="exif_edit_button" type="button" value="Edit"/>
	</div>
</fieldset>
