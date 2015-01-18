<?php

require_once('getid3/getid3/getid3.php');

function smartReadFile($location, $filename, $mimeType = 'application/octet-stream')
{	
	if (!file_exists($location))
	{
		header ("HTTP/1.1 404 Not Found");

		return;
	}

	$size = filesize($location);
	$time = date('r', filemtime($location));
	$fm	= @fopen($location, 'rb');

	if (!$fm)
	{
		header ("HTTP/1.1 505 Internal server error");
		return;
	}

	$begin = 0;
	$end = $size - 1;
	
	if (isset($_REQUEST['time']) && is_numeric($_REQUEST['time']))
	{
		$getID3 = new getID3;

		$data = $getID3->analyze($location);

		$bitrate = $data['bitrate'];
		$timeToTake = $_REQUEST['time'];
		$sizeToTake = min($size, ($bitrate / 8) * $timeToTake);

		$begin = max(0, rand(0, $size - $sizeToTake));
		$end = min($begin + $sizeToTake, $size);
	}

	if (isset($_REQUEST['time']))
		header('HTTP/1.1 206 Partial Content');
	else
		header('HTTP/1.1 200 OK');

	header("Content-Type: $mimeType"); 
	header('Cache-Control: public, must-revalidate, max-age=0');
	header('Pragma: no-cache');  
	header('Accept-Ranges: bytes');
	header('Content-Length:' . (($end - $begin) + 1));

	if (isset($_REQUEST['time']))
		header("Content-Range: bytes $begin-$end/$size");

	header("Content-Disposition: inline; filename=$filename");
	header("Content-Transfer-Encoding: binary");
	header("Last-Modified: $time");

	$cur = $begin;

	fseek($fm, $begin, 0);

	while(!feof($fm) && $cur <= $end && (connection_status() == 0))
	{
		print fread($fm, min(1024 * 16, ($end - $cur) + 1));
		$cur += 1024 * 16;
	}
}

$extractId = 1;

if (!empty($_REQUEST['id']))
	$extractId = intval($_REQUEST["id"]);

$extractsPath = "../telecharger/data/blind/extraits/";
$filePath = $extractsPath . "extrait" . $extractId . ".mp3";

smartReadFile($filePath, "extract.mp3", 'audio/mpeg');

?>