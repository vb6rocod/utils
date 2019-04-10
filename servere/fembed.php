<?php
/* resolve fembed
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
$filelink = "https://www.fembed.com/v/4lo0jr-px9q";

if (strpos($filelink, "fembed.") !== false)
	{

	// https://www.fembed.com/v/4lo0jr-px9q
	// $ua = player user_agent !!!!

	if ($flash == "flash") $ua = $_SERVER['HTTP_USER_AGENT'];
	  else
		{
		$ua = 'Mozilla/5.0(Linux;Android 7.1.2;ro;RO;MXQ-4K Build/MXQ-4K) MXPlayer/1.8.10';
		$ua = 'Mozilla/5.0(Linux;Android 10.1.2) MXPlayer';
		}

	preg_match("/v\/([\w\-]*)/", $filelink, $m);
	$id = $m[1];
	$l = "https://www.fembed.com/api/source/" . $id;
	$post = "r=";
	$url = $l;
	$data = array(
		'r' => ''
	);
	$options = array(
		'http' => array(
			'header' => "Content-type: application/x-www-form-urlencoded\r\n",
			'method' => 'POST',
			'content' => http_build_query($data) ,
		)
	);
	$context = stream_context_create($options);
	$h3 = @file_get_contents($url, false, $context);
	$r = json_decode($h3, 1);
	if (isset($r["captions"][0]["path"]))
		{
		if (strpos($r["captions"][0]["path"], "http") === false) $srt = "https://www.fembed.com/asset" . $r["captions"][0]["path"];
		  else $srt = $r["captions"][0]["path"];
		}

	$c = count($r["data"]);
	if (strpos($r["data"][$c - 1]["file"], "http") === false) $l1 = "https://www.fembed.com" . $r["data"][$c - 1]["file"];
	  else $l1 = $r["data"][$c - 1]["file"];
	$h4 = @get_headers($l1);
	foreach($h4 as $key => $value)
		{
		if (preg_match("/Location/", $value))
			{
			$t1 = explode("Location:", $value);
			$t2 = explode("\n", $t1[1]);
			$link = trim($t2[0]);
			break;
			}
		}
	}

echo $link;
?>
