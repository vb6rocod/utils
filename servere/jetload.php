<?php
 /* resolve jetload
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

$filelink="https://jetload.net/e/UIn06HSYSKY6?autoplay=yes";
if (strpos($filelink,"jetload.net") !== false) {
  include ("rec.php");
  $ua = $_SERVER['HTTP_USER_AGENT'];
  $filelink=str_replace("/f/","/e/",$filelink);
  preg_match("/e\/([a-zA-Z0-9_]+)/",$filelink,$m);
  $id=$m[1];
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $filelink);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:22.0) Gecko/20100101 Firefox/22.0');
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_ENCODING, "");
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h = curl_exec($ch);
  curl_close($ch);
  if (preg_match('/([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.(srt|vtt)))/', $h, $s)) {
  $srt=$s[1];
  if ($srt && strpos($srt,"http") === false) $srt="https://jetload.net/".$srt;
  if (strpos($srt,"empty.srt") !== false) $srt="";
  }
  $site_key="6Lc90MkUAAAAAOrqIJqt4iXY_fkXb7j3zwgRGtUI";
  $co="aHR0cHM6Ly9qZXRsb2FkLm5ldDo0NDM.";
  $sa="secure_url";
  $loc="https://jetload.net";
  $rec=rec($site_key,$co,$sa,$loc);
  $l="https://jetload.net/jet_secure";
  $post='{"token":"'.$rec.'","stream_code":"'.$id.'"}';
  $head=array('Accept: application/json, text/plain, */*',
  'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
  'Accept-Encoding: deflate',
  'Content-Type: application/json;charset=utf-8',
  'Content-Length: '.strlen($post).'',
  'Origin: https://jetload.net',
  'Connection: keep-alive',
  'Referer: '.$filelink.'');
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
  curl_setopt ($ch, CURLOPT_POST, 1);
  curl_setopt ($ch, CURLOPT_POSTFIELDS, $post);
  $h = curl_exec($ch);
  curl_close($ch);
  $r=json_decode($h,1);
  $link=$r['src']['src'];
}
echo $link;
?>
