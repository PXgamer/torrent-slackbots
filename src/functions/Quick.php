<?php
namespace pxgamer\TorrentSlackBots\Functions;

class Quick
{

	public static function mksize ($s, $precision = 2) {
		$suf = array("B", "kB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB");

		for ($i = 1, $x = 0; $i <= count($suf); $i++, $x++) {
			if ($s < pow(1024, $i) || $i == count($suf)) // Change 1024 to 1000 if you want 0.98GB instead of 1,0000MB
				return number_format($s/pow(1024, $x), $precision)." ".$suf[$x];
		}
	}

	public static function parseTurl($url) {
		$url = explode('/', $url);
		
		return (object)[
			"id" => $url[4],
			"url" => "https://extra.to/torrent/" . $url[4],
			"dl" => "https://extra.to/download/" . $url[4]
		];
	}

}