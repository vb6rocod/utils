<?php
 /* resolve verystream.
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

$filelink="https://verystream.com/e/9TMLcpuCbF1";
if (strpos($filelink,"verystream.") !== false) {
  $t1=explode("/",$filelink);
  $filelink="https://verystream.com/e/".$t1[4];
  $ua="Mozilla/5.0 (Windows NT 10.0; WOW64; rv:46.0) Gecko/20100101 Firefox/46.0";
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $filelink);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
      curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:46.0) Gecko/20100101 Firefox/46.0');
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_REFERER, "https://verystream.com");
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
      curl_setopt($ch, CURLOPT_TIMEOUT, 15);
      $h1 = curl_exec($ch);
      curl_close($ch);
      if (preg_match('/([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.(srt|vtt)))/', $h1, $m)) {
         $srt=$m[1];
         if (strpos($srt,"http") === false)
         $srt="https://verystream.com".$srt;
      }
      $t1=explode('id="videolink">',$h1);
      if (isset($t1[1])) {
      $t2=explode('<',$t1[1]);
      $id=$t2[0];
      $l="https://verystream.com/gettoken/".$id."?mime=true";
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $l);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
      curl_setopt($ch, CURLOPT_USERAGENT, $ua);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_REFERER, "https://verystream.com");
      curl_setopt($ch, CURLOPT_HEADER,1);
      curl_setopt($ch, CURLOPT_NOBODY,1);
      $h2 = curl_exec($ch);
      curl_close($ch);
      $t1=explode("Location:",$h2);
      $t2=explode("?",$t1[1]);
      $link=urldecode(trim($t2[0]));
      $movie_file=substr(strrchr(urldecode($link), "/"), 1);
      $movie_file1=substr($movie_file, 0, -4);
      $movie_file2 = preg_replace('/[^A-Za-z0-9_]/','_',$movie_file1);
      $link=str_replace($movie_file1,$movie_file2,$link);
      $link=str_replace("https","http",$link).".mp4";
  } else {
    $link="";
  }
}
echo $link;
?>
