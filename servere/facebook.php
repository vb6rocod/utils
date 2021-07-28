<?php
/* resolve facebook
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
$filelink = "https://www.facebook.com/video/embed?video_id=";

if (strpos($filelink, "facebook") !== false)
	{
	$pattern = '/(video_id=|videos\/)([0-9a-zA-Z]+)/';
	preg_match($pattern, $filelink, $m);
	$filelink = "https://www.facebook.com/video/embed?video_id=" . $m[2];
	$filelink="https://www.facebook.com/watch/live/?v=".$m[2]."&ref=watch_permalink";
	$ua = "Mozilla/5.0 (Windows NT 10.0; rv:81.0) Gecko/20100101 Firefox/81.0";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $filelink);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, $ua);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	$h1 = curl_exec($ch);
	curl_close($ch);
	$h1 = urldecode(str_replace("\\", "", $h1));
    if (preg_match("/(?:hd_src|sd_src)\:\"([^\"]+)\"/",$h1,$m))
      $link=$m[1];
    else
      $link="";
	}

echo $link;
?>
