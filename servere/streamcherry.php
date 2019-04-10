<?php
/* resolve streamcherry
* Copyright (c) 2019 vb6rocod
*
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
* examples of usage :
* $filelink = input file
* $link --> video_link
*/
$filelink = "https://streamcherry.com/f/dksoskcasnqrqden/t";

if (strpos($filelink, "streamcherry.com") !== false)
	{

	// https://streamcherry.com/embed/pdslqaopmlomfrql/

	function indexOf($hack, $pos)
		{
		$ret = strpos($hack, $pos);
		return ($ret === FALSE) ? -1 : $ret;
		}

	function decode($encoded, $code)
		{
		$a1 = "";
		$k = "=/+9876543210zyxwvutsrqponmlkjihgfedcbaZYXWVUTSRQPONMLKJIHGFEDCBA";
		$count = 0;
		for ($index = 0; $index < strlen($encoded); $index++)
			{
			while ($count <= strlen($encoded) - 1)
				{
				$b1 = indexOf($k, $encoded[$count]);
				$count++;
				$b2 = indexOf($k, $encoded[$count]);
				$count++;
				$b3 = indexOf($k, $encoded[$count]);
				$count++;
				$b4 = indexOf($k, $encoded[$count]);
				$count++;
				$c1 = (($b1 << 2) | ($b2 >> 4));
				$c2 = ((($b2 & 15) << 4) | ($b3 >> 2));
				$c3 = (($b3 & 3) << 6) | $b4;
				$c1 = $c1 ^ $code;
				$a1 = $a1 . chr($c1);
				if ($b3 != 64) $a1 = $a1 . chr($c2);
				if ($b3 != 64) $a1 = $a1 . chr($c3);
				}
			}

		return $a1;
		}

	$ua = $_SERVER['HTTP_USER_AGENT'];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $filelink);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, $ua);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_ENCODING, '');
	curl_setopt($ch, CURLOPT_REFERER, "https://streamcherry.com");
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	$h1 = curl_exec($ch);
	curl_close($ch);
	if (preg_match("@type:\"video/([^\"]+)\",src:d\('([^']+)',(.*?)\).+?height:(\d+)@", $h1, $m))
		{
		$a = $m[2];
		$b = $m[3];
		$rez = decode($a, $b);
		$rez = str_replace("@", "", $rez);
		if (strpos($rez, "http") === false) $rez = "http:" . $rez;
		}
	  else
		{
		$rez = "";
		}

	$link = $rez;
	}

echo $link;
?>
