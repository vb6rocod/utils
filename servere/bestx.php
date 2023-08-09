<?php
 /* resolve bestx.stream
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

$filelink="https://bestx.stream/v/D6Rj6gBwm42V/";
if (strpos($filelink,"bestx.stream") !== false) {
  $host="https://bestx.stream";
  $head=array('User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/112.0',
  'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
  'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
  'Accept-Encoding: deflate',
  'Connection: keep-alive',
  'Referer: https://pressplay.top',
  'Origin: https://pressplay.top',
  'Upgrade-Insecure-Requests: 1');
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,$filelink);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_HTTPHEADER,$head);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
  curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  $h = curl_exec($ch);
  curl_close($ch);
  if (preg_match("/MasterJS\s*\=\s*[\'\"]([^\'\"]+)[\'\"]/i",$h,$m)) {
  $enc=$m[1];
  require_once("cryptoJsAesDecrypt.php");
  $js=new cryptoJsAesDecrypt();
  $pass="4VqE3#N7zt&HEP^a";
  $pass="11x&W5UBrcqn\$9Yl";
  $out = $js->decrypt2($pass,base64_decode($enc));
  $link="";
  $srt="";
  $srt1=array();
  if (preg_match("/sources\:\s*\[\{\"file\"\:\"([^\"]+)\"/",$out,$m))
   $link=$m[1];
  if (preg_match_all("/file\"\:\"([^\"]+)\"\,\"label\"\:\"(English|Romanian)/i",$out,$s)) {
    for ($k=0;$k<count($s[2]);$k++) {
     if (preg_match("/English/i",$s[2][$k]))
      if (!isset($srt1["English"])) $srt1["English"]=$s[1][$k];
     if (preg_match("/Romanian/i",$s[2][$k]))
      if (!isset($srt1["Romanian"])) $srt1["Romanian"]=$s[1][$k];
    }
    if (isset($srt1["Romanian"]))
     $srt=$srt1["Romanian"];
    elseif (isset($srt1["English"]))
     $srt=$srt1["English"];
  }
  if ($link && $flash <> "flash") {
  $ua="Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/112.0";
  $link .="|Referer=".urlencode($host)."&Origin=".urlencode($host)."&User-Agent=".urlencode($ua);;
  }
  }
}
echo $link;
?>
