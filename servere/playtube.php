<?php
 /* resolve playtube
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

$filelink="https://playtube.ws/embed-bfv0sg6wivjr/tt14039028.mp4.html?c1_file=https://seriale-online.net/subtitrarifilme/tt14039028.vtt&c1_label=Romana";
if (strpos($filelink,"playtube.") !== false) {
   require_once("JavaScriptUnpacker.php");
   include ("tear.php");
   $ua="Mozilla/5.0 (Windows NT 10.0; rv:81.0) Gecko/20100101 Firefox/81.0";
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $filelink);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_USERAGENT, $ua);
   curl_setopt($ch, CURLOPT_REFERER,"https://seriale-online.net/");
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
   curl_setopt($ch, CURLOPT_TIMEOUT, 25);
   $h = curl_exec($ch);
   curl_close($ch);
  $out="";
  if (preg_match("/eval\(function\(p,a,c,k,e,[r|d]?/",$h)) {
  $jsu = new JavaScriptUnpacker();
  $out = $jsu->Unpack($h);
  }
  if (preg_match('/(\/\/[\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.(srt|vtt)))/', $h.$out, $s))
    $srt="https:".$s[1];
  if (preg_match('/(file|src)\:\s*\"([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.(mp4|m3u8)))/', $out.$h, $m))
  $link=trim($m[2]);
  
  $link=""; // new version
  $t1=explode("file_code:'",$out);
  $t2=explode("'",$t1[1]);
  $file_code=$t2[0];
  $t1=explode("hash:'",$out);
  $t2=explode("'",$t1[1]);
  $hash=$t2[0];
  $l="https://playtube.ws/dl";
  $post="op=playerddl&file_code=".$file_code."&hash=".$hash;
  $head=array('Accept: */*',
   'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
   'Accept-Encoding: deflate',
   'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
   'X-Requested-With: XMLHttpRequest',
   'Content-Length: '.strlen($post),
   'Origin: https://playtube.ws',
   'Connection: keep-alive',
   'Referer: https://playtube.ws');
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_POST,1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
  curl_setopt($ch, CURLOPT_HTTPHEADER,$head);
  curl_setopt($ch, CURLOPT_ENCODING,"");
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
  curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  $w=0;
  do {
   $h = curl_exec($ch);
   $y=json_decode($h,1);
   $w++;
  } while (isset($y[0]['error']) && $w < 10);
  curl_close($ch);
  $link=$y[0]['file'];
  $seed=$y[0]['seed'];
  if ($link) {
   $out=str_replace("'",'"',$out);
   $t1=explode('var chars=',$out);
   $t2=explode(';',$t1[1]);
   $e="\$chars='".$t2[0]."';";
   eval ($e);
   $t1=explode('replace(/[',$out);
   $t2=explode("]",$t1[1]);
   $rep="/[".$t2[0]."]/";
   $x=json_decode($chars,1);
   $seed=preg_replace_callback(
    $rep,
    function ($m) {
      global $x;
      return $x[$m[0]];
    },
    $seed
   );
   $link = decrypt($link,$seed);
   $link=preg_replace_callback(
    $rep,
    function ($m) {
      global $x;
      return $x[$m[0]];
    },
    $link
   );
   $flash="flash";
   if ($link && $flash <> "flash")
    $link=$link."|Referer=".urlencode("https://playtube.ws")."&Origin=".urlencode("https://playtube.ws");
   }
}
echo $link;
?>
