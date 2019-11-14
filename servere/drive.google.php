<?php
 /* resolve drive.google
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

$filelink="https://drive.google.com/file/d/1yNs4OjXCugk0CddF07xvaIEasxrLkb8V/view";
if (strpos($filelink,"drive.google.com") !== false) {
  $cookie="drive.dat";
  $pat = '@google.+?([a-zA-Z0-9-_]{20,})@';
  preg_match($pat,$filelink,$m);
  $id=$m[1];
  $l="https://drive.google.com/file/d/".$id."/view";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
  curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
  curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
  curl_setopt($ch, CURLOPT_HEADER, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h = curl_exec($ch);
  curl_close($ch);

  $x=file_get_contents($cookie);
  preg_match("/NID\s+(\S+)/",$x,$t);
  preg_match("/DRIVE_STREAM\s+(\S+)/",$x,$t1);
  $head=array("Cookie: NID=".$t[1]."; DRIVE_STREAM=".$t1[1]);
  $ad="NID=".$t[1]."; DRIVE_STREAM=".$t1[1];
  $sPattern = '@\["fmt_stream_map","([^"]+)"]@';
  preg_match($sPattern,$h,$m);
  $videos=explode(",",$m[1]);
  $a_itags=array(37,22,18);
  foreach ($videos as $video) {
   preg_match("/(\d+)\|(\S+)/",$video,$m);
   $links[$m[1]] = $m[2];
  }
  if (isset($links[37]))
    $link=$links[37];
  elseif (isset($links[22]))
    $link=$links[22];
  elseif (isset($links[18]))
    $link=$links[18];
  else
    $link="";
  $link = utf8_decode(implode(json_decode('["'.$link.'"]')));
  if ($link && $flash != "flash")
     $link=$link."|Cookie=".urlencode($ad);  // MX Player
}
echo $link;
?>
