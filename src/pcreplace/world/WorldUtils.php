<?php

namespace pcreplace\world;

class WorldUtils
{
	/**
	 * @param string $dir
	 * @param bool $deep
	 * @param array $results
	 *
	 * @return array
	 */
	static function getDirContents(string $dir, bool $deep = false, &$results = [])
	{
		$files = scandir($dir);

		foreach ($files as $key => $value) {
			$path = realpath($dir . DIRECTORY_SEPARATOR . $value);
			if (!is_dir($path)) {
				$results[] = $path;
			} else if ($value !== '.' && $value !== '..') {
				if ($deep) {
					WorldUtils::getDirContents($path, $deep, $results);
				}
				$results[] = $path;
			}
		}

		return $results;
	}

	/**
	 * @param int $x
	 * @param int $z
	 *
	 * @return string
	 */
	static function getXZHash(int $x, int $z): string
	{
		return PHP_INT_SIZE === 8 ? (($x & 0xFFFFFFFF) << 32) | ($z & 0xFFFFFFFF) : $x . ':' . $z;
	}
}