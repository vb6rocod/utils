<?php
 /* resolve streamvid
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

$filelink="https://streamvid.co/player/pi5Mk6un7tIap1k/";
if (strpos($filelink,"streamvid.co") !== false) {
  $flash="flash";
function unjuice($source) {
  $juice = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
  $pat='@JuicyCodes.Run\(([^\)]+)@';
  if (preg_match($pat,$source,$m)) {
  $e=preg_replace('/\"\s*\+\s*\"/',"",$m[1]);
  $e=preg_replace('/[^A-Za-z0-9+\\/=]/',"",$e);
  $t = "";
  $n=$r=$i=$s=$o=$u=$a=$f=0;
  while ($f < strlen($e)) {
    $s = strpos($juice,$e[$f]);$f+=1;
    $o = strpos($juice,$e[$f]);$f+=1;
    $u = strpos($juice,$e[$f]);$f+=1;
    $a = strpos($juice,$e[$f]);$f+=1;
    $n = $s << 2 | $o >> 4; $r = (15 & $o) << 4 | $u >> 2; $i = (3 & $u) << 6 | $a;
    $t .= chr($n);
    if (64 != $u) $t .= chr($r);
    if (64 != $a) $t .= chr($i);
  }
  return $t;
  }
  return $source;
}
  $ua = $_SERVER['HTTP_USER_AGENT'];
  $host=parse_url($filelink)['host'];
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $filelink);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch,CURLOPT_REFERER,"https://".$host);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h = curl_exec($ch);
  curl_close($ch);

  $t1=explode('id="video_player">',$h);
  $h=$t1[1];
  $t=unjuice($h);

  require_once("JavaScriptUnpacker.php");
  $jsu = new JavaScriptUnpacker();
  $out = $jsu->Unpack($t);

  if (preg_match("/\/\/[a-zA-Z0-9\/\_\:\.\?\-]+\.m3u8/",$out,$m)) {
    $subs=array();
    $link="https:".$m[0];
    if (preg_match_all("/file\"\:\"([a-zA-Z0-9\/\_\:\.]+)\"\,\"label\"\:\"([a-zA-Z0-9]+)\"\,\"kind\"\:\"captions\"/msi",$out,$m)) {
     for ($k=0;$k<count($m[0]);$k++) {
      $subs[$m[2][$k]]=$m[1][$k];
     }
     if (isset($subs['Romanian']))
      $srt=$subs['Romanian'];
     elseif (isset($subs['English']))
      $srt=$subs['English'];
    }
    if ($link && $flash <> "flash") {  // flash="flash" for jwplayer
      if (substr($link, -1) =="/") $link=substr($link, 0, -1);
      $link=$link."|Referer=".urlencode("https://".$host);
    } else {
      if (substr($link, -1) =="/") $link=substr($link, 0, -1);
      $link=$link;
    }
 } else
   $link="";
}
echo $link;
?>
