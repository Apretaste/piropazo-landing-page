<?php

use MatthiasMullie\Minify;

class Reductor
{
	/**
	 * Minify a CSS or JS file
	 *
	 * @author salvipascual
	 * @param String $source: path the file
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

	/**
	 * Merge several files into one, and minify. Use CSS or JS, but not both
	 *
	 * @param Array $files: list if paths
	 * @param String $ext: 'css' or 'js'
	 * @return String
	 */
	public function compile($files, $ext)
	{
		// get the paths
		$di = \Phalcon\DI\FactoryDefault::getDefault();
		$wwwroot = $di->get('path')['root'];

		// compile into a single file
		$compilation = "";
		foreach ($files as $file)
		{
			$compilation .= file_get_contents("$wwwroot/public/$file")."\n\n";
		}

		// create the temporal path to file
		$fileName = md5($compilation).".$ext";
		$tmpFile = "$wwwroot/public/min/$fileName";

		// minify and cache compiles file
		if( ! file_exists($tmpFile))
		{
			file_put_contents($tmpFile, $compilation);
			if($ext == "css") $minifier = new Minify\CSS($tmpFile);
			if($ext == "js") $minifier = new Minify\JS($tmpFile);
			$minifier->minify($tmpFile);
		}

		// return path to compiles and minified file
		$wwwhttp = $di->get('path')['http'];
		return "$wwwhttp/min/$fileName";
	}
}
