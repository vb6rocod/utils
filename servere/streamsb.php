<?php
 /* resolve streamsb and alias
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

$filelink="https://sbplay2.com/e/jxf4aynwz1cs.html";
$filelink="https://cloudemb.com/e/grstev3m144o.html";
$filelink="https://cloudemb.com/e/mwuaso93r0j7.html";
if (preg_match("/sbfull\.|sbfast\.|sbembed\.com|sbembed1\.com|sbplay\.org|sbvideo\.net|streamsb\.net|sbplay\.one|cloudemb\.com|playersb\.com|tubesb\.com|sbplay\d\.|embedsb\.com/",$filelink)) {
  // lot of alias ....
  $pattern = "/(?:\/\/|\.)((?:tube|player|cloudemb|stream|sbfast|sbfull)?s?b?(?:embed\d?|embedsb\d?|play\d?|video)?\.(?:com|net|org|one|\w+))\/(?:embed-|e|play|d)?\/?([0-9a-zA-Z]+)/";
  preg_match($pattern,$filelink,$m);
  $host=$m[1];
  $id=$m[2];
  $l="https://".$host."/e/".$id.".html";

  $ua="Mozilla/5.0 (Windows NT 10.0; rv:87.0) Gecko/20100101 Firefox/87.0";
  $head=array('Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
  'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
  'Accept-Encoding: deflate',
  'Connection: keep-alive',
  'Upgrade-Insecure-Requests: 1');

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch,CURLOPT_REFERER,$filelink);
  curl_setopt($ch, CURLOPT_HTTPHEADER,$head);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
  curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  $h = curl_exec($ch);
  if (preg_match("/app\.?(min\.)?v?\d+?\.?(\d+)?\.js/",$h,$m)) {
    $l="https://".$host."/js/".$m[0];
    curl_setopt($ch, CURLOPT_URL, $l);
    $h1 = curl_exec($ch);
    if (preg_match("/\'(ces\w{2,3})\'/",$h1,$m)) {
     $alias="sour".$m[1];
     //print_r ($m);
    } else {
     $alias="sourcessx34";
     $alias="sourcesx38";
     $alias="sources48";
    }

  }
  curl_close($ch);
  if (preg_match('/(\/\/[\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.(srt|vtt)))/', $filelink." ".$h, $s)) {
    $srt="https:".$s[1];
  }
    function enc($a) {
     $b="";
     for ($k=0;$k<strlen($a);$k++) {
      $b .=dechex(ord($a[$k]));
     }
    return $b;
    }
    function makeid($a) {
     $b="";
     $c="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
     for ($k=0;$k<$a;$k++) {
      $b .=$c[rand(0,61)];
     }
     return $b;
    }

     $x=makeid(12)."||".$id."||".makeid(12)."||"."streamsb";
     $c1=enc($x);
     $x=makeid(12)."||".makeid(12)."||".makeid(12)."||"."streamsb";

     $c2=enc($x);
     $x=makeid(12)."||".$c2."||".makeid(12)."||"."streamsb";
     $c3=enc($x);

     $l="https://".$host."/".$alias."/".$c1."/".$c3;

     $ua="Mozilla/5.0 (Windows NT 10.0; rv:95.0) Gecko/20100101 Firefox/95.0";
     $head=array('Accept: application/json, text/plain, */*',
     'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
     'Accept-Encoding: deflate',
     'watchsb: sbstream',
     'Connection: keep-alive',
     'Referer: https://'.$host);

     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, $l);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_USERAGENT, $ua);
     curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
     curl_setopt($ch, CURLOPT_HTTPHEADER,$head);
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
     curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
     curl_setopt($ch, CURLOPT_TIMEOUT, 25);
     $h = curl_exec($ch);
     curl_close($ch);

     $x=json_decode($h,1);
     $link=$x['stream_data']['file'];
}
echo $link;
?>
