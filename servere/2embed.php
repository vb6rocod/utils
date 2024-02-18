<?php
 /* resolve 2embed
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
 * return an array
 */

$filelink="https://rabbitstream.net/embed-4/HUfa8sK5YvEh?z=";
  $host="https://".parse_url($filelink)['host'];
  $ua="Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:122.0) Gecko/20100101 Firefox/122.0";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $filelink);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch,CURLOPT_REFERER,"https://www3.zoechip.com/");
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
  curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  $h = curl_exec($ch);
  curl_close($ch);

  $h=str_replace("\\","",$h);
  $t1=explode('data-id="',$h);
  $t2=explode('"',$t1[1]);
  $id=$t2[0];
  

  $l= $host."/ajax/embed-4/getSources?id=".$id;

  $head=array('Accept: */*',
  'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
  'Accept-Encoding: deflate',
  'X-Requested-With: XMLHttpRequest',
  'Connection: keep-alive',
  'Referer: '.$host.'/');
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_HTTPHEADER,$head);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
  curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  $h = curl_exec($ch);
  curl_close($ch);

  $x=json_decode($h,1);
  $file=$x["sources"];

  if (substr($x["sources"],0,2) == "U2") {
   require_once("CryptoJSAES_decrypt.php");
   $l="https://keys4.fun/";
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $l);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_USERAGENT, $ua);
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
   curl_setopt($ch, CURLOPT_TIMEOUT, 15);
   $h1 = curl_exec($ch);
   curl_close($ch);
   $y=json_decode($h1,1)['rabbitstream']['keys'];

   $out="";
   for ($i=0;$i<count($y);$i++) {
    $out .=chr($y[$i]);
   }
   $decryptedKey=base64_encode($out);
   $x=CryptoJSAES_decrypt($file,$decryptedKey);
   if ($x) {
     $xx=json_decode($x,1);
     $link=$xx[0]['file'];
   }
 } else {
  if (isset($x["sources_1"][0]["file"]))
   $link= $x["sources_1"][0]["file"];
  elseif (isset($x["sources"][0]["file"]))
   $link= $x["sources"][0]["file"];
 }
 echo $link;
?>
