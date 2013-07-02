<?php

namespace OCA\Exif;

use OC_Cache;

class ExifLoader {

	const CACHE_TTL = 3600;

	/**
	 * @var string
	 */
	private $_path;

	/**
	 * @param string$path
	 */
	public function __construct($path) {
		$this->_path = $path;
	}

	/**
	 * @todo caching
	 * @return array
	 */
	public function getExifData() {
		$cache_key = sprintf('exif__%s', $this->_path);
		if (OC_Cache::hasKey($cache_key)) {
			return OC_Cache::get($cache_key);
		}

		$data = exif_read_data($this->_path, 0, false);

		OC_Cache::set($cache_key, $data, self::CACHE_TTL);

		return $data;
	}
}

