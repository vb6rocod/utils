<?php
 /* resolve videovard
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

$filelink="https://videovard.sx/e/m97g3y0q9xcs?c1_file=https://seriale-online.net/subtitrari/7482-5-2.vtt&c1_label=Romana";
if (strpos($filelink,"videovard.") !== false) {
  if (preg_match('/(\/\/[\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.(srt|vtt)))/', $filelink, $s))
    $srt="https:".$s[1];
  $id="";
  $link="";
   if (preg_match("/\/[ef]\/([0-9a-zA-Z\_\-]+)/",$filelink,$m)) {
   $id=$m[1];
   }
   if ($id) {
   include ("tear.php");
   $ua="Mozilla/5.0 (Windows NT 10.0; rv:90.0) Gecko/20100101 Firefox/90.0";
   $head=array('Accept: application/json, text/plain, */*',
   'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
   'Accept-Encoding: deflate',
   'Alt-Used: videovard.sx',
   'Connection: keep-alive',
   'Referer: https://videovard.sx');
   $l="https://videovard.sx/api/make/hash/".$id;
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $l);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_USERAGENT, $ua);
   curl_setopt($ch, CURLOPT_HTTPHEADER,$head);
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
   curl_setopt($ch, CURLOPT_ENCODING,"");
   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
   curl_setopt($ch, CURLOPT_TIMEOUT, 25);
   $h = curl_exec($ch);
   curl_close($ch);
   $x=json_decode($h,1);
   if (isset($x['hash'])) {
   $hash=$x['hash'];
   $l="https://videovard.sx/api/player/setup";
   $post="cmd=get_stream&file_code=".$id."&hash=".$hash;

   $head=array('Accept: application/json, text/plain, */*',
   'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
   'Accept-Encoding: deflate',
   'Content-Length: '.strlen($post),
   'Origin: https://videovard.sx',
   'Alt-Used: videovard.sx',
   'Connection: keep-alive',
   'Referer: https://videovard.sx');
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_POST,1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
  curl_setopt($ch, CURLOPT_HTTPHEADER,$head);
  curl_setopt($ch, CURLOPT_ENCODING,"");
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
  curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  $h = curl_exec($ch);
  curl_close($ch);

  $y=json_decode($h,1);
  if (isset($y['src'])) {
  $src=$y['src'];
  $seed=$y['seed'];
  // https://videovard.sx/_nuxt/daa1d94.js
  $out='var chars={"0":"5","1":"6","2":"7","5":"0","6":"1","7":"2"};';
  $out=str_replace("'",'"',$out);
  $t1=explode('var chars=',$out);
  $t2=explode(';',$t1[1]);
  $e="\$chars='".$t2[0]."';";
  eval ($e);
  $rep="/[012567]/";
  $x=json_decode($chars,1);
  $seed=preg_replace_callback(
    $rep,
    function ($m) {
      global $x;
      return $x[$m[0]];
    },
    $seed
  );
  $link = decrypt($src,$seed);
  $link=preg_replace_callback(
    $rep,
    function ($m) {
      global $x;
      return $x[$m[0]];
    },
    $link
  );
  }
  }
  }
  if ($link)
   $link=$link."|Referer=".urlencode("https://videovard.sx")."&Origin=".urlencode("https://videovard.sx");
}
echo $link;
?>
