<?php
class FileSize
{
	public static function format($bytes)
	{
		if($bytes == 0)
			return '0 B';		

		$sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
		$i = floor(log($bytes, 1024));

		return round($bytes/ pow(1024, $i), 2) . ' ' . $sizes[$i];
	}
}
?>