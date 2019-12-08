<?php
/* resolve videomega
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
$filelink = "https://www.videomega.co/e/78f604df6f6786a8";

if (strpos($filelink,"videomega.") !== false) {
  $cookie="videomega.dat";
  $filelink=str_replace("/f/","/e/",$filelink);
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $filelink);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; rv:65.0) Gecko/20100101 Firefox/65.0");
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
  curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  curl_exec($ch);
  $filelink=str_replace("/e/","/js/",$filelink);
  require_once("JavaScriptUnpacker.php");
  curl_setopt($ch, CURLOPT_URL, $filelink);
  $h = curl_exec($ch);
  curl_close($ch);
  $jsu = new JavaScriptUnpacker();
  $out = $jsu->Unpack($h);
  $out .=$h;
  if (preg_match('/((http|https)[\.\d\w\-\.\/\\\:\?\&\#\%\_\,\s\[\]\(\)]*(\.mp4))/', $out, $m)) {
  $link=$m[1];
  if (preg_match_all('/([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_\,\)\(\s\[\]]*(\.(srt|vtt)))\" srclang=\"\S+\" label=\"(.*?)\"/', $out, $s))
  $srts=array();
  if (isset($s[4])) {
    for ($k=0;$k<count($s[4]);$k++) {
      $srts[$s[4][$k]] = $s[1][$k];
    }
  }
  if (isset($srts["Romanian"]))
    $srt=$srts["Romanian"];
  elseif (isset($srts["English"]))
    $srt=$srts["English"];
  } else {
    $link="";
  }
}
echo $link;
?>
