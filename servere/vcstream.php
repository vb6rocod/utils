<?php
/* resolve vcstream
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
$filelink = "https://vcstream.to/embed/5b979c9fa292b/Strangers.S01E01.720p.HDTV.x264-ORGANiC.mp4";

if (strpos($filelink, "vcstream.to") !== false)
	{
	$cookie = $base_cookie . "vcstream.dat";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $filelink);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; rv:61.0) Gecko/20100101 Firefox/61.0');
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_ENCODING, "");
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	$h = curl_exec($ch);
	curl_close($ch);
	$t1 = explode("url: '/", $h);
	$t2 = explode("'", $t1[1]);
	$l1 = "https://vcstream.to/" . $t2[0];
	$head = array(
		'User-Agent: Mozilla/5.0 (Windows NT 10.0; rv:61.0) Gecko/20100101 Firefox/61.0',
		'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
		'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
		'Accept-Encoding: gzip, deflate, br'
	);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $l1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_ENCODING, "");
	curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	$h = curl_exec($ch);
	curl_close($ch);
	$h = str_replace("\\", "", $h);
	$t1 = explode('file":"', $h);
	$t2 = explode('"', $t1[1]);
	$link = $t2[0];
	if (preg_match('/([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.(srt|vtt)))/', $h, $m)) $srt = $m[1];
	}

echo $link;
?>
