<?php
 /* resolve filemoon
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

$filelink="https://filemoon.sx/e/re09uiwgwgve?c1_file=https://seriale-online.net/subtitrarifilme/tt11291274.vtt&c1_label=Romana";
// https://filemoon.to/e/dk86vk0iouf2?sub.info=https://fmovies.to/ajax/episode/subtitles/0b5d2788859f1833c7f39eb5f5b02122?&autostart=true
$filelink="https://furher.in/e/tndogrdpl8p0";
if (preg_match("/filemoon\.|moonmov\.pro|furher\.|truepoweroflove\.|kerapoxy\./",$filelink)) {
   $ua="Mozilla/5.0 (Windows NT 10.0; rv:81.0) Gecko/20100101 Firefox/81.0";
   $t1=explode("?",$filelink);
   $host="https://".parse_url($t1[0])['host'];
  if (preg_match('/(\/\/[\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.(srt|vtt)))/', $filelink, $s))
    $srt="https:".$s[1];
   require_once("JavaScriptUnpacker.php");
   $ua="Mozilla/5.0 (Windows NT 10.0; rv:81.0) Gecko/20100101 Firefox/81.0";
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $filelink);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_USERAGENT, $ua);
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
   curl_setopt($ch, CURLOPT_ENCODING,"");
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
   curl_setopt($ch, CURLOPT_TIMEOUT, 25);
   $h = curl_exec($ch);
   curl_close($ch);
   //echo $h;
  $out="";
  if (preg_match("/eval\(function\(p,a,c,k,e,[r|d]?/",$h)) {
  $jsu = new JavaScriptUnpacker();
  preg_match_all("/eval\(function.*?\<\/script/s",$h,$m);
  for ($k=0;$k<count($m[0]);$k++) {
  $out .= $jsu->Unpack($m[0][$k]);
  }
  }
  $out=$h." ".$out;
  if (preg_match("/sources\:\s*\[\{file\:\"([^\"]+)\"/",$out,$m))
    $link=$m[1];
  if ($link) {
    $link=$link."|Referer=".urlencode($host)."&Origin=".urlencode($host);
    $link .="&User-Agent=".urlencode("Mozilla/5.0 (Windows NT 10.0; rv:81.0) Gecko/20100101 Firefox/81.0");
  }
}
echo $link;
?>
