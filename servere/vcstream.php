<?php
/* resolve vcstream / vidcloud.co
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
$filelink = "https://vcstream.to/embed/5b979c9fa292b/Strangers.S01E01.720p.HDTV.x264-ORGANiC.mp4";
//https://vidcloud.co/embed/5b979c9fa292b/Strangers.S01E01.720p.HDTV.x264-ORGANiC.mp4
if (strpos($filelink, "vcstream.to") !== false  || strpos($filelink,"vidcloud.co") !== false) {
  include ("rec.php");
  $cookie=$path_to_cookie."vcstream.dat";
  $ua = $_SERVER['HTTP_USER_AGENT'];
  preg_match("/(embed\/|fid\=)([a-zA-Z0-9]+)/",$filelink,$m);
  $filelink="https://vidcloud.co/embed/".$m[2];
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $filelink);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
  curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h = curl_exec($ch);
  curl_close($ch);
  $t1=explode('csrf-token" content="',$h);
  $t2=explode('"',$t1[1]);
  $csrf=$t2[0];
  if (preg_match('/([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.(srt|vtt)))/', $h, $m))
   $srt=$m[1];
  $site_key="6LdqXa0UAAAAABc77NIcku_LdXJio9kaJVpYkgQJ";
  $co="aHR0cHM6Ly92aWRjbG91ZC5jbzo0NDM.";
  $loc="https://vidcloud.co";
  $sa="get_player";
  $rec=rec($site_key,$co,$sa,$loc);
  $head=array('Accept: application/json, text/javascript, */*; q=0.01',
  'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
  'Accept-Encoding: deflate',
  'X-CSRF-TOKEN: '.$csrf.'',
  'X-Requested-With: XMLHttpRequest',
  'Connection: keep-alive',
  'Referer: '.$filelink.'');
  preg_match("/(embed\/|fid\=)([a-zA-Z0-9]+)/",$filelink,$m);
  $l="https://vidcloud.co/player?fid=".$m[2]."&page=embed&token=".$rec;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
  curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h = curl_exec($ch);
  curl_close($ch);
  $h=str_replace("\\","",$h);
  $t1=explode('file":"',$h);
  $t2=explode('"',$t1[1]);
  $link=$t2[0];
  if (preg_match('/([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.(srt|vtt)))/', $h, $m))
   $srt=$m[1];
  /* check to "redirect" in m3u8 to play on PC or MX Player  - optional*/
  $flash="flash"; // for jwplayer
  if (preg_match("/\/0\/playlist\.m3u8/",$link)) {
  $head=array(
  'Accept: */*',
  'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
  'Accept-Encoding: deflate',
  'Origin: https://vidcloud.co',
  'Connection: keep-alive',
  'Referer: https://vidcloud.co');
  $origin="https://vidcloud.co";
  $a1=$_SERVER['HTTP_REFERER'];
  $a2=explode("?",$a1);
  $hash_path = dirname($a2[0]); // change this
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $link);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch,CURLOPT_HTTPHEADER,$head);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  //curl_setopt($ch, CURLOPT_HEADER,1);
  $h = curl_exec($ch);
  curl_close($ch);

  if (preg_match_all("/\S+\.m3u8/",$h,$m)) {  // get max resolution
   $base1=str_replace(strrchr($link, "/"),"/",$link);
   $link=$base1.$m[0][count($m[0])-1];
  }
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $link);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch,CURLOPT_HTTPHEADER,$head);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h = curl_exec($ch);
  curl_close($ch);

  if (preg_match("/\/\/(.*?)\/redirect\/.+/",$h)) {
    $h=str_replace('URI="//','URI="https://',$h);
    if (preg_match("/URI\=\"(.*?)\"/",$h,$m) && $flash=="flash") {
    $l1=$m[1];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $l1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
    curl_setopt($ch,CURLOPT_HTTPHEADER,$head);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $h1 = curl_exec($ch);
    curl_close($ch);
    file_put_contents("hash.key",$h1);
    $h=str_replace($l1,$hash_path."/hash.key",$h);
    }
    if ($flash <> "flash") {
    preg_match("/\/\/([a-zA-Z0-9\.\-\_]+)\/redirect\/([a-zA-Z0-9]+)\/([a-zA-Z0-9]+)\/([a-zA-Z0-9]+)/mei",$h,$m);
    $serv=$m[1];
    $sig=$m[2];
    $ids=$m[3];
    $id1=$m[4];
    $id=array();
    $pat="/".$server."\/redirect\/".$sig."\/".$ids."\/([a-zA-Z0-9]+)\//mei";

    preg_match_all($pat,$h,$m);

    $id=array_values(array_unique($m[1]));

    $out="#EXTM3U"."\r\n";
    $out .="#EXT-X-VERSION:5"."\r\n";
    $out .="#EXT-X-PLAYLIST-TYPE:VOD"."\r\n";
    preg_match("/#EXT-X-TARGETDURATION:\d+/",$h,$m);
    $out .=$m[0]."\r\n";
    $out .="#EXT-X-MEDIA-SEQUENCE:0"."\r\n";
    preg_match("/#EXT-X-KEY:METHOD.+/",$h,$m);
    $out .=$m[0]."\r\n";
    for ($k=0;$k<count($id);$k++) {
     $pat="/(\#EXTINF\:\d+\.\d+\,)\n(\#EXT-X-BYTERANGE\:\d+\@\d+)?\n?([https?\:]?\/\/".$serv."\/redirect\/".$sig."\/".$ids."\/".$id[$k].")/";
     preg_match_all($pat,$h,$n);
     $dur=0;
     for($z=0;$z<count($n[1]);$z++) {
      preg_match("/\#EXTINF\:(\d+\.\d+)\,/",$n[1][$z],$d);
      $dur +=$d[1];
     }
     $out .="#EXTINF:".number_format($dur,6).","."\r\n";
     if ($flash == "flash") {
     $l1="https://".$serv."/redirect/".$sig."/".$ids."/".$id[$k]."/".$id[$k];
     $out .="hserver.php?file=".base64_encode("link=".urlencode($l1)."&origin=".urlencode($origin))."\r\n";
     } else
     $out .="https://".$serv."/redirect/".$sig."/".$ids."/".$id[$k]."/".$id[$k]."\r\n";
    }
    $out .="#EXT-X-ENDLIST";
    } else {
      $h=preg_replace_callback(
      "/(https?\:)?\/\/([a-zA-Z0-9\.\-\_]+)\/redirect\/([a-zA-Z0-9]+)\/([a-zA-Z0-9]+)\/([a-zA-Z0-9]+)\/([a-zA-Z0-9]+)/",
      function ($matches) {
      return "hserver.php?file=".base64_encode("link=".$matches[0]."&origin=".urlencode("https://vidcloud.co"));
      },
      $h
      );
      $out=$h;
    }
  if ($out) {
    file_put_contents("lava.m3u8",$out);
    if ($flash == "flash") {
      $link = $hash_path."/lava.m3u8";
    } else
      $link = $hash_path."/lava.m3u8"; //$link="http://127.0.0.1:8080/scripts/filme/lava.m3u8";
  } else {
    $link="";
  }
  }
  }
  if ($flash <> "flash")
    $link = $link."|Origin=".urlencode("https://vidcloud.co");   // send header to MX Player
}
echo $link;
?>
