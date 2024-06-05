<?php
 /* get youtube video (live or not)
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
 */
 function str_between($string, $start, $end){
	$string = " ".$string; $ini = strpos($string,$start);
	if ($ini == 0) return ""; $ini += strlen($start); $len = strpos($string,$end,$ini) - $ini;
	return substr($string,$ini,$len);
}
function youtube($file) {
function reverse($a,$b) {
	return  array_reverse($a);
}

function length($a,$b) {
	$tS = $a[0];
	$a[0] = $a[$b % count($a)];
	$a[$b] = $tS;
	return  $a;
}
function splice($a,$b) {
	return  array_slice($a,$b);
}
if(preg_match('/youtube\.com\/(v\/|watch\?v=|embed\/)([\w\-]+)/', $file, $match)) {
  $id = $match[2];
  $l = "https://www.youtube.com/watch?v=".$id;
  $html="";
  $p=0;
  $ua="Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:125.0) Gecko/20100101 Firefox/125.0";
  while($html == "" && $p<10) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $html = curl_exec($ch);
  curl_close($ch);
  $p++;
  }
  $t1=explode('jsUrl":"',$html);
  $t2=explode('"',$t1[1]);
  $js_url="https://www.youtube.com".$t2[0];
  
  $parts=array();
  $r1=array();
  if (strpos($html,"ytplayer.config =") !== false) {
    $html = str_between($html,'ytplayer.config = ',';ytplayer.web_player_context_config');
    $parts = json_decode($html,1);
    $r1=json_decode($parts['args']['player_response'],1);
  } else {
    $html=trim(str_between($html,'var ytInitialPlayerResponse = ',';var meta'))."";
    $parts = json_decode($html,1);
    $r1=$parts;
  }


  if (isset($r1['streamingData']["hlsManifestUrl"])) {
      $url=$r1['streamingData']["hlsManifestUrl"];
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
      curl_setopt($ch, CURLOPT_USERAGENT, $ua);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
      curl_setopt($ch, CURLOPT_TIMEOUT, 15);
      $h = curl_exec($ch);
      curl_close($ch);

      $a1=explode("\n",$h);
      if (preg_match("/\.m3u8/",$h)) {
       preg_match_all("/RESOLUTION\=(\d+)/i",$h,$m);
       $max_res=max($m[1]);
       for ($k=0;$k<count($a1);$k++) {
        if (strpos($a1[$k],$max_res) !== false) {
         $r=trim($a1[$k+1]);
         break;
        }
       }
      }
    return $r;
  } else {
  $videos=array();
  //print_r ($r1['streamingData']['formats']);
  if (isset($r1['streamingData']['formats'][0]['url'])) {
  for ($k=0;$k<count($r1['streamingData']['formats']);$k++) {
    $videos[$r1['streamingData']['formats'][$k]['itag']]=$r1['streamingData']['formats'][$k]['url'];
  }
  } else if (isset($r1['streamingData']['formats'][0]['cipher'])) {
  for ($k=0;$k<count($r1['streamingData']['formats']);$k++) {
    $t1=explode("url=",$r1['streamingData']['formats'][$k]['cipher']);
    $videos[$r1['streamingData']['formats'][$k]['itag']]=urldecode($t1[1])."&".$t1[0];
  }
  } else if (isset($r1['streamingData']['formats'][0]['signatureCipher'])) {
  for ($k=0;$k<count($r1['streamingData']['formats']);$k++) {
    $t1=explode("url=",$r1['streamingData']['formats'][$k]['signatureCipher']);
    $videos[$r1['streamingData']['formats'][$k]['itag']]=urldecode($t1[1])."&".$t1[0];
  }
  }
  if (isset($videos['37']))
    $video= $videos['37'];
  else if (isset($videos['22']))
    $video= $videos['22'];
  else if (isset($videos['18']))
    $video= $videos['18'];
  else
    $video="";
  if ($video) {
  parse_str($video, $output);
  //print_r ($output);
  if (!isset($output['s'])) {
    $r=$video;
  } else {
  $sA="";
  $s=$output["s"];
  $tip=$output["sp"];
  //$l = "https://s.ytimg.com".$parts['assets']['js'];
  //$l = "https://www.youtube.com".$parts['assets']['js'];
  $l=$js_url;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h = curl_exec($ch);
  curl_close($ch);
  preg_match("/a\=a\.split\(\"\"\);(.*?)return a\.join\(\"\"\)/",$h,$m);
  $code=$m[1];
  $pat="/(\w+\.(\w+))\((\w+)\,(\w+)\);/";
  preg_match_all($pat,$code,$m);
  $pat1=implode("|", $m[2]);
  preg_match_all("/(".$pat1.")\:function.*?(splice|length|reverse)/",$h,$f);
  global $func;   // if this file is included include ("youtube.php");
  $func = array_combine($f[1], $f[2]);
  $code=preg_replace_callback(
    "/(\w+\.(\w+))\((\w+)\,(\w+)\);/",
    function ($match) {
     global $func;
     return "\$sA=".$func[$match[2]]."(\$sA,".$match[4].");";
    },
    $code
  );
  $sA = str_split($s);
  eval ($code);
  $sA = implode($sA);
  $signature = $sA;
  $r=$video."&".$tip."=".$signature;
  }
 } else {
  $r="";
 }
 return $r;
}
} else
  return "";
}
function youtube1($file) {
if(preg_match('/youtube\.com\/(v\/|watch\?v=|embed\/)([\w\-]+)/', $file, $match)) {
  $r=array();
  $s=array();
  $out="";
  $id = $match[2];
  $l="https://en.y2mate.is/v41/";
  $head=array('User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:125.0) Gecko/20100101 Firefox/125.0',
  'Accept: application/json, text/javascript, */*; q=0.01',
  'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
  'Accept-Encoding: deflate',
  'Origin: https://en.y2mate.is',
  'Connection: keep-alive',
  'Referer: https://en.y2mate.is/');
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_HEADER, 1);
  $h = curl_exec($ch);
  curl_close($ch);

  preg_match_all("/Set-Cookie:\s*([\S]+)/i",$h,$c);
  $cookie="Cookie: ";
  for ($z=0;$z<count($c[1]);$z++) {
   $cookie .=$c[1][$z]." ";
  }
  $t1=explode('csrf-token" content="',$h);
  $t2=explode('"',$t1[1]);
  $csrf=$t2[0];

  $file = "https://www.youtube.com/watch?v=".$id;
  $l="https://y2mate.is/analyze";
  $l="https://en.y2mate.so/analyze";
  $l="https://en.y2mate.is/analyze";
  $post= "url=".$file;
  $head=array('User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:125.0) Gecko/20100101 Firefox/125.0',
  'Accept: application/json, text/javascript, */*; q=0.01',
  'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
  'Accept-Encoding: deflate',
  'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
  'Content-Length: '.strlen($post),
  'X-CSRF-TOKEN: '.$csrf,
  $cookie,
  'Origin: https://en.y2mate.is',
  'Connection: keep-alive',
  'Referer: https://en.y2mate.is/');

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POST,1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
  $h = curl_exec($ch);
  curl_close($ch);
  $r=json_decode($h,1)['formats']['video'];

  foreach ($r as $rr) {
   if ($rr['needConvert']==false)
    $s[$rr['formatId']]=$rr['url'];
  }
  if (isset($s['37']))
   $out=$s['37'];
  elseif (isset($s['22']))
   $out=$s['22'];
  elseif (isset($s['18']))
   $out=$s['18'];
  else
   $out="";
  return $out;
}
}
$l="https://www.youtube.com/embed/zCjBWYbP_wQ";
$link=youtube($l);
echo $link."<BR><BR>";
//or
$link=youtube1($l);
echo $link."<BR><BR>";

?>
