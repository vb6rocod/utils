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
if (strpos($filelink,"dood.watch") !== false) {
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
  $ua     =   $_SERVER['HTTP_USER_AGENT'];
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $filelink);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_ENCODING, "");
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h = curl_exec($ch);
  curl_close($ch);
  if (preg_match('/(\/\/[\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.(srt|vtt)))/', $h, $s))
  $srt="https:".$s[1];
  $hash="";
  $token="";
  $mp="";
  $t1=explode('hash=',$h);
  $t2=explode('&',$t1[1]);
  $hash=$t2[0];
  $t1=explode('token=',$h);
  $t2=explode('&',$t1[1]);
  $token=$t2[0];
  $t1=explode('return a+"',$h);
  $t2=explode('"',$t1[1]);
  $mp=$t2[0];
  if ($token && $hash) {
  $l="https://dood.watch/dood?op=get_md5&hash=".$hash."&token=".$token;
  $head=array('X-Requested-With: XMLHttpRequest');
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_HTTPHEADER,$head);
  curl_setopt($ch, CURLOPT_REFERER,$filelink);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h = curl_exec($ch);
  curl_close($ch);
  $h=str_replace("\n","",$h);
  $h1=preg_replace("/\//","1",$h);
  $h1=base64_decode($h1);
  $h1=preg_replace("/\//","Z",$h1);
  $h1=base64_decode($h1);
  $h1=preg_replace("/@/","a",$h1);
  $h1=base64_decode($h1);
  if (strpos($h1,"http") !== false) {
   $link=$h1.makePlay().$mp.time()*1000;
  } else {
   $link="";
  }
  } else {
   $link="";
  }
}
echo $link;
?>
