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
  $l="https://www.dailymotion.com/embed/video/".$id;
  $h=file_get_contents($l);
  $t1=explode('"ts":',$h);
  $t2=explode(",",$t1[1]);
  $ts=$t2[0];
  $t1=explode('"v1st":"',$h);
  $t2=explode('"',$t1[1]);
  $dm=$t2[0];
  $l="https://www.dailymotion.com/player/metadata/video/".$id."?embedder=".urlencode($filelink);
  $l .="&dmV1st=".$dm."&dmTs=".$ts."&is_native_app=0&app=com.dailymotion.neon&client_type=website&section_type=player&component_style=_";
  $h=file_get_contents($l);
  $r2=json_decode($h,1);
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
