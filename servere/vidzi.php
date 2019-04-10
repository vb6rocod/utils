<?php
/* resolve vidzi
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
$filelink = "http://vidzi.tv/otefvw9e1jcl.html";

if (strpos($filelink,"vidzi.tv") !== false) {
  //http://vidzi.tv/otefvw9e1jcl.html
  require_once("JavaScriptUnpacker.php");

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $filelink);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:22.0) Gecko/20100101 Firefox/22.0');
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h2 = curl_exec($ch);
  curl_close($ch);
  $a1=explode("jwplayer.js",$h2);
  $h2=$a1[1];
  $jsu = new JavaScriptUnpacker();
  $out = $jsu->Unpack($h2);

  preg_match('/([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.mp4))/', $out, $m);
  $link=$m[1];
  if (!$link) {
  preg_match('/([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.mp4))/', $h2, $m);
  $link=$m[1];
  }
}
echo $link;
?>
