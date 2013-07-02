<?php

use OCA\Exif\ExifConfig;
use OCA\Exif\ExifFormatter;

$settings = new ExifConfig(OCP\USER::getUser());
$l10n = OC_L10N::get('exif');

$template = new OCP\Template('exif', 'settings');
$template->assign('supported_types', ExifFormatter::getSupportedTypes());
$template->assign('active_parameters', $settings->getActiveParameters());

OCP\Util::addscript('exif', 'exif_settings');

return $template->fetchPage();