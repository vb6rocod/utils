<?php
 /* resolve vev.io/thevideo.me need pair IP https://vev.io/pair
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

$filelink="http://vev.io/6ko92827nxrph";
if (strpos($filelink,"thevideo.me") !== false || strpos($filelink,"vev.io") !== false) {
  $pattern = '/thevideo\.me\/(?:embed-|download\/)?([0-9a-zA-Z]+)/';
  if (preg_match($pattern,$filelink,$m)) {
  $file_id=$m[1];
  //$filelink="https://thevideo.me/t7ilerxjm6ca";
  $filelink="https://thevideo.me/embed-".$file_id.".html";
  $ch = curl_init($filelink);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_REFERER, $filelink);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; rv:55.0) Gecko/20100101 Firefox/55.0');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_NOBODY,1);
  curl_setopt($ch, CURLOPT_HEADER,1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h1 = curl_exec($ch);
  curl_close($ch);
  $t1=explode("cation:",$h1);
  $t2=explode("\n",$t1[1]);
  $filelink=trim($t2[0]);
 }
 if (preg_match("/vev\.io\/(?:embed-|download\/)?([0-9a-zA-Z]+)/",$filelink,$m)) {
   $id=$m[1];
 }
  $l="https://vev.io/api/pair/".$id;
  $ua="Mozilla/5.0 (Windows NT 10.0; rv:63.0) Gecko/20100101 Firefox/63.0";
  $ch = curl_init($l);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch,CURLOPT_REFERER,"https://vev.io/");
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $html = curl_exec($ch);
  curl_close ($ch);
  $r=json_decode($html,1);

  if ($r["qualities"]) {
     foreach ($r["qualities"] as $key=>$value) {
     $link=$value;
     }
     if ($r["subtitles"]) $srt=$r["subtitles"][0];
 } else
    $link="";
}
echo $link;
?>
