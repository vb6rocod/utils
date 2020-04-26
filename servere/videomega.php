<?php
/* resolve videomega
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
$filelink = "https://www.videomega.co/e/78f604df6f6786a8";
$filelink = "https://www.videomega.co/e/02e8928ca02a60b6?c1_file=https://serialeonline.to/subtitrari/52-1-7.vtt&c1_label=Romana";
if (strpos($filelink,"videomega.") !== false) {
  $cookie="videomega.dat";  // ???
  $filelink=str_replace("/f/","/e/",$filelink);
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $filelink);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; rv:65.0) Gecko/20100101 Firefox/65.0");
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
  curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
  curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  $h=curl_exec($ch);
  curl_close($ch);
  $srt="";
  if (preg_match("/([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.(srt|vtt)))/",$filelink,$m)) {
   $srt=$m[1];
  }
  $t1=explode('var token="',$h);
  $t2=explode('"',$t1[1]);
  $token=$t2[0];
  $t1=explode('var crsf="',$h);
  $t2=explode('"',$t1[1]);
  $crsf=$t2[0];
  if ($token && $crsf) {
  if (!$srt && preg_match_all('/([\.\d\w\-\.\=\/\\\:\?\&\#\%\_\,\)\(\s\[\+\]]+(\.(srt|vtt)))\" srclang=\"(\w+)\" label=\"(\w+)\"/', $h, $s)) {
  $srts=array();
  if (isset($s[5])) {
    for ($k=0;$k<count($s[5]);$k++) {
      if (strpos($s[1][$k],"empty.srt") === false) $srts[strtolower($s[5][$k])] = $s[1][$k];
    }
  }
  if (count($srts)>1) {
  foreach ($srts as $key => $value) {
  $t1=explode("srt=",$value);
  if (strpos($t1[1],"videomega") === false) {
   $head=array('Accept: */*',
   'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
   'Accept-Encoding: deflate',
   'Origin: https://'.parse_url($filelink)["host"].'',
   'Connection: keep-alive',
   'Referer: '.$filelink.'');
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $t1[1]);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_HTTPHEADER,$head);
   curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; rv:71.0) Gecko/20100101 Firefox/7");
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
   curl_setopt($ch, CURLOPT_TIMEOUT, 15);
   $h=curl_exec($ch);
   curl_close($ch);
   if(!preg_match('/(\d\d):(\d\d):(\d\d)(\.|,)(\d\d\d) --> (\d\d):(\d\d):(\d\d)(\.|,)(\d\d\d)/', $h))
    unset ($srts[$key]);
  }
  }
  }
  if (count($srts)>1) {
  if (isset($srts["romanian"]))
    $srt=$srts["romanian"];
  elseif (isset($srts["romana"]))
    $srt=$srts["romana"];
  elseif (isset($srts["english"]))
    $srt=$srts["english"];
  } elseif (count($srts)>0) {
    $srt=$s[1][0];
  }
  }

  $post="gone=".$token."&oujda=".$crsf;
  $head=array('Accept: */*',
   'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
   'Accept-Encoding: deflate',
   'Content-Type: application/x-www-form-urlencoded',
   'Content-Length: '.strlen($post).'',
   'Origin: https://www.videomega.co',
   'Connection: keep-alive',
   'Referer: '.$filelink);
  $l="https://www.videomega.co/vid/";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; rv:65.0) Gecko/20100101 Firefox/65.0");
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_HTTPHEADER,$head);
  curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POST,1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
  curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  $h=curl_exec($ch);
  curl_close($ch);
  if (preg_match("/http/",$h)) {
    $link=trim($h);
  } else {
    $link="";
  }
  }
}
echo $link;
?>
