<?php
 /* resolve gamovideo
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

$filelink="http://gamovideo.com/1henwi0d5boj";
if (strpos($filelink,"gamovideo.") !== false) {
  //http://gamovideo.com/gd82bzc3i6eq
  //http://gamovideo.com/embed-gd82bzc3i6eq-640x360.html
  $ua="Mozilla/5.0 (Windows NT 10.0; rv:71.0) Gecko/20100101 Firefox/71.0";
  $pattern = '@(?:\/\/|\.)(gamovideo\.com)\/(?:embed-)?([a-zA-Z0-9]+)@';
  preg_match($pattern,$filelink,$r);
  $l="http://gamovideo.com/".$r[2];
  require_once("JavaScriptUnpacker.php");
  $head=array('Cookie: gyns=1; fbm=1; gail=1; rtn=1; luq=1; gew=1; col=1; cpl=1; sugamun=1; invn=1; pfm=1; file_id=3183849; aff=36780; gam=1');
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_ENCODING, "");
  curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h3 = curl_exec($ch);
  curl_close($ch);
  //echo $h3;
  
  $jsu = new JavaScriptUnpacker();
  $out = $jsu->Unpack($h3);

  if (preg_match('/((http|https)[\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.mp4))/', $out, $m)) {
  $link=$m[1];
  if (preg_match('/([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.(srt|vtt)))/', $out, $s))
  $srt=$s[1];
  } else {
    $link="";
  }
}
echo $link;
?>
