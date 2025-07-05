<?php
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_WARNING);
//work out some values based on components of the path.
//assumes that the application is installed at C:/FalconOnline.
//array keys will need to be modified if installed at a different location.
$sitePath = str_replace('\\', '/', realpath('../'));
$pathArr = explode('/', $sitePath);

//echo $sitePath;
if ( strpos(PHP_OS, "WIN") === false ) {
	array_shift($pathArr);
	$basePath = '/'.$pathArr[0] . '/' . $pathArr[1] . '/' . $pathArr[2] . '/' . $pathArr[3];
	//set the include path based on what we worked out above
	$inclPath  = '.:..:';
	$inclPath .=  $basePath . '/lib/ZendFramework-1.12.20/library:';
	$inclPath .=  $basePath . '/lib';
}
else {
	$basePath = $pathArr[0] . '/' . $pathArr[1] . '/' . $pathArr[2]  . '/' . $pathArr[3];
	//set the include path based on what we worked out above
	$inclPath  = '.;..;';
	$inclPath .=  $basePath . '/lib/ZendFramework-1.12.20/library;';
	$inclPath .=  $basePath . '/lib';
}
//echo $inclPath;
ini_set('include_path', $inclPath);

include('bootstrap.php');
?>
