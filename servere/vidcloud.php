<?php
 /* resolve vidcloud
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

$filelink="https://vidcloud.co/v/5ca178320ea84/the.walking.dead.s09e16.web.h264-tbs.mkv";
if (strpos($filelink,"vidcloud.co") !== false || strpos($filelink,"vidcloud.online") !== false) {
  $pattern = '/(?:\/\/|\.)((?:vidcloud\.co|loadvid\.online))\/(?:embed|v)\/([0-9a-zA-Z]+)/';
  preg_match($pattern,$filelink,$m);
  $id=$m[2];
  $l="https://vidcloud.co/player?fid=".$id."&page=video";
  /*
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:22.0) Gecko/20100101 Firefox/22.0');
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h2 = curl_exec($ch);
  curl_close($ch);
  */
  $h2=file_get_contents($l);   // ???? why ?????????  with curl don't work on android!
  $h2=str_replace("\\","",$h2);
  $h2=str_replace("\n","",$h2);
  $link=str_between($h2,'file":"','"');
}
echo $link;
?>
