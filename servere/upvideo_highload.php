<?php
 /* resolve upvideo.to and highload.to
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
function hunter($h, $u, $n, $t, $e, $r) {
    $r = "";
    for($i = 0; $i < strlen($h);$i++) {
        $s = "";
        while($h[$i] !== $n[$e]) {
            $s .= $h[$i];
            $i++;
        }
        for($j = 0; $j < strlen($n);$j++) {
          $s=str_replace($n[$j],$j,$s);
        }
        $r .= chr(abc($s, $e, 10) - $t);
    }
    return $r;
}
function abc($d, $e, $f) {
    $g = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ+/';
    $h = substr($g,0, $e);
    $i = substr($g,0, $f);
    $x=strrev($d);
    $a=0;
    $j=0;
    for ($m=0;$m<strlen($x);$m++) {
      $j +=strpos($h,$x[$m])*pow($e,$m);
    }
    $k = '';
    while($j > 0) {
        $k = $i[$j % $f].$k;
        $j = ($j - ($j % $f)) / $f;
    }
    return $k;
}
$filelink="https://highload.to/e/ftmwkj1ab3gp";
if (preg_match("/highload\.to|upvideo\.to/",$filelink)) {
  $host=parse_url($filelink)['host'];
  if (preg_match("/highload/",$host))
   $l="https://highload.to/assets/js/master.js";
  else
   $l="https://upvideo.to/assets/js/tabber.js";
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; rv:65.0) Gecko/20100101 Firefox/65.0");
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_ENCODING, "");
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  curl_setopt($ch, CURLOPT_URL, $l);
  $h = curl_exec($ch);
  preg_match_all("/decodeURIComponent\(escape\(r\)\)\}\((.*?)\)\)/",$h,$m);

  $out="";
  for ($k=0;$k<count($m[1]);$k++) {
   $c=str_replace('"',"",$m[1][$k]);
   $t1=explode(",",$c);
   $out .="\n".hunter($t1[0],$t1[1],$t1[2],$t1[3],$t1[4],$t1[5]);
  }
  preg_match("/var\s*res\s*\=\s*(\w+)\.replace\(\"([\w\=]+)\"/",$out,$m);

  $find=$m[1];
  $rep1=$m[2];
  preg_match("/res\.replace\(\"([\w\=]+)\"/",$out,$m);
  $rep2=$m[1];

  curl_setopt($ch, CURLOPT_URL, $filelink);
  $h = curl_exec($ch);
  curl_close($ch);
  preg_match_all("/decodeURIComponent\(escape\(r\)\)\}\((.*?)\)\)/",$h,$m);

  $out="";
  for ($k=0;$k<count($m[1]);$k++) {
   $c=str_replace('"',"",$m[1][$k]);
   $t1=explode(",",$c);
   $out .="\n".hunter($t1[0],$t1[1],$t1[2],$t1[3],$t1[4],$t1[5]);
  }
  $a="/".$find."\s*\=\s*\"([\w\=]+)\"/";

  preg_match($a,$out,$m);
  $res=$m[1];
  $res=str_replace($rep1,"",$res);
  $res=str_replace($rep2,"",$res);
  $link=base64_decode($res);
}
echo $link;
?>
