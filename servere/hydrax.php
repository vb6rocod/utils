<?php
/* resolve hydrax links, works only with jwplayer
/* https://iamcdn.net/players/jwplayer/jwplayer.v8.custom.min.js
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
* see "https://hydrax.net/"
* get "key", "slug" and origin (like https://domain.com)
* usage
* $out=hydrax($key,$slug,$origin);
* file_put_contents(hydrax.m3u8);
* in jwplayer "sources": [{"file": "http://localhost/..../hydrax.m3u8", "type":"m3u8"}],
*/
function hydrax($key,$slug,$origin) {
  set_time_limit(360); // must wait.....
  $ua = $_SERVER['HTTP_USER_AGENT'];
  $b_error=false;
  $out="";
  $l="https://multi.idocdn.com/vip";
  $post="key=".$key."&type=slug&value=".$slug;

  $head=array('Accept: */*',
  'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
  'Accept-Encoding: deflate',
  'Content-Type: application/x-www-form-urlencoded',
  'Origin: '.$origin.'',
  'Content-Length: '.strlen($post).'',
  'Connection: keep-alive');
  $ch = curl_init($l);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt ($ch, CURLOPT_POST, 1);
  curl_setopt ($ch, CURLOPT_POSTFIELDS, $post);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $html = curl_exec($ch);
  curl_close ($ch);
  $x=json_decode($html,1);

  $server=$x['servers']['redirect'][0];
  if (isset($x['fullhd']))
    $r=$x['fullhd'];
  else if (isset($x['hd']))
    $r=$x['hd'];
  else if (isset($x['sd']))
    $r=$x['sd'];
  else
    $r=array();

  $sig=$r['sig'];
  $id=$r['id'];
  $duration=$r['duration'];
  $hash=$r['hash'];
  $iv=$r['iv'];
  $links=array();
  for ($k=0;$k<count($r['ranges']);$k++) {
    for ($p=0;$p<count($r['ranges'][$k]);$p++) {
      $next=$k+$p+1;
      if ($next > count($r['ranges'])-1 ) $next=$next-$k;       //16 -> 14 17 -> 15
      if (count($r['ranges'][$k]) == 1) {
        $links[$k][$next]="https://".$server."/redirect/".$sig."/".$id."/".$r['ids'][$k]."/".$r['ids'][$next];
      } else {
        $links[$k][$next]="https://".$server."/redirect/".$sig."/".$id."/".$r['ids'][$k]."/".$r['ids'][$next];
      }
    }
  }
  $head=array('Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
  'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
  'Accept-Encoding: deflate',
  'Referer: '.$origin.'',
  'Origin: '.$origin.'',
  'Connection: keep-alive',
  'Upgrade-Insecure-Requests: 1');

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL
  curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_NOBODY,1);
  curl_setopt($ch, CURLOPT_HEADER,1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
  curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  foreach ($links as $key1=>$link) {
     foreach ($link as $key2 => $value) {
       curl_setopt($ch, CURLOPT_URL,$value);
       $html = curl_exec($ch);
       if (preg_match("/Location:\s*(\S+)/",$html,$s))
         $links[$key1][$key2]=$s[1];
       else {
         $b_error=true;
         break;
       }
     }
  }
  curl_close ($ch);
  if (!$b_error) {
  $out ="#EXTM3U"."\r\n";
  $out .="#EXT-X-VERSION:4"."\r\n";
  $out .="#EXT-X-PLAYLIST-TYPE:VOD"."\r\n";
  $out .="#EXT-X-TARGETDURATION:".$duration."\r\n";
  $out .="#EXT-X-MEDIA-SEQUENCE:0"."\r\n";
  $out .="#EXT-X-HASH:".$hash."\r\n";

  $z=0;
  for ($k=0;$k<count($r['ranges']);$k++) {
    for ($p=0;$p<count($r['ranges'][$k]);$p++) {
      $out .="#EXTINF:".$r['extinfs'][$z].","."\r\n";
      $z++;
      $next=$k+$p+1;
      if ($next > count($r['ranges'])-1 ) $next=$next-$k;
      if (count($r['ranges'][$k]) == 1) {
        $out .=$links[$k][$next]."\r\n";
      } else {
        $out .="#EXT-X-BYTERANGE:".$r['ranges'][$k][$p]."\r\n";
        $out .=$links[$k][$next]."\r\n";
      }
    }
  }
  $out .="#EXT-X-ENDLIST";
  }
  return ($out);
}
?>
