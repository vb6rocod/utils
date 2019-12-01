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
* $l=hydrax($key,$slug,$origin, $flash);
* in jwplayer "sources": [{"file": "http://localhost/..../hydrax.m3u8", "type":"m3u8"}],
*/
function hydrax($key,$slug,$origin, $flash) {
  /* $flash == "flash" for jwplayer */
  set_time_limit(360); // for jwplayer must wait.....
  $ua = $_SERVER['HTTP_USER_AGENT'];
  $a1=$_SERVER['HTTP_REFERER'];   // if exist !!!!!!!!!!!
  $a2=explode("?",$a1);
  $hash_path = dirname($a2[0]);  // change this
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
  if (isset($x['servers'])) {
  $server=$x['servers']['redirect'][0];
  if (isset($x['fullhd']))
    $r=$x['fullhd'];
  else if (isset($x['hd']))
    $r=$x['hd'];
  else if (isset($x['sd']))
    $r=$x['sd'];
  else
    $r=array();
  if ($flash == "flash") {  // optional
    if (isset($x['sd']))
     $r=$x['sd'];
  }
  $sig=$r['sig'];
  $id=$r['id'];
  $duration=$r['duration'];
  $hash=$r['hash'];
  $iv=$r['iv'];
  file_put_contents("hash.key",base64_decode($hash)); // this is local path C:\MyDir\myloc !!!!!!!!
  $out ="#EXTM3U"."\r\n";
  $out .="#EXT-X-VERSION:4"."\r\n";
  $out .="#EXT-X-PLAYLIST-TYPE:VOD"."\r\n";
  $out .="#EXT-X-TARGETDURATION:".$duration."\r\n";
  $out .="#EXT-X-MEDIA-SEQUENCE:0"."\r\n";
  //$out .="#EXT-X-HASH:".$hash."\r\n";  Not supported by MX Player  use AES-128 style
  /* URI where is hash.key, must be a URL ex. http://localhost/myloc/hash.key
     save hash.key to same location!
  */
  $out .='#EXT-X-KEY:METHOD=AES-128,URI="'.$hash_path."/hash.key".'",IV='.$iv."\r\n";
  if ($flash == "flash") {
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
     curl_setopt($ch, CURLOPT_NOBODY,0);
     curl_setopt($ch, CURLOPT_HEADER,0);
     curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
     curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  }
  $z=0;
  for ($k=0;$k<count($r['ranges']);$k++) {
   $dur=0;
   if ($flash == "flash") {
     $l="https://".$server."/html/".$sig."/".$id."/".$r['ids'][$k]."/".$r['ids'][$k].".html?domain=".parse_url($origin)['host'];
     curl_setopt($ch, CURLOPT_URL,$l);
     $h = curl_exec($ch);
     $l_redirect=base64_decode(json_decode($h,1)['url']);
   }
    for ($p=0;$p<count($r['ranges'][$k]);$p++) {
      if ($flash == "flash") {
       $dur += $r['extinfs'][$z];
       $out .="#EXTINF:".$r['extinfs'][$z].","."\r\n";
       if (count($r['ranges'][$k]) > 1)
         $out .="#EXT-X-BYTERANGE:".$r['ranges'][$k][$p]."\r\n";
       $out .=$l_redirect."\r\n";
      } else {
      $dur += $r['extinfs'][$z];
      }
      $z++;
    }
    $tot_dur1 += $dur;
    if ($flash <> "flash") { // MX Player ignare "#EXT-X-BYTERANGE
     $out .="#EXTINF:".$dur.","."\r\n";
     $l="https://".$server."/redirect/".$sig."/".$id."/".$r['ids'][$k]."/".$r['ids'][$k];
     $out .=$l."\r\n";
    }
 }
    $out .="#EXT-X-ENDLIST";
 if ($flash == "flash") curl_close ($ch);

  if ($out) {
    file_put_contents("hydrax.m3u8",$out);
    if ($flash == "flash") {
      $link = $hash_path."/hydrax.m3u8";
    } else
      $link = $hash_path."/hydrax.m3u8"; //$link="http://127.0.0.1:8080/scripts/filme/hydrax.m3u8";
  } else {
    $link="";
  }
  } else {
    $link="";
  }
   if ($link && $flash != "flash")
     $link=$link."|Referer=".urlencode($origin)."&Origin=".urlencode($origin);  // MX Player can send headers
  return $link;
}

