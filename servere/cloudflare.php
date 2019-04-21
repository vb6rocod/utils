<?php
/* resolve cloudflare
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
* get cf_clearance cookie
*/
$link = "https://tvhub.org/";
$link_old_style = "https://spacemov.cc/";
include ("cf.php");

$ua = $_SERVER['HTTP_USER_AGENT'];
$cookie = __DIR__ . "\c.txt";   // you may change this

if (file_exists($cookie)) unlink($cookie);
$head = array(
	'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
	'Accept-Language: en-US,en;q=0.5',
	'Accept-Encoding: deflate, br',
	'Connection: keep-alive',
	'Upgrade-Insecure-Requests: 1'
);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $link);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, $ua);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_HEADER, 1);
$h = curl_exec($ch);

if (strpos($h, "503 Service") !== false)
	{
	if (strpos($h, 'id="cf-dn') === false) $q = getClearanceLink_old($h, $link);
	  else $q = getClearanceLink($h, $link);
	curl_setopt($ch, CURLOPT_URL, $q);
	$h = curl_exec($ch);
	curl_close($ch);
	/*for check
	$c=file_get_contents($cookie);
	preg_match("/cf_clearance\s+[a-z0-9\-]+/",$c,$match);
	echo $match[0];
	*/
	}
  else
	{
	curl_close($ch);
	}

?>
