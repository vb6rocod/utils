<?php
/* rebuild m3u8 file for googleusercontent
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
  $filelink="https://cloud.vidhubstr.org/public/dist/index.html?id=ccb6d3c0a006b6ab6aab8e49a0f0a127&vlsub=artofracing2019.srt&autoplay=yes";
  $filelink="https://cloud.vidhubstr.org/public/dist/index.html?id=a5076fa96a8b7620d2186e37cc048c70&autoplay=yes";
if (strpos($filelink,"cloud.vidhubstr.org") !== false) {
  set_time_limit(60);
  $ua = $_SERVER['HTTP_USER_AGENT'];
  $out="";
  $x=parse_url($filelink)['query'];
  parse_str($x, $output);
  $id=$output['id'];
  $l="https://cloud.vidhubstr.org/getHost/".$id;
  $post="";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt ($ch, CURLOPT_POST, 1);
  curl_setopt ($ch, CURLOPT_POSTFIELDS, $post);
  curl_setopt ($ch, CURLOPT_ENCODING,"");
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h = curl_exec($ch);
  curl_close($ch);
  $l=base64_decode($h);
  if (preg_match("/http/",$l)) {
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $l);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_USERAGENT, $ua);
   curl_setopt ($ch, CURLOPT_ENCODING,"");
   curl_setopt($ch, CURLOPT_HEADER, 1);
   curl_setopt($ch, CURLOPT_NOBODY, 1);
   $h = curl_exec($ch);
   curl_close($ch);
   if (preg_match("/Location:\s?(\S+)/",$h,$m))
    $l=$m[1];
   $new_host=parse_url($l)['host'];
   $head=array('Accept: */*',
   'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
   'Origin: https://cloud.vidhubstr.org',
   'Referer: https://cloud.vidhubstr.org',
   'Connection: keep-alive');
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $l);
   curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
   curl_setopt($ch, CURLOPT_USERAGENT, $ua);
   curl_setopt ($ch, CURLOPT_ENCODING,"");
   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
   curl_setopt($ch, CURLOPT_TIMEOUT, 15);
   $h = curl_exec($ch);
   if (preg_match("/drive.*?\.m3u8/",$h,$m)) {
    $l="https://".$new_host."/".$m[0];
    curl_setopt($ch, CURLOPT_URL, $l);
    $h = curl_exec($ch);

    $n=explode("\n",$h);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_HEADER,1);
    curl_setopt($ch, CURLOPT_NOBODY,1);
    curl_setopt($ch, CURLOPT_ENCODING, "");
    for ($k=0;$k<count($n);$k++) {
     if (strpos($n[$k],"#") !== false)
      $out .=$n[$k]."\n";
     else {
      $l="https://".$new_host.trim($n[$k]);
      curl_setopt($ch, CURLOPT_URL,$l);
      $x = curl_exec($ch);
      preg_match("/Location:\s+(\S+)/",$x,$y);
      $z=$y[1];
      $out .=$z."\n";
     }
    }
   }
   if (isset($output['sub']))
    $srt=$output['sub'];
   if (isset($output['vlsub'])) {
    $l="https://sub.vidhubstr.org/getSubObj?name=".$output['vlsub'];
    curl_setopt($ch, CURLOPT_NOBODY,0);
    curl_setopt($ch, CURLOPT_HEADER,0);
    curl_setopt($ch, CURLOPT_URL,$l);
    $h = curl_exec($ch);
    $x=json_decode($h,1);
    $srt=$x[0]['file'];
   }
  }
  curl_close($ch);
  file_put_contents("lava.m3u8",$out);
  $p = dirname($_SERVER['HTTP_REFERER']);
  $link = $p."/lava.m3u8";
}
?>
