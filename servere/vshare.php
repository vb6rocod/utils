<?php
/* resolve vshare
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
$filelink = "https://vshare.eu/icapcl0mmrj8.htm";

if (strpos($filelink, "vshare.eu") !== false)
	{
	$pattern = '/(?:\/\/|\.)(vshare\.eu)\/(?:embed-|)?([0-9a-zA-Z]+)/';
	preg_match($pattern, $filelink, $m);
	$filelink = "https://vshare.eu/" . $m[2];
	$ch = curl_init($filelink);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_REFERER, $filelink);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:22.0) Gecko/20100101 Firefox/22.0');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	$h = curl_exec($ch);
	curl_close($ch);
	$id = str_between($h, 'name="id" value="', '"');
	$fname = str_between($h, 'name="fname" value="', '"');
	$referer = str_between($h, 'referer" value="', '"');
	$post = "op=download1&usr_login=&id=" . $id . "&fname=" . urlencode($fname) . "&referer=&method_free=Proceed+to+video";
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
	if (preg_match('/[src="]((http|https)[\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.mp4))/', $h, $m)) $link = $m[1];
	  else $link = "";
	}

echo $link;
?>
