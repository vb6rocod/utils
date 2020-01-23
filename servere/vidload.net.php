<?php
/* resolve vidload.net
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
$filelink = "https://www.vidload.net/e/29d045cffe0231a4";

if (strpos($filelink,"vidload.net") !== false) {
  $cookie="videomega.dat"; // very similar
  require_once("JavaScriptUnpacker.php");
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
  $h=curl_exec($ch);
  curl_close($ch);
  curl_close($ch);

  $ids=array();
  // href="xcxcc" id="ererer"
  preg_match_all("/href\s*\=\s*[\'|\"](.*?)[\'|\"].*?id\s*\=\s*[\'|\"](\w+)[\'|\"]/ms",$h,$m);
  for ($k=0;$k<count($m[2]);$k++) {
   $ids[$m[2][$k]]=$m[1][$k];
  }
  // type="hidden" id="5e29af3b6ac6b" value="
  preg_match_all("/type\=\"hidden\"\s*id=\"(.*?)\"\s*value\=\"(.*?)\"/ms",$h,$m);
  for ($k=0;$k<count($m[2]);$k++) {
   $ids[$m[1][$k]]=$m[2][$k];
  }
  // bigbangass=document.getElementById("
  $rep=array();
  preg_match_all("/(\w+)\s*\=\s*document\.getElementById\([\'\"](\w+)[\'\"]/ms",$h,$m);
  for ($k=0;$k<count($m[1]);$k++) {
    $rep[$m[1][$k]]=$ids[$m[2][$k]];
  }
  // xhr.send("myreason="+tnaketalikom+"&saveme="+fuckoff)
  preg_match("/xhr\.send\s*\((.*?)\)/ms",$h,$m);
  $p=preg_replace("/(\"|\'|\s|\+)/","",$m[1]);
  parse_str($p,$o);
  $a=array();
  foreach($o as $key => $value) {
    $a[$key]=$rep[$value];
  }
  $post=http_build_query($a);
  //echo $post;
  preg_match("/streamurl\/[\'|\"]\s*\+\s*(\w+)/ms",$h,$m);
  $id=$m[1];
  $str_url=$rep[$id];
  $l="https://www.vidload.net/streamurl/".$str_url."/";
  //echo $l;
  $head=array('Accept: */*',
  'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
  'Accept-Encoding: deflate',
  'Content-Type: application/x-www-form-urlencoded',
  'Content-Length: '.strlen($post).'',
  'Origin: https://www.vidload.net',
  'Connection: keep-alive',
  'Referer: '.$filelink.'');
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,0);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
  curl_setopt ($ch, CURLOPT_POST, 1);
  curl_setopt ($ch, CURLOPT_POSTFIELDS, $post);
  curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $l = curl_exec($ch);
  curl_close($ch);
  //echo "\n".$l;
  $l="https://www.vidload.net".$l;
  $head=array('Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
  'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
  'Accept-Encoding: deflate',
  'Connection: keep-alive',
  'Referer: '.$filelink.'',
  'Upgrade-Insecure-Requests: 1');
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, trim($l));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
  curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h3 = curl_exec($ch);
  curl_close($ch);
  //echo $h3;
  $jsu = new JavaScriptUnpacker();
  $out = $jsu->Unpack($h3);

  $out .=$h3;
  if (preg_match('/http.+\.mp4/', $out, $m)) {
  $link=$m[0];
  if (preg_match_all('/([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_\,\)\(\s\[\+\]]*(\.(srt|vtt)))\" srclang=\"\S+\" label=\"(.*?)\"/', $out, $s))
  //print_r ($s);
  $srts=array();
  if (isset($s[4])) {
    for ($k=0;$k<count($s[4]);$k++) {
      $srts[$s[4][$k]] = $s[1][$k];
    }
  }
  if (isset($srts["Romanian"]))
    $srt=$srts["Romanian"];
  elseif (isset($srts["Romana"]))
    $srt=$srts["Romana"];
  elseif (isset($srts["English"]))
    $srt=$srts["English"];
  } else {
    $link="";
  }
}
echo $link;
?>
