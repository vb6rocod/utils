<?php
 /* resolve mixdrop
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

$filelink="https://mixdrop.co/e/eaeuizxtz0";
$filelink="https://mixdrop.is/e/4njejwl0cql4w11";
$mixdrop="/mixdro{0,}p\.|mdy48tn97\.|mdbekjwqa\.|mdfx\w+|mdzsmut|mixdropjmk/";
if (preg_match($mixdrop,$filelink)) {
  $filelink=str_replace("/f/","/e/",$filelink);
  require_once("JavaScriptUnpacker.php");
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $filelink);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; rv:65.0) Gecko/20100101 Firefox/65.0");
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch,CURLOPT_REFERER,$filelink);
  curl_setopt($ch, CURLOPT_ENCODING, "");
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h3 = curl_exec($ch);
  curl_close($ch);

  $out="";
  if (preg_match("/eval\(function\(p,a,c,k,e,[r|d]?/",$h3)) {
  $jsu = new JavaScriptUnpacker();
  preg_match_all("/eval\(function.*?\<\/script/s",$h3,$m);
  for ($k=0;$k<count($m[0]);$k++) {
  $out .= $jsu->Unpack($m[0][$k]);
  }
  }
  if (preg_match("/(\/\/[\w|\.\:\?\&\/\=\_\-]+\.mp4\?[\w|\.\:\?\&\/\=\_\-]+)[\'\"]/",$out,$m)) {
      $link="https:".$m[1];
    if (strpos($link,"http") === false) $link="https:".$link;
      if (preg_match("/\.(remote)?sub\s*\=\s*\"(.*?)\"/",$out,$s)) {
       $srt= $s[2];
       $srt= urldecode($s[2]);
       $srt=str_replace(" ","%20",$srt);
       if (strpos($srt,"http") === false && $srt) $srt="https:".$srt;
      }
  } else {
    $link="";
  }
}
echo $link;
?>
