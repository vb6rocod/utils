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
if (preg_match("/vidnext\.net|vidnode\.net|vidembed\.(net|cc|io)|\/vidcloud9\./",$filelink)) {
  require_once("aes.php");
  $t1=explode("&",$filelink);
  $rest=$t1[1];
  $x=parse_url($filelink);
  $host=$x['host'];
  parse_str($x['query'],$y);
  $id=$y['id'];
  // see https://vidembed.io/js/player2021.min.js?v=7.5
  $key = '25746538592938496764662879833288';
  $iv="5641039825516312";   // random
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
  if (isset($r['source'][0]['file'])) {
   $c=count($r['source'])-1;
   $link= $r['source'][$c]['file'];
  }

  if (isset($r['track']['tracks']['file']))
   $srt=$r['track']['tracks']['file'];
  elseif (isset($r['track']['tracks'][0]['file']))
   $srt=$r['track']['tracks'][0]['file'];
}
echo $link;
?>
