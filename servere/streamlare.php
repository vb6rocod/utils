<?php
 /* resolve streamlare
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
  // https://streamlare.com/v/oLvgezwJoEPDbp8E
if (preg_match("/streamlare\.com|slmaxed\.com|slwatch\.co/",$filelink)) {
   function xor_string($string, $key) {
    for($i = 0; $i < strlen($string); $i++)
        $string[$i] = ($string[$i] ^ $key[$i % strlen($key)]);
    return $string;
   }
  if (preg_match('/(\/\/[\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.(srt|vtt)))/', $filelink, $s)) {
    $srt="https:".$s[1];
  }
  $link="";
  $ua="Mozilla/5.0 (Windows NT 10.0; rv:102.0) Gecko/20100101 Firefox/102.0";
  $cookie=$base_cookie."streamlare.dat";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $filelink);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_HEADER,1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
  curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
  curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  $html = curl_exec($ch);
  curl_close($ch);

  $html=htmlspecialchars_decode($html,ENT_QUOTES);

  if (preg_match("/Location\:\s*(.+)/i",$html,$m3))
   $host=parse_url(trim($m3[1]))['host'];
  else
   $host=parse_url($filelink)['host'];
  $html=htmlspecialchars_decode($html,ENT_QUOTES);

  $t1=explode('hashid":"',$html);
  $t2=explode('"',$t1[1]);
  $id=$t2[0];
  $t1=explode('csrf-token" content="',$html);
  $t2=explode('"',$t1[1]);
  $csrf=$t2[0];
  $l="https://".$host."/api/video/stream/get";
  $post='{"id":"'.$id.'"}';
  $head=array('User-Agent: Mozilla/5.0 (Windows NT 10.0; rv:102.0) Gecko/20100101 Firefox/102.0',
  'Accept: application/json, text/plain, */*',
   'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
   'Accept-Encoding: deflate',
   'Referer: '.$filelink,
   'X-Requested-With: XMLHttpRequest',
   'X-CSRF-TOKEN: '.$csrf,
   'Content-Type: application/json;charset=utf-8',
   'Content-Length: '.strlen($post),
   'Origin: https://'.$host,
   'Connection: keep-alive');

  $ch = curl_init($l);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
  curl_setopt($ch, CURLOPT_HTTPHEADER,$head);
  curl_setopt($ch, CURLOPT_POST,1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
  curl_setopt($ch, CURLOPT_ENCODING,"");
  curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
  curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
  curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  $h = curl_exec($ch);
  curl_close ($ch);
  $x=json_decode($h,1);
  // see app.js
  //if (isset($x['result']['Original']['src']))
  // $link = xor_string(base64_decode($x['result']['Original']['src']),"3");
  if (isset($x['result']['playlist']))
   $link= $x['result']['playlist'];
  elseif (isset($x['result']['Original']['src']))
   $link=$x['result']['Original']['src'];
  elseif (isset($x['result']['Original']['file']))
   $link=$x['result']['Original']['file'];
  elseif (isset($x['result']['file']))
   $link=$x['result']['file'];
  elseif (isset($x['result']['1080p']['file']))
   $link=$x['result']['1080p']['file'];
  elseif (isset($x['result']['720p']['file']))
   $link=$x['result']['720p']['file'];
  elseif (isset($x['result']['480p']['file']))
   $link=$x['result']['480p']['file'];
  elseif (isset($x['result']['360p']['file']))
   $link=$x['result']['360p']['file'];

  if ($link && preg_match("/video\?token/",$link)) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $link);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch,CURLOPT_REFERER,"https://".$host);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_ENCODING,"");
  curl_setopt($ch, CURLOPT_HEADER,1);
  curl_setopt($ch, CURLOPT_NOBODY,1);
  curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
  curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
  curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  $h = curl_exec($ch);
  curl_close($ch);

  if (preg_match("/location\:\s*(.+)/i",$h,$m))
    $link=trim($m[1]);


    if ($flash <> "flash") { // MX PLAYER
     $link .="|Referer=".urlencode("https://".$host."/");
     $link .="&User-Agent=".urlencode("Mozilla/5.0 (Windows NT 10.0; rv:102.0) Gecko/20100101 Firefox/102.0");
    }
 }
}
echo $link;
?>
