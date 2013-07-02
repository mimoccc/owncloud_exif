<?php

OC::$CLASSPATH['OCA\Exif\ExifLoader'] = 'exif/lib/ExifLoader.php';
OC::$CLASSPATH['OCA\Exif\ExifFormatter'] = 'exif/lib/ExifFormatter.php';
OC::$CLASSPATH['OCA\Exif\ExifConfig'] = 'exif/lib/ExifConfig.php';

OCP\Util::addscript('exif', 'exif');

OCP\App::registerPersonal('exif', 'settings');