<?php
 /* resolve easyload.io
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

$filelink="https://easyload.io/e/K0NW5BAJdJ";
if (strpos($filelink,"easyload.io") !== false) {
  $filelink=str_replace("/f/","/e/",$filelink);
  $ua = $_SERVER['HTTP_USER_AGENT'];
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $filelink);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_REFERER,"https://vidcloud9.com");
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h = curl_exec($ch);
  curl_close($ch);
  $t1=explode('exdata="',$h);
  $t2=explode('"',$t1[1]);
  $e=$t2[0];
  $x=base64_decode(base64_decode($e));
  $y=json_decode($x,1);
  $src=$y['streams'][0]['src'];
  $out="";
  $t="15";
  for ($i=0;$i<strlen($src);$i++) {   // thanks to tvaddonsco
     $out .=chr(ord($src[$i]) ^ ord($t[$i% strlen($t)]));
  }
  if (strpos($out,"http") !== false)
    $link=$out;
  if ($link && strpos($link,".m3u8") === false)   // for subtitles
    $link=$link."/v.mp4";
  if ($flash <> "flash" && $link) $link =$link."|Referer=".urlencode("https://".$host);
  if (isset ($y['subtitles'][0]['src']))   // ???????  not sure...
    $srt = $y['subtitles'][0]['src'];
}
echo $link;
?>
