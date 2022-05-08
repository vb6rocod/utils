<?php
 /* resolve vidcloud9 and alias
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

$filelink="https://vidembed.cc/streaming.php?id=MzU1OTA2&title=Finch&typesub=SUB&sub=L2ZpbmNoL2ZpbmNoLnYxLnZ0dA==&cover=Y292ZXIvZmluY2gucG5n";
if (preg_match("/vidnext\.net|vidnode\.net|vidembed\.(net|cc|io)|\/vidcloud9\.|membed\.net/",$filelink)) {
  require_once("aes.php");
  $t1=explode("&",$filelink);
  $rest=$t1[1];
  $x=parse_url($filelink);
  $host=$x['host'];
  parse_str($x['query'],$y);
  $id=$y['id'];
  $id1=$id;
  unset($y['id']);
  $q=http_build_query($y);
  // see https://vidembed.io/js/player2021.min.js?v=7.5
  $key = '25746538592938496764662879833288';
  $iv="5641039825516312";   // random
  $key="25742532592138496744665879883281";     // new 10.03.2022
  $iv="9225679083961858";
  $aes = new Aes($key, 'CBC', $iv);
  $out="";
  for ($k=0;$k<strlen($id);$k++) {
   $out .="%08";
  }
  $id = $id.$out;
  $e=urldecode($id);
  $y = $aes->encrypt($e);
  $enc=base64_encode($y);
  $l="https://vidembed.io/encrypt-ajax.php?id=".$enc."&".$rest."&c=aaaaaaaa&refer=none&time=52564103982551631204";
  $l="https://vidembed.io/encrypt-ajax.php?id=".$enc."&".$q."&c=aaaaaaaa&refer=https://vidembed.cc&alias=".$id1;  // new 10.03.2022
  //echo $l;
  $head=array('Accept: application/json, text/javascript, */*; q=0.01',
   'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
   'Accept-Encoding: deflate',
   'Referer: https://'.$host.'/',
   'X-Requested-With: XMLHttpRequest',
   'Connection: keep-alive');

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; rv:71.0) Gecko/20100101 Firefox/71.0');
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_HTTPHEADER,$head);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h2 = curl_exec($ch);
  curl_close($ch);
  
  $r=json_decode($h2,1);
  $x=$r['data'];
  $x=base64_decode($x);
  $y = $aes->decrypt($x);
  $y = preg_replace('/[[:^print:]]/', '', $y);

  $r=json_decode($y,1);

  if (isset($r['source'][0]['file'])) {
   $c=count($r['source'])-1;
   if (preg_match("/auto/i",$r['source'][$c]['label']) && $c>1) $c=$c-1;
   $link= $r['source'][$c]['file'];
  }

  if (isset($r['track']['tracks']['file']))
   $srt=$r['track']['tracks']['file'];
  elseif (isset($r['track']['tracks'][0]['file']))
   $srt=$r['track']['tracks'][0]['file'];
}
echo $link;
/*
from player2021.min.js
var a8 = 0x0,
    a9 = a1(0x1a2),
    a10 = a1(0x1af),
    a11 = a1(0x1ad),
    a12 = a9 + a11 + a10, -->  key
    a13 = a1(0x1a1),
    a14 = a1(0x1d5),
    a15 = a1(0x1c4),
    a16 = a13 + a15 + a14;  --> iv

function c2() {
    var a54 = ['/img/download.svg', '602960syjQch', '&alias=', 'open', '11345724lpDLja', 'attr', '1384967446658', 'advertising', '79883281', 'getPosition', '7zPRkRT', '36SEGGMj', 'decrypt', 'setup', '321652OsVqYG', '<svg xmlns=\"http://www.w3.org/2000/svg\" class=\"jw-svg-icon jw-svg-icon-rewind2\" viewBox=\"0 0 240 240\" focusable=\"false\"><path d=\"m 25.993957,57.778 v 125.3 c 0.03604,2.63589 2.164107,4.76396 4.8,4.8 h 62.7 v -19.3 h -48.2 v -96.4 H 160.99396 v 19.3 c 0,5.3 3.6,7.2 8,4.3 l 41.8,-27.9 c 2.93574,-1.480087 4.13843,-5.04363 2.7,-8 -0.57502,-1.174985 -1.52502,-2.124979 -2.7,-2.7 l -41.8,-27.9 c -4.4,-2.9 -8,-1 -8,4.3 v 19.3 H 30.893957 c -2.689569,0.03972 -4.860275,2.210431 -4.9,4.9 z m 163.422413,73.04577 c -3.72072,-6.30626 -10.38421,-10.29683 -17.7,-10.6 -7.31579,0.30317 -13.97928,4.29374 -17.7,10.6 -8.60009,14.23525 -8.60009,32.06475 0,46.3 3.72072,6.30626 10.38421,10.29683 17.7,10.6 7.31579,-0.30317 13.97928,-4.29374 17.7,-10.6 8.60009,-14.23525 8.60009,-32.06475 0,-46.3 z m -17.7,47.2 c -7.8,0 -14.4,-11 -14.4,-24.1 0,-13.1 6.6,-24.1 14.4,-24.1 7.8,0 14.4,11 14.4,24.1 0,13.1 -6.5,24.1 -14.4,24.1 z m -47.77056,9.72863 v -51 l -4.8,4.8 -6.8,-6.8 13,-12.99999 c 3.02543,-3.03598 8.21053,-0.88605 8.2,3.4 v 62.69999 z\"></path></svg>', 'Forward 10 Seconds', 'indexOf', 'seek', 'encrypt', 'substr', 'random', 'play', 'data', 'crossorigin', 'stringify', 'value', 'Download Video', 'removeItem', '90839', '30634BJVKUC', 'ready', '19151cDMGOp', 'source_bk', 'endsWith', 'getDuration', 'error', 'AES', 'track', '183ZjKDEQ', '493654uPbiOf', '3975054ORDInO', '560AOTLzB', '_blank', 'Utf8', 'video', '61858', '15bpkbyR', 'parse', 'tracks', 'script[data-name=\'crypto\']', '922567', '25742532592', 'addButton', 'Next 10s', 'enc', 'firstFrame'];
    c2 = function() {
        return a54;
    };
    return c2();
}
*/
?>
