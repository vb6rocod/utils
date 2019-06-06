
<?php
/* resolve movpod,daclips
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
* $filelink = "https://movpod.in/9hhueiilr5kb";
* $link --> video_link
*/
function str_between($string, $start, $end){
	$string = " ".$string; $ini = strpos($string,$start);
	if ($ini == 0) return ""; $ini += strlen($start); $len = strpos($string,$end,$ini) - $ini;
	return substr($string,$ini,$len);
}
$filelink = "https://movpod.in/9hhueiilr5kb";
if (strpos($filelink, "daclips.") !== false || strpos($filelink, "movpod.") !== false)
	{

	// https://movpod.in/9hhueiilr5kb
	// https://movpod.in/c2b3k9wa9ysj
	// http://daclips.in/ulmwt4acqp4n

	$pattern = '/((daclips|movpod)\.(?:in|com|net))\/(?:embed-)?([0-9a-zA-Z]+)/';
	preg_match($pattern, $filelink, $m);
	$url = parse_url($filelink);
	$filelink = "https://" . $url["host"] . "/" . $m[3];
	$ch = curl_init($filelink);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_REFERER, $filelink);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; rv:66.0) Gecko/20100101 Firefox/66.0');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	curl_setopt($ch, CURLOPT_ENCODING, "");
	$h = curl_exec($ch);
	curl_close($ch);
	$id = str_between($h, '"id" value="', '"');
	$fname = str_between($h, '"fname" value="', '"');
	$post = "op=download1&usr_login=&id=" . $id . "&fname=" . $fname . "&referer=&channel=&method_free=Free+Download";
	sleep(5);
	$head = array(
		'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
		'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
		'Accept-Encoding: gzip, deflate, br',
		'Content-Type: application/x-www-form-urlencoded',
		'Content-Length: ' . strlen($post) . '',
		'Cookie: __test'
	);
	$ch = curl_init($filelink);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_REFERER, $filelink);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; rv:66.0) Gecko/20100101 Firefox/66.0');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	curl_setopt($ch, CURLOPT_ENCODING, "");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$h = curl_exec($ch);
	curl_close($ch);
	if (preg_match('/([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.mp4))/', $h, $m)) $link = $m[1];
	  else $link = "";
	}

?>
