<?php
/* resolve thevideobee
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
$filelink = "https://thevideobee.to/4r0n95knvkbz.html";

if (strpos($filelink, "thevideobee.to") !== false)
	{

	// https://thevideobee.to/4r0n95knvkbz.html

	$pattern = '/(?:\/\/|\.)(thevideobee\.to)\/(?:embed-|)?([0-9a-zA-Z]+)/';
	preg_match($pattern, $filelink, $m);
	$filelink = "https://thevideobee.to/" . $m[2] . ".html";
	$ch = curl_init($filelink);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_REFERER, $filelink);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:22.0) Gecko/20100101 Firefox/22.0');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // RETURN THE CONTENTS OF THE CALL
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	$h = curl_exec($ch);
	curl_close($ch);
	$id = str_between($h, 'name="id" value="', '"');
	$fname = str_between($h, 'name="fname" value="', '"');
	$referer = str_between($h, 'referer" value="', '"');
	$hash = str_between($h, 'name="hash" value="', '"');
	$usr_login = str_between($h, 'usr_login" value="', '"');
	$post = "op=download1&usr_login=" . $usr_login . "&id=" . $id . "&fname=" . urlencode($fname) . "&referer=" . $referer . "&hash=" . $hash . "&imhuman=Proceed+to+video";
	$head = array(
		'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
		'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
		'Accept-Encoding: deflate',
		'Content-Type: application/x-www-form-urlencoded',
		'Content-Length: ' . strlen($post) . ''
	);
	sleep(1);
	$ch = curl_init($filelink);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_REFERER, $filelink);
	curl_setopt($ch, CURLOPT_USERAGENT, $ua);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // RETURN THE CONTENTS OF THE CALL
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	$h = curl_exec($ch);
	curl_close($ch);
	if (preg_match('/[src="]((http|https)[\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.(mp4|m3u8)))/', $h, $m)) $link = $m[1];
	  else $link = "";
	if (preg_match('/([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.vtt|\.srt))/', $h, $m))
		{
		$srt = $m[1];
		if (strpos($srt, "empty.srt") !== false) $srt = "";
		if ($srt)
			{
			if (strpos($srt, "http") === false) $srt = "https://thevideobee.to/" . $srt;
			}
		}
	}

echo $link;
?>
