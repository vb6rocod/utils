<?php
/* resolve fembed
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
$filelink = "https://www.fembed.com/v/4lo0jr-px9q";

if (preg_match("/embedsito\.com|vidsrc\.xyz|feurl\.|fcdn\.stream|fembed\.|femax\d+\.com|gcloud\.live|bazavox\.com|xstreamcdn\.com|smartshare\.tv|streamhoe\.online|animeawake\.net|mediashore\.org|sexhd\.co|streamm4u\.club/",$filelink)) {
  $host=parse_url($filelink)['host'];
  preg_match("/v\/([\w\-]*)/",$filelink,$m);
  $id=$m[1];
  $url="https://".$host."/api/source/".$id;
  $data = array('r' => '','d' => $host);
  $options = array(
        'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    )
  );

  $context  = stream_context_create($options);
  $h3 = @file_get_contents($url, false, $context);
  $r=json_decode($h3,1);

  if (isset($r['player']['poster_file'])) {
   $t1=explode("userdata/",$r['player']['poster_file']);
   $t2=explode("/",$t1[1]);
   $userdata=$t2[0];
  } else {
   $userdata="";
  }
  if (isset($r["captions"][0]["path"])) {
   if (strpos($r["captions"][0]["path"],"http") === false)
    $srt="https://".$host."/asset".$r["captions"][0]["path"];
   else
    $srt=$r["captions"][0]["path"];
  } elseif (isset($r["captions"][0]["hash"])) {
    $srt="https://".$host."/asset/userdata/".$userdata."/caption/".$r["captions"][0]["hash"]."/".$r["captions"][0]["id"].".".$r["captions"][0]["extension"];
  }
  $c = count($r["data"]);
  if (strpos($r["data"][$c-1]["file"],"http") === false)
   $link="https://".$host.$r["data"][$c-1]["file"];
  else
   $link=$r["data"][$c-1]["file"];
   if (preg_match("/\#caption\=(.+)/",$filelink,$m))
     $srt=$m[1];
}
echo $link;
?>
