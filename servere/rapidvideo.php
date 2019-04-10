<?php
 /* resolve rapidvideo
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

$filelink="http://www.rapidvideo.com/e/G1PYC4XK5K";
if (strpos($filelink,"rapidvideo.com") !==false) {
      preg_match("/(e\/|embed\/|v=|e\/)(\w+)/",$filelink,$m);
      $id=$m[2];
      $filelink="https://www.rapidvideo.com/?v=".$id;
      $ua="Mozilla/5.0 (Windows NT 10.0; WOW64; rv:46.0) Gecko/20100101 Firefox/46.0";
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $filelink);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
      curl_setopt($ch, CURLOPT_USERAGENT, $ua);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
      curl_setopt($ch, CURLOPT_TIMEOUT, 15);
      $h = curl_exec($ch);
      curl_close($ch);

      $t1=explode("jwplayer.key",$h);
      $t2=explode("</script",$t1[1]);
      $t3=str_replace("\/","/",$t2[0]);

      if (preg_match_all('/([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.mp4))/', $h, $m)) {
        $n=count($m[1]);
        $link=$m[1][$n-1];
      } else
        $link="";
      if (preg_match_all('/([\.\d\w\=\-\.\/\\\:\?\&\#\%\_\,]*(\.(srt|vtt)))/', $h, $m)) {
         $srt=$m[0][0];
         if (strpos($srt,"http") === false) $srt="https://www.rapidvideo.com".$srt;
      }
}
echo $link;
?>
