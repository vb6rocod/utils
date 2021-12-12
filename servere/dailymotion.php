<?php
 /* resolve dailymotion
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

$filelink="https://www.dailymotion.com/video/x2jtx5v";
if (strpos($filelink,"dailymotion.com") !==false) {
  // https://www.dailymotion.com/embed/video/x2l65up?autoplay=1
  preg_match ("/video\/([a-zA-Z0-9]+)/",$filelink,$m);
  $id=$m[1];
  $filelink="https://www.dailymotion.com/embed/video/".$id;
  $ua="Mozilla/5.0 (Windows NT 10.0; rv:63.0) Gecko/20100101 Firefox/63.0";

  $h=file_get_contents($filelink);
  $t1=explode('var config = {',$h);
  $t2=explode('window.playerV5',$t1[1]);
  $t1=explode('window.__PLAYER_CONFIG__ = {',$h);
  $t2=explode(';</script',$t1[1]);
  $h1=trim("{".$t2[0]);

  $r1=json_decode($h1,1);

  $l1=$r1['context']['metadata_template_url'];
  $l1=str_replace(':videoId',$id,$l1);
  $l1=str_replace('embedder=:',urlencode($filelink),$l1);
  $h3=file_get_contents($l1);
  $r2=json_decode($h3,1);

  $l_main=$r2['qualities']['auto'][0]['url'];
  $link=$l_main;

  $h2=file_get_contents($l_main);

  if (preg_match_all("/^http(.*)$/m",$h2,$q)) {
   $link=$q[0][count($q[0])-1];
   $t1=explode("#",$link);
   $link=$t1[0];
  }
}
echo $link;
?>
