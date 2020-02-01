<?php
/* resolve mystream
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
$filelink = "https://embed.mystream.to/pufpln9x8ejh";
$filelink = "https://embed.mystream.to/edqddehi2hrh";
function vv($a157,$a158) {
  $a159 = array();
  $a160 = 0x0;
  $a161="";
  $a162 = '';
  for($a163 = 0x0; $a163 < 0x100; $a163++) {
    $a159[$a163] = $a163;
  }
  for($a163 = 0x0; $a163 < 0x100; $a163++) {
    $a160 = ($a160 + $a159[$a163] + ord($a157[$a163 % strlen($a157)])) % 0x100;
    $a161 = $a159[$a163];
    $a159[$a163] = $a159[$a160];
    $a159[$a160] = $a161;
  }
  $a163 = 0x0;
  $a160 = 0x0;
  for($a191 = 0x0; $a191 < strlen($a158); $a191++) {
    $a163 = ($a163 + 0x1) % 0x100;
    $a160 = ($a160 + $a159[$a163]) % 0x100;
    $a161 = $a159[$a163];
    $a159[$a163] = $a159[$a160];
    $a159[$a160] = $a161;
    $a162 .= chr(ord($a158[$a191]) ^ $a159[($a159[$a163] + $a159[$a160]) % 0x100]);
  }
  return $a162;
}


if (strpos($filelink,"mystream.") !== false || strpos($filelink,"mstream.") !==false) {
  require_once('AADecoder.php');
  include ("obfJS.php");
  include ("jj.php");
  $k=0;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $filelink);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; rv:65.0) Gecko/20100101 Firefox/65.0");
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_ENCODING, "");
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
  curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  while ($k<5) {
   $h = curl_exec($ch);
   if (preg_match("/Video not found/",$h)) break;
   $h=decode_code($h);
   $h1=AADecoder::decode($h);
   $pat="/setAttribute\(\'src\', *\'(http.+?mp4)\'\)/";
   if (preg_match($pat,$h1)) break;
   $pat1="/setAttribute\(\'src\',\s*vv\(key,\s*atob\(\'(\S+)\'/";
   if (preg_match($pat1,$h1)) break;
   sleep(1);
   $k++;
  }
  curl_close($ch);
  if (strpos($h1,"Video not found") === false) {
  if (preg_match('/([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.(srt|vtt)))/', $h1, $s))
  $srt=$s[1];
  $pat="/setAttribute\(\'src\', *\'(http.+?mp4)\'\)/";
  if (preg_match($pat, $h1, $m)) {
    $link=$m[1];
    if (strpos($link,"http") === false && $link) $link="https:".$link;
  } else {
    $pat1="/setAttribute\(\'src\',\s*vv\(key,\s*atob\(\'(\S+)\'/";
    if (preg_match($pat1, $h1,$q))
     $encoded=$q[1];
    else
     $encoded="";
    $hash="";
    $t1=explode("<script>",$h1);
    $t2=explode("</script",$t1[1]);
    if ($z1 = jjdecode($t2[0])) {
     $z1=preg_replace_callback(
      "/atob\(\'(.*?)\'\)/ms",
      function ($matches) {
       return base64_decode($matches[1]);
      },
      $z1
     );
    preg_match("/(\w{20,})/",$z1,$p);
    $hash=$p[1];
   }
   $t2=explode("</script",$t1[2]);
   $enc=$t2[0];
   $dec=obfJS();
   $h=$l=$n="";
   if(preg_match("/var\s*h\=\'(\w+)\'/ms",$dec,$m))
    $h=$m[1];
   if (preg_match("/var\s*l\=\'(\w+)\'/ms",$dec,$m))
    $l=$m[1];
   if (preg_match("/var\s*n\=\'(\w+)\'/ms",$dec,$m))
    $n=$m[1];
   $test=array();
   for($j = 0x0; $j < strlen($l); $j++) {
     for($k = 0x0; $k < strlen($n); $k++) {
       $test[$l[$j].$n[$k]] = $h[$j + $k];
     }
   }
   $key="";
   for($i = 0x0; $i < strlen($hash); $i += 0x2) {
      $key = $key.$test[$hash[$i].$hash[$i + 1]];
   }
   $link=vv($key,base64_decode($encoded));
   if (strpos($link,"http") === false && $link) $link="https:".$link;
  }
  } else
    $link="";
}
echo $link;
?>
