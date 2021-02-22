<?php
/* resolve userload
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
$filelink = "https://userload.co/embed/42f856c1b175/tt0115697.mp4?c1_file=https://seriale-online.net/subtitrarifilme/tt0115697.vtt&c1_label=Romana";

if (strpos($filelink,"userload.") !== false) {
  include ("AADecoder.php");
  require_once("JavaScriptUnpacker.php");
  $l="https://userload.co/api/assets/userload/js/videojs.js";
  $ua = $_SERVER['HTTP_USER_AGENT'];
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h = curl_exec($ch);
  curl_close($ch);
  $jsu=new AADecoder();
  $out = $jsu->decode($h);
  $morocco1="";
  $mycountry1="";
  if (preg_match("/.send\(\"morocco\=\"\+(\w+)\+\"\&mycountry\=\"\+(\w+)/",$out,$m)) {
   $morocco1=$m[1];
   $mycountry1=$m[2];
  }
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $filelink);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_REFERER,"https://userload.co");
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h = curl_exec($ch);
  curl_close($ch);

  if (preg_match("/kind\=\"captions\" src\=\"(.*?)\"/si",$h,$s))
    $srt=$s[1];
  $t1=explode('div class="video-div"',$h);
  $h=$t1[1];
  $jsu = new JavaScriptUnpacker();
  $out = $jsu->Unpack($h);
  $pat="/var\s*".$morocco1."\s*\=\"(\w+)/";
  if (preg_match($pat,$out,$m))
   $morocco=$m[1];
  $pat="/var\s*".$mycountry1."\s*\=\"(\w+)/";
  if (preg_match($pat,$out,$m))
   $mycountry=$m[1];

  $l="https://userload.co/api/request/";
  $post="morocco=".$morocco."&mycountry=".$mycountry;
  $head=array('Accept: */*',
   'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
   'Accept-Encoding: deflate',
   'Content-Type: application/x-www-form-urlencoded',
   'Content-Length: '.strlen($post).'',
   'Origin: https://userload.co',
   'Connection: keep-alive',
   'Referer: '.$filelink);
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
  curl_setopt ($ch, CURLOPT_POST, 1);
  curl_setopt ($ch, CURLOPT_POSTFIELDS, $post);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h = curl_exec($ch);
  curl_close($ch);
  if (strpos($h,"http") === false)
    $link="https://userload.co".trim($h);
  else
    $link=trim($h);
}
echo $link;
?>
