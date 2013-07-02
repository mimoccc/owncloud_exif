<?php

namespace OCA\Exif;

use OCP;

class ExifConfig {

	const CONFIG_PARAMETER_KEY = 'active_parameters';

	/**
	 * @var string
	 */
	private $_uid;

	/**
	 * @param string $uid
	 */
	public function __construct($uid) {
		$this->_uid = $uid;
	}

	/**
	 * @return string
	 */
	public function getActiveParameters() {
		$active_parameters = OCP\Config::getUserValue($this->_uid, 'exif', self::CONFIG_PARAMETER_KEY, '');
		if (!$active_parameters) {
			return ExifFormatter::getDefaultSettings();
		}
		return explode(',', $active_parameters);
	}

	/**
	 * @param array $active_parameters
	 */
	public function setActiveParameters(array $active_parameters) {
		$active_parameters = implode(',', $active_parameters);
		OCP\Config::setUserValue($this->_uid, 'exif', self::CONFIG_PARAMETER_KEY, $active_parameters);
	}
}