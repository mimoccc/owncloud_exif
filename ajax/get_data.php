<?php

use OCA\Exif\ExifFormatter;
use OCA\Exif\ExifLoader;
use OCA\Exif\ExifConfig;

OCP\JSON::checkLoggedIn();

$file = $_GET['file'];
$localfile = \OC\Files\Filesystem::getLocalFile($file);

$exif_loader = new ExifLoader($localfile);
$settings = new ExifConfig(\OCP\USER::getUser());

$exif_data = $exif_loader->getExifData();

if (!$exif_data) {
	throw new \Exception(sprintf("Could not load file: %s", $localfile));
}

$exif_formatter = new ExifFormatter($exif_data);

$exif_data = $exif_formatter->getFormattedExifData($settings->getActiveParameters());

\OCP\JSON::success(array('exif' => $exif_data));
