<?php

use MatthiasMullie\Minify;

class Reductor
{
	/**
	 * Minify a CSS or JS file
	 *
	 * @author salvipascual
	 * @param String $source full path the file
	 * @return String
	 */
	public function minify($source)
	{
		// get the paths
		$di = \Phalcon\DI\FactoryDefault::getDefault();
		$wwwroot = $di->get('path')['root'];
		$wwwhttp = $di->get('path')['http'];

		// get the full path to the file
		$source = "$wwwroot/public/$source";

		// create the hash of a name
		$ext = pathinfo($source)['extension']; // get ext
		$fileName = md5(file_get_contents($source)).".$ext"; // file name
		$path = "$wwwroot/public/min/$fileName"; // full path

		// minify and cache CSS files
		if($ext=="css" && ! file_exists($path))
		{
			$minifier = new Minify\CSS($source);
			$minifier->minify($path);
		}

		// minify and cache CSS files
		if($ext=="js" && ! file_exists($path))
		{
			$minifier = new Minify\JS($source);
			$minifier->minify($path);
		}

		// return http path to the minified file
		return "{$wwwhttp}/min/$fileName";
	}

	/**
	 * minify html content
	 *
	 * @param string $html
	 * @return string
	 */
	public function minifyHTML($html)
	{
		// get the paths
		$di = \Phalcon\DI\FactoryDefault::getDefault();
		$wwwroot = $di->get('path')['root'];

		// create the hash of a name
		$tmpPath = "$wwwroot/public/min/".md5($html).".html";

		// if the exist, load from cache
		if(file_exists($tmpPath)) return file_get_contents($tmpPath);

		// else minify and return
		$min = PHPWee\Minify::html($html);
		file_put_contents($tmpPath, $min);
		return $min;
	}
}
