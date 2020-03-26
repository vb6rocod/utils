<?php
/* resolve mystream
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
$filelink = "https://embed.mystream.to/pufpln9x8ejh";
$filelink = "https://embed.mystream.to/edqddehi2hrh";
$filelink="https://embed.mystream.to/j7mt4olun0cr";
if (strpos($filelink,"mystream.") !== false || strpos($filelink,"mstream.") !==false) {
 $pat='@(?://|\.)(my?stream\.(?:la|to|cloud|xyz))/(?:external|watch/)?([0-9a-zA-Z_]+)@';
 preg_match($pat,$filelink,$i);
 $filelink="https://embed.mystream.to/".$i[2];
 $h=file_get_contents($filelink);
 if (preg_match("@(\\$\=\~\[\].*?)\<script@si",$h,$u)) {
  if (preg_match('/([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.(srt|vtt)))/', $h, $s)) // not sure
  $srt=$s[1];
  $code=$u[0];
  $t1=explode(";",$code);
  $js=$code;
  $t3=substr($t1[1],3);
  $c=explode(",",$t3);
  $x="0,f,1,a,2,b,d,3,e,4,5,c,6,7,8,9";
  $y=explode(",",$x);
  $map=array();
  for ($k=0;$k<count($c);$k++) {
    $a1=explode(":",$c[$k]);
    $map[$y[$k]]="$.".$a1[0];
  }
  $map['o']="$._$";
  $map['u']="$._";
  $map['t']="$.__";
  function cmp($a, $b) {
    if (strlen($a) == strlen($b)) {
        return 0;
    }
    return (strlen($a) > strlen($b)) ? -1 : 1;
  }
  uasort($map, 'cmp');  // sort map strlen
  foreach($map as $key=>$value) {
    $js=str_replace($value,$key,$js);
  }
  $js=str_replace("+","",$js);
  $js=str_replace('"','',$js);
  $js=str_replace('(![])[2]','l',$js);
  $js = preg_replace_callback('@\\\\(\d{2,3})@', function($c){return chr(base_convert($c[1], 8, 10)); }, $js);
  $js=str_replace("\\","",$js);
  if (preg_match('/([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.(srt|vtt)))/', $js, $s))   // not sure
  $srt=$s[1];
  if (preg_match("/http.+\.(mp4|m3u8)/",$js,$m))
    $link=$m[0];
  else
    $link="";
 } else
    $link="";
}
echo $link;
?>
