<?php
 /* resolve fslink (vidguard)
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

$filelink="https://fslinks.org/e/gqM1VOPXwMOo4kY?&c1_file=http://filmeserialeonline.org/srt/tt5264838.srt&c1_label=RO&c2_file=http://filmeserialeonline.org/srt/tt5264838_EN.srt&c2_label=EN";
if (preg_match("/vgfplay\.|fslinks\.|vembed\.net|vgembed\.|vid-guard\.com|embedv\./",$filelink)) {
  require_once("AADecoder1.php");
  function decode_code1($code){
    return preg_replace_callback(
        "@\\\\(u)([0-9a-fA-F]{4})@",
        function($m){
            return mb_convert_encoding(chr($m[1]?hexdec($m[2]):octdec($m[2])),'UTF-8');
        },
        $code
    );
  }
  function kk($p,$q,$s) {   // from /assets/js/main.js?id=
   $u="";
   for ($v=0;$v<strlen($p);$v +=2) {
    $u .=chr(intval(substr($p,$v,2),16) ^ $q);
   }
   return $u;
  }
  function  getTechName($e) {  // from /assets/videojs/video.min.js?id=
   $t=array();
   for ($i=strlen($e)-1,$n=0;0<=$i;$i--,$n++) {
    $t[$n]=$e[$i];
   }
   for ($i=0;$i<count($t)-1;$i +=2) {
     $r=[$t[$i+1],$t[$i]];
     $t[$i]=$r[0];
     $t[$i+1]=$r[1];
   }
  return implode($t,"");
  }
  $host="https://".parse_url($filelink)['host'];
  parse_str(parse_url($filelink)['query'],$s);
  if (key($s)) $srt=$s[key($s)];
  $head=array('User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/112.0',
  'Accept: */*',
  'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
  'Accept-Encoding: deflate',
  'Connection: keep-alive',
  'Referer: '.$filelink,
  'Origin: '.$host,
  'Upgrade-Insecure-Requests: 1');
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,$filelink);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_ENCODING,"");
  curl_setopt($ch, CURLOPT_HTTPHEADER,$head);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
  curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  $h = curl_exec($ch);
  curl_close($ch);
  /*
  $t1=explode('videojs/ad/plugin.js?id=',$h);
  $t2=explode('"',$t1[1]);
  $id=$t2[0];
  $l=$host."/assets/videojs/ad/plugin.js?id=".$id;

  curl_setopt($ch, CURLOPT_URL,$l);
  $h1 = curl_exec($ch);
  */
  $h=decode_code1($h);
  $h=str_replace(" ","",$h);
  $h=str_replace("\/","/",$h);
  $h=str_replace("'\\\\\\\\'","'\\\\'",$h);
  $h=str_replace("'\\\\\"'","'\\\"'",$h);
  $jsu=new AADecoder();
  $out = $jsu->decode($h);
  $t1=explode('window.svg=',$out);
  $t2=explode(';")',$t1[1]);
  $rest=$t2[0];
  //$rest = substr($t1[1], 0, -1);
  $r=json_decode($rest,1);
  //print_r ($r);
  if (isset($r['stream'][0]['URL'])) {
  $q=array();
  for ($k=0;$k<count($r['stream']);$k++) {
   $q[$r['stream'][$k]['Label']]=$r['stream'][$k]['URL'];
  }
  if (isset($q['1080p']))
   $l=$q['1080p'];
  elseif (isset($q['720p']))
   $l=$q['720p'];
  elseif (isset($q['480p']))
   $l=$q['480p'];
  elseif (isset($q['360p']))
   $l=$q['360p'];
  elseif (isset($q['auto']))
   $l=$q['auto'];
  } else {
  $l=$r['stream'];
  }
  $t1=explode("sig=",$l);
  $t2=explode("&",$t1[1]);
  $token=$t2[0];
  $token1=substr(base64_decode(kk($token,2,16)),5);
  $x=substr($token1,0,strlen($token1)-5);
  $token2=getTechName($x);
  $link=str_replace($token,$token2,$l);
}
echo $link;
?>
