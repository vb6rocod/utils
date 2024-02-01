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
$filelink="https://moviesapi.club/movie/958196";
/* origin https://w1.moviesapi.club */

    function def($d, $e, $f) {
        $x="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ+/";
        $h=substr($x,0,$e);

        $i=substr($x,0,$f);

        $d=strrev($d);
        $a=0;
        for ($z=0;$z<strlen($d);$z++) {
         $b=$d[$z];
         if (strpos($h,$b) !== false) $a +=strpos($h,$b) * pow($e,$z);
        }
        $k = "";
        $j=$a;
        while ($j > 0) {
            $k = $i[$j % $f] . $k;
            $j = ($j - ($j % $f)) / $f;
        }
        return $k;
    }
    function player($p, $l, $a, $y, $e, $r) {
        $r = "";
        $len = strlen($p);
        for ($i = 0;  $i < $len; $i++) {
            $s = "";
            while ($p[$i] !== $a[$e]) {
                $s .= $p[$i];
                $i++;
            }
            for ($j = 0; $j < strlen($a); $j++) {
              $s=str_replace($a[$j],$j,$s);
            }

            $r .= chr(def($s, $e, 10) - $y);
        }
        return $r;
    };
  $host="https://".parse_url($filelink)['host'];

  $head=array('User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/112.0',
  'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
  'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
  'Accept-Encoding: deflate',
  'Connection: keep-alive',
  'Referer: '.$host,
  'Origin: '.$host,
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
  if (preg_match("/class\=\"vidframe\"\s+src\=\"([^\"]+)\"/",$h,$m)) {   //https://moviesapi.club/movie/447365
  curl_setopt($ch, CURLOPT_URL,$m[1]);
  $h = curl_exec($ch);
  }
  curl_close($ch);
  if (preg_match("/\=\s+\'([^\']+)\'/",$h,$m)) {
  $enc=$m[1];
  require_once("cryptoJsAesDecrypt.php");
  $js=new cryptoJsAesDecrypt();
  $out="";
  if (preg_match("/eval\(_0x[a-z0-9]+\(([^\)]+)\)/",$h,$n)) {
    $x=trim(str_replace('"',"",$n[1]));
    $z=explode(",",$x);

    $mm=player($z[0],$z[1],$z[2],$z[3],$z[4],$z[5]);
    if (preg_match("/\'([^\']+)\'/",$mm,$m))
      $pass=$m[1];
    else
      $pass="";
  $out = $js->decrypt1($pass,$enc);
  } elseif (preg_match("/\}\}\}\(_0x\w+,([^\)]+)\)\,/",$h,$m)) {
  $a="\$b=".$m[1].";";
  eval ($a);
  preg_match("/var _0x\w+\=\[([^\]]+)\]/",$h,$m);
  $a="\$c=array(".$m[1].");";
  eval ($a);
    for ($k = 0; $k < $b; $k++) {
      array_push($c, array_shift($c));
    }
  preg_match("/\'\w+\':_0x\w+\((\w+)\)\+_0x\w+\((\w+)\)/",$h,$m);
  $cc=count($c);
  for ($k=0;$k<$cc;$k++) {
    if (strlen($c[($m[1]+$k) % $cc].$c[($m[2]+$k) % $cc])==15)
    $p[]=$c[($m[1]+$k) % $cc].$c[($m[2]+$k) % $cc];
  }
  $out="";
  for ($k=0;$k<count($p);$k++) {
  $pass=$p[$k];

  $out .= $js->decrypt1($pass,$enc);
  }
  }

  $link="";
  $srt="";
  $srt1=array();
  if (preg_match("/sources\:\s*\[\{\"file\"\:\"([^\"]+)\"/",$out,$m))
   $link=$m[1];
  $ua="Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/112.0";
  $link .="|Referer=".urlencode($host)."&Origin=".urlencode($host)."&User-Agent=".urlencode($ua);
  }

echo $link;
?>
