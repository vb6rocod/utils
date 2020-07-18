<?php
 /* resolve dood.watch
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

$filelink="https://dood.watch/e/gd93oog2e3vq?c1_file=https://serialeonline.to/subtitrarifilme/tt4619908.vtt&c1_label=Romana";
$filelink="https://dood.to/e/kkw9v93qg9c964qyaf9r33fx6cnm7ybu";  // dead
//https://www.doodstream.com/d/sot4bb1da0rq
if (preg_match("/dood(stream)?\./",$filelink)) {
  function makePlay() {
   $a="";
   $t = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
   $n = strlen($t) - 1;
   for ($o = 0; 10>$o; $o++) {
    $a .= $t[rand(0,$n)];
   }
   return $a;
  }
  $filelink=str_replace("/f/","/e",$filelink);
  $filelink=str_replace("/d/","/e/",$filelink);
  include("rec.php");
  $host=parse_url($filelink)['host'];
  //$token=rec('6LeBZ_QUAAAAAFRlK-3AKsVsAhMsXme1mO_NBKpc','aHR0cDovL2Rvb2QudG86ODA.','pass_md5','https://'.$host);
  $ua     =   $_SERVER['HTTP_USER_AGENT'];
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $filelink);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_ENCODING, "");
  curl_setopt($ch, CURLOPT_HEADER,1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h = curl_exec($ch);
  curl_close($ch);
  if (preg_match_all("/location\:\s+(http.+)/i",$h,$m)) {
    $host=parse_url(trim($m[1][count($m[1])-1]))['host'];
  }
  if (preg_match('/(\/\/[\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.(srt|vtt)))/', $h, $s))
  $srt="https:".$s[1];
  if (preg_match("/pass_md5/",$h)) {
  $t1=explode('token=',$h);
  $t2=explode('&',$t1[1]);
  $tok=$t2[0];
  $t1=explode("$.get('",$h);
  $t2=explode("'",$t1[1]);
  //$l="https://".$host.$t2[0].$token;
  $l="https://".$host.$t2[0];
  $head=array('Accept: */*',
  'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
  'Accept-Encoding: deflate',
  'X-Requested-With: XMLHttpRequest',
  'Alt-Used: dood.to:443',
  'Connection: keep-alive',
  'Cookie: referer=',
  'Referer: '.$filelink);
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_HTTPHEADER,$head);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
  curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  $h1 = curl_exec($ch);
  curl_close($ch);
  if (preg_match("/http/",$h1))
   $link=$h1."?token=".$tok."&expiry=".(time()*1000);
  else
   $link="";
  } else {
   $link="";
  }
   if ($flash <> "flash" && $link) $link =$link."|Referer=".urlencode("https://".$host);
}
echo $link;
?>
