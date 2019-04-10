 <?php
 /* resolve powvideo "splice"
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
 * $filelink = "https://powvideo.net/o4xa8jywtx07";
 * $link --> video_link
 */
$filelink = "https://powvideo.net/o4xa8jywtx07";
require_once("JavaScriptUnpacker.php");
preg_match('/(powvideo|powvideo)\.(net|cc)\/(?:embed-|iframe-|preview-|)([a-z0-9]+)/',$filelink,$m);
$id=$m[3];
$filelink="https://povvideo.net/embed-".$id.".html";
$ua       = $_SERVER["HTTP_USER_AGENT"];
$head     = array(
    'Cookie: ref_url='.urlencode($filelink).'; BJS0=1; BJS1=1; e_'.$id.'=123456789'
);
$l="https://povvideo.net/iframe-".$id."-954x562.html";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $l);
curl_setopt($ch, CURLOPT_REFERER, "https://povvideo.net/preview-".$id."-732x695.html");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch,CURLOPT_HTTPHEADER,$head);
curl_setopt($ch, CURLOPT_USERAGENT, $ua);
curl_setopt($ch, CURLOPT_ENCODING, "");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
$h = curl_exec($ch);
curl_close($ch);

$jsu = new JavaScriptUnpacker();
$out = $jsu->Unpack($h);
if (preg_match('/([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_]*(\.mp4))/', $out, $m)) {
   $link=$m[1];
   $t1=explode("/",$link);
   $a145=$t1[3];
   $r=str_split(strrev($a145));
   /*
   array_splice($r, 3 , 1); // old
   $r[7]=array_splice($r,4 , 1, $r[7])[0];
   $r[2]=array_splice($r,1 , 1, $r[2])[0];
   $r[0]=array_splice($r,9 , 1, $r[0])[0];
   $r[5]=array_splice($r,6 , 1, $r[5])[0];
   _0x16f256[_0x4c7c('0x8', 'SGjY')](0x3, 0x2); // new
   */
   array_splice($r, 3 , 2);
   $x=implode($r);
   $link=str_replace($a145,$x,$link);
} else {
    $link = "";
}
echo $link;
?>
