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
function decode_code($code){
    return preg_replace_callback(
        "@\\\(x)?([0-9a-f]{2,3})@",
        function($m){
            return chr($m[1]?hexdec($m[2]):octdec($m[2]));
        },
        $code
    );
}

if (strpos($filelink,"mystream.") !== false || strpos($filelink,"mstream.cloud") !==false) {
  require_once('AADecoder.php');
  include ("jj.php");
  $h=file_get_contents($filelink);
  if (strpos($h,"Video not found") === false) {
  $h1=AADecoder::decode($h);
  if (preg_match('/([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.(srt|vtt)))/', $h1, $s))
  $srt=$s[1];
  $pat="/setAttribute\(\'src\', *\'(http.+?mp4)\'\)/";
  $pat1="/setAttribute\(\'src\',\s*vv\(key/";
  if (preg_match($pat, $h1, $m)) {
  $link=$m[1];
  } elseif (preg_match($pat1, $h1)) {
   $h1=decode_code($h1);
   $t1=explode("atob('",$h1);
   $t2=explode("'",$t1[1]);
   $encoded=$t2[0];
   if (preg_match("/(var\s+(_0x[a-z0-9]+))\=\[(\'[a-zA-Z0-9\=\+\/]+\'\,?)+\]/ms", $h1, $m)) {
        $php_code = str_replace($m[1], "\$c0", $m[0]) . ";";
        eval($php_code);
        //print_r ($c0);
        /* rotate with 0xd0 search (_0x1107,0xd0)) */
        $pat = "/\(" . $m[2] . "\,(0x[a-z0-9]+)/";
        if (preg_match($pat, $h, $n)) {
            $x = hexdec($n[1]);
            for ($k = 0; $k < $x; $k++) {
                array_push($c0, array_shift($c0));
            }
        }
   }
   print_r ($c0);
   echo "<BR>";
   $t1=explode('<script>',$h1);
   $t2=explode('</script',$t1[1]);
   $z=trim($t2[0]);
   $z = jjdecode($z);
   $t1=explode("atob('",$z);
   $t2=explode("'",$t1[1]);
   $dec=base64_decode($t2[0]);   // 1147|window.bqtk0='r9u1d9g3q7m4z8y7s8o7p3e7';|1903
   $t1=explode("'",$dec);
   $hash=$t1[1];
/*
var l = c2('0x0');
var n = c2('0x1');
var h = 'hgsylgxvxpnfpcytjgtyinridhbavmgehjfv';
*/
/*
var l=_0x2a75('0x0');
var n=_0x2a75('0x1');
var h=_0x2a75('0x2');
*/
   if (preg_match("/var l=_0x[a-z0-9]+\(\'0x(\d+)\'\)/",$h1,$p)) {
     $l=$c0[$p[1]];
   } else {
     preg_match("/var l=\'(\S+)\'/",$h1,$q);
     $l=$q[1];
   }
   if (preg_match("/var n=_0x[a-z0-9]+\(\'0x(\d+)\'\)/",$h1,$p)) {
     $n=$c0[$p[1]];
   } else {
     preg_match("/var n=\'(\S+)\'/",$h1,$q);
     $n=$q[1];
   }
   if (preg_match("/var h=_0x[a-z0-9]+\(\'0x(\d+)\'\)/",$h1,$p)) {
     $h=$c0[$p[1]];
   } else {
     preg_match("/var h=\'(\S+)\'/",$h1,$q);
     $h=$q[1];
   }

   echo $l."<BR>".$n."<BR>".$h."<BR>";

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
  } else {
   $link="";
  }
  } else {
   $link="";
  }
}
echo $link;
?>