/*
http://localhost/mobile1/scripts/filme/hydrax.m3u8 for jwplayer
#EXTM3U
#EXT-X-VERSION:4
#EXT-X-PLAYLIST-TYPE:VOD
#EXT-X-TARGETDURATION:21
#EXT-X-MEDIA-SEQUENCE:0
#EXT-X-KEY:METHOD=AES-128,URI="http://localhost/mobile1/scripts/filme/hash.key",IV=0xb1d4330ed16cf97e8a9c73c802bd4f1b
#EXTINF:10.678045,
https://doc-0k-84-docs.googleusercontent.com/docs/securesc/ha0ro937gcuc7l7deffksulhg5h7mbp1/pf6o3sddrp0km7njoj0h20d4uhqq4c9i/1575194400000/11493598482476673579/.../1zsFbM_krKJHdX6ykJJd_eeFO9owbIMFj?e=download
#EXTINF:18.311177,
#EXT-X-BYTERANGE:900720@0
https://doc-04-2o-docs.googleusercontent.com/docs/securesc/ha0ro937gcuc7l7deffksulhg5h7mbp1/a9todbka9097pvnqp8611dbdp8loc5d0/1575194400000/13935169784296041683/.../1hmfiza6riBw-Lk_U8tvm_QtIetTh0KTn?e=download
#EXTINF:13.722958,
#EXT-X-BYTERANGE:859728@900720
https://doc-04-2o-docs.googleusercontent.com/docs/securesc/ha0ro937gcuc7l7deffksulhg5h7mbp1/a9todbka9097pvnqp8611dbdp8loc5d0/1575194400000/13935169784296041683/.../1hmfiza6riBw-Lk_U8tvm_QtIetTh0KTn?e=download
#EXTINF:16.017068,
.........
#EXTINF:16.726158,
#EXT-X-BYTERANGE:836048@7911312
https://doc-0k-3c-docs.googleusercontent.com/docs/securesc/ha0ro937gcuc7l7deffksulhg5h7mbp1/4lmlb5idl628h47olngu10ot0gqebm1d/1575201600000/01543035153859401261/.../1CdbSyBZVVjPlRPq1zXT_6tLCKEaoFJXO?e=download
#EXT-X-ENDLIST
*/

/*
http://localhost/mobile1/scripts/filme/hydrax.m3u8 for MX Player
#EXTM3U
#EXT-X-VERSION:4
#EXT-X-PLAYLIST-TYPE:VOD
#EXT-X-TARGETDURATION:10
#EXT-X-MEDIA-SEQUENCE:0
#EXT-X-KEY:METHOD=AES-128,URI="http://localhost/mobile1/scripts/filme/hash.key",IV=0xb1d4330ed16cf97e8a9c73c802bd4f1b
#EXTINF:9.4242,
https://i.william-crocker.xyz/redirect/7Uey7ipRDcLaL9DbweTYOyRJnd24ozXtniv1w8L5oiKpLSOx7UIx7UIK/VzWLV8GRVLoSXzr31Rlq87f5VMKTOgVvWPBRQpfmJQqo1rBs050MC5jwK4on/BRex6z6Cc24xM7lUgJ4bSzevmDlC92pHjAEOcRUVRzOD/BRex6z6Cc24xM7lUgJ4bSzevmDlC92pHjAEOcRUVRzOD
#EXTINF:5.004,
https://i.william-crocker.xyz/redirect/7Uey7ipRDcLaL9DbweTYOyRJnd24ozXtniv1w8L5oiKpLSOx7UIx7UIK/VzWLV8GRVLoSXzr31Rlq87f5VMKTOgVvWPBRQpfmJQqo1rBs050MC5jwK4on/BlRrfa4PU7jeTmVClWZE8OIEgo6CRpZ3M36N83jCWOpP/BlRrfa4PU7jeTmVClWZE8OIEgo6CRpZ3M36N83jCWOpP
#EXTINF:3.4194,
https://i.william-crocker.xyz/redirect/7Uey7ipRDcLaL9DbweTYOyRJnd24ozXtniv1w8L5oiKpLSOx7UIx7UIK/VzWLV8GRVLoSXzr31Rlq87f5VMKTOgVvWPBRQpfmJQqo1rBs050MC5jwK4on/BS43R7j2gJlunKVa8pj0BDeUjaVJj5ZWjzVpnSFWVS0w/BS43R7j2gJlunKVa8pj0BDeUjaVJj5ZWjzVpnSFWVS0w
#EXTINF:5.004,
.................
#EXTINF:5.004,
https://i.william-crocker.xyz/redirect/7Uey7ipRDcLaL9DbweTYOyRJnd24ozXtniv1w8L5oiKpLSOx7UIx7UIK/VzWLV8GRVLoSXzr31Rlq87f5VMKTOgVvWPBRQpfmJQqo1rBs050MC5jwK4on/BmZrjWVXRSUMUW4Nf2ek8KeRWDFWl2epjAV4f32WU7Vk/BmZrjWVXRSUMUW4Nf2ek8KeRWDFWl2epjAV4f32WU7Vk
#EXTINF:6.0048,
https://i.william-crocker.xyz/redirect/7Uey7ipRDcLaL9DbweTYOyRJnd24ozXtniv1w8L5oiKpLSOx7UIx7UIK/VzWLV8GRVLoSXzr31Rlq87f5VMKTOgVvWPBRQpfmJQqo1rBs050MC5jwK4on/BRU8RaRNWD0Eg7VEValuMSUVS3QOjRAOm2jnczFrmApv/BRU8RaRNWD0Eg7VEValuMSUVS3QOjRAOm2jnczFrmApv
#EXTINF:5.5461,
https://i.william-crocker.xyz/redirect/7Uey7ipRDcLaL9DbweTYOyRJnd24ozXtniv1w8L5oiKpLSOx7UIx7UIK/VzWLV8GRVLoSXzr31Rlq87f5VMKTOgVvWPBRQpfmJQqo1rBs050MC5jwK4on/BR2GU3QoLMOXUW47MAOGVJrqBWZZlo2RloeM9W47U2pR/BR2GU3QoLMOXUW47MAOGVJrqBWZZlo2RloeM9W47U2pR
#EXT-X-ENDLIST
*/
?>
