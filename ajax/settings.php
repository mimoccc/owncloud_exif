<?php

use OCA\Exif\ExifConfig;

OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('exif');

$active_parameters = explode(',', $_POST['active_parameters']);

$config = new ExifConfig(OCP\USER::getUser());
$config->setActiveParameters($active_parameters);

OCP\JSON::success(array('data' => array('message' => "Changed Config", 'foo'=>$active_parameters)));
