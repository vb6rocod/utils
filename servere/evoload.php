<?php
 /* resolve evoload.io
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

$filelink="https://evoload.io/e/wEZkuDhnkURe5j";
$filelink="https://evoload.io/e/imHpGr37G3lq8n";
if (strpos($filelink,"evoload.io") !== false) {
  $filelink=str_replace("/f/","/e/",$filelink);
  if (preg_match("/\/e\/(\w+)/",$filelink,$m))
   $code=$m[1];
  else
   $code="";
  include ("rec.php");
  $cookie= __DIR__ ."\evoload.dat";
  $ua = $_SERVER['HTTP_USER_AGENT'];
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $filelink);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_REFERER,"https://evoload.io");
  curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
  curl_setopt($ch, CURLOPT_COOKIEJAR,$cookie);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h = curl_exec($ch);
  if (preg_match('/(\/\/[\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.(srt|vtt)))/', $h, $s))
    $srt="https:".$s[1];

  $key="6Ldv2fYUAAAAALstHex35R1aDDYakYO85jt0ot-c";
  $co="aHR0cHM6Ly9ldm9sb2FkLmlvOjQ0Mw..";
  $loc="https://evoload.io";
  $token=rec($key,$co,"",$loc);
  $l="https://evoload.io/SecurePlayer";
  $post='{"code":"'.$code.'","token":"'.$token.'"}';
  $xsrf="";
  $head=array('Accept: application/json, text/plain, */*',
   'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
   'Accept-Encoding: deflate',
   'Content-Type: application/json;charset=utf-8',
   'X-XSRF-TOKEN: '.$xsrf.'',
   'Content-Length: '.strlen($post).'',
   'Origin: https://evoload.io',
   'Connection: keep-alive',
   'Referer: '.$filelink);
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
  curl_setopt ($ch, CURLOPT_POST, 1);
  curl_setopt ($ch, CURLOPT_POSTFIELDS, $post);
  $h = curl_exec($ch);
  curl_close($ch);
  $r=json_decode($h,1);
  if (isset ($r['subtitles'])) {
    $srt = $r['subtitles'][0]['system_name'];
  }
  if (isset($r['stream'])) {
   if (isset($r['stream']['backup']))
    $link=$r['stream']['backup'];
   elseif (isset($r['stream']['src']))
    $link=$r['stream']['src'];
  }
} else {
   $link="";
}
echo $link;
?>
