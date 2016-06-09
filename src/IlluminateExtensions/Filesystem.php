<?php 

namespace SedpMis\Lib\IlluminateExtensions;

use Illuminate\Filesystem\Filesystem as IlluminateFilesystem;

class Filesystem extends IlluminateFilesystem 
{

	public function fileNamesOnly($path)
	{
		$paths = $this->files($path);
		
		foreach ($paths as $i => $path) 
		{
			$paths[$i] = str_replace('\\', '/', $path);
			$arr      = explode('/', $paths[$i]);
			$paths[$i] = $arr[count($arr)-1];
		}
		return $paths;
	}

	public function getFileName($filepath, $includeFileExt = FALSE)
	{
		$filepath = str_replace('\\', '/', $filepath);
		$arr      = explode('/', $filepath);
		$file     = $arr[count($arr)-1];
		if (!$includeFileExt) 
		{
			list($file) = explode('.', $file);
		}

		return $file;
	}

	public function dirNamesOnly($path)
	{
		$dirs = $this->directories($path);
		foreach ($dirs as $i => $dir) 
		{
			$dir = str_replace('\\', '/', $dir);
			$arr = explode('/', $dir);
			$dir = $arr[count($arr)-1];
			$dirs[$i] = $dir;
		}
		return $dirs;
	}
}
