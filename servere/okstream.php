<?php
 /* resolve okstream
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

$filelink="https://www.okstream.cc/e/6d7198848112/tt0396652.mp4";
if (strpos($filelink,"okstream.cc") !== false) {
  // https://www.okstream.cc/e/6d7198848112/tt0396652.mp4?c1_file=https://serialeonline.to/subtitrarifilme/tt0396652.vtt&c1_label=Romana
  $filelink=str_replace("/f/","/e/",$filelink);
  $ua = $_SERVER['HTTP_USER_AGENT'];
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $filelink);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h = curl_exec($ch);
  if (preg_match('/(\/\/[\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.(srt|vtt)))/', $h, $s))
  $srt="https:".$s[1];
  $t1=explode('var keys="',$h);
  $t2=explode('"',$t1[1]);
  $morocco=$t2[0];
  $t1=explode('var protection="',$h);
  $t2=explode('"',$t1[1]);
  $mycountry=$t2[0];
  $l="https://www.okstream.cc/request/";
  $post="morocco=".$morocco."&mycountry=".$mycountry;
  $head=array('Accept: */*',
   'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
   'Accept-Encoding: deflate',
   'Content-Type: application/x-www-form-urlencoded',
   'Content-Length: '.strlen($post).'',
   'Origin: https://www.okstream.cc',
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
  if (strpos($h,"http") === false)
    $link="https://www.okstream.cc".trim($h);
  else
    $link=trim($h);
  if ($link && $flash <> "flash")
   $link=$link."|Referer=".urlencode("https://www.okstream.cc");
}
echo $link;
?>
