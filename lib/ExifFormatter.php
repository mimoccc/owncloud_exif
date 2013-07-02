<?php

namespace OCA\Exif;

use OC_Helper;

class ExifFormatter {

	/**
	 * @var array
	 */
	private $_exif_data;

	/**
	 * @return string[]
	 */
	public static function getSupportedTypes() {
		return  array(
			'CompressedBitsPerPixel' => 'Compressed Bits Per Pixel',
			'ColorSpace' => 'Color space',
			'Contrast' => 'Contrast',
			'ExifVersion' => 'ExifVersion',
			'ExposureTime' => 'ExposureTime',
			'ExposureProgram' => 'ExposureProgram',
			'FNumber' => 'FNumber',
			'FileDateTime' => 'FileDateTime',
			'FileName' => 'FileName',
			'FileSize' => 'FileSize',
			'FileType' => 'FileType',
			'Flash' => 'Flash',
			'FocalLength' => 'FocalLength',
			'ISOSpeedRatings' => 'Iso',
			'Model' => 'Camera Model',
			'MimeType' => 'MimeType',
			'Orientation' => 'Orientation',
			'WhiteBalance' => 'WhiteBalance',
			'DateTimeOriginal' => 'DateTimeOriginal',
			'Software' => 'Software',

			'GPSPosition' => 'GPS position',
			'Solution' => 'Solution',
		);
	}

	/**
	 * @return string[]
	 */
	public static function getDefaultSettings() {
		return array(
			'Solution',
			'ExposureTime',
			'FNumber',
			'ISOSpeedRatings',
			'FocalLength'
		);
	}

	/**
	 * @param array $exif_data
	 */
	public function __construct(array $exif_data=array()) {
		$this->_exif_data = $exif_data;
	}

	/**
	 * @return array[]
	 */
	public function getFormattedExifData(array $parameters) {
		$formatted_data = array();

		foreach(self::getSupportedTypes() as $exif_type => $name) {
			if (!in_array($exif_type, $parameters)) {
				continue;
			}

			$value = isset($this->_exif_data[$exif_type]) ? $this->_exif_data[$exif_type] : null;
			$formatte_value = $this->_formatParameter($exif_type, $value);

			$formatted_data[$exif_type] = array(
				'name' => $name,
				'value' => $formatte_value,
				'original' => $value
			);
		}

		return $formatted_data;
	}

	/**
	 * @param string $exif_type
	 * @param string $value
	 * @return string mixed
	 */
	private function _formatParameter($exif_type, $value) {
		$method = "_formatParameter$exif_type";
		if (method_exists($this, $method)) {
			return $this->$method($value);
		}
		return $value;
	}


	/**
	 * @param string $value
	 * @return float
	 */
	private function _formatNumber($value) {
		if (strpos($value, '/') !== false) {
			list($dividend, $divisor) = explode('/', $value);
			return (int)$dividend / (int)$divisor;
		}
		return (float)$value;
	}

	/**
	 * @param string $value
	 * @return string
	 */
	private function _formatParameterCompressedBitsPerPixel($value) {
		return $this->_formatNumber($value);
	}

	/**
	 * @param string $value
	 * @return string
	 */
	private function _formatParameterFileDateTime($value) {
		return date('r', $value);
	}
	/**
	 * @param string $value
	 * @return string
	 */
	private function _formatParameterFileSize($value) {
		$byte = $this->_formatNumber($value);
		return OC_Helper::humanFileSize($byte);
	}

	/**
	 * @param string $value
	 * @return string
	 */
	private function _formatParameterFNumber($value) {
		return sprintf('f%g', $this->_formatNumber($value));
	}

	/**
	 * @param string $value
	 * @return string
	 */
	private function _formatParameterFocalLength($value) {
		return sprintf('%gmm', $this->_formatNumber($value));
	}

	/**
	 * @param string $value
	 * @return string
	 */
	private function _formatParameterExposureTime($value) {
		$seconds = $this->_formatNumber($value);
		if ($seconds < 0.1) {
			return sprintf('1/%ds', 1/$seconds);
		} else {
			return sprintf('%gs', $seconds);
		}
	}

	private function _formatParameterGPSPosition($value) {
		if (!isset($this->_exif_data['GPSLatitude'])) {
			return '-unknown-';
		}

		$latitude = $this->_exif_data['GPSLatitude'];
		$longitude = $this->_exif_data['GPSLongitude'];

		return sprintf('Lat: %s Long: %s', $this->_formatGPS($latitude), $this->_formatGPS($longitude));
	}

	/**
	 * @return string
	 */
	private function _formatParameterSolution() {
		$length = $this->_formatNumber($this->_exif_data['ExifImageLength']);
		$width = $this->_formatNumber($this->_exif_data['ExifImageWidth']);

		return sprintf("%dx%d", $width, $length);
	}

	/**
	 * @return string
	 */
	private function _formatParameterFileType($file_type) {
		// http://php.net/manual/de/function.exif-imagetype.php
		switch($file_type) {
			case IMAGETYPE_GIF:
				return 'GIF';
			case IMAGETYPE_JPEG:
				return 'JPEG';
			case IMAGETYPE_PNG:
				return 'PNG';
			case IMAGETYPE_TIFF_II:
			case IMAGETYPE_TIFF_MM:
				return 'TIFF';

			default:
				return sprintf('unknow type: %d', $file_type);
		}

	}

	/**
	 * @param array $section
	 * @return string
	 */
	private function _formatGPS(array $section) {
		$degreese = $this->_formatNumber($section[0]);
		$minutes = $this->_formatNumber($section[1]);
		$seconds = $this->_formatNumber($section[2]);

		if ($seconds) {
			return sprintf("%d° %d' %d''", $degreese, $minutes, $seconds);
		} else {
			return sprintf("%d° %d'", $degreese, $minutes);
		}
	}

	/**
	 * @param string $orientation
	 * @return string
	 */
	private function _formatParameterOrientation($orientation) {
		switch($orientation) {
			case 1:
				return "original";
			case 2:
				return "0° flipped";
			case 3:
				return "180°";
			case 4:
				return "180° flipped";
			case 5:
				return "90° flipped";
			case 6:
				return "270°";
			case 7:
				return "270° flipped";
			case 8:
				return "90°";
			default:
				return 'Unknown';
		}
	}
}
