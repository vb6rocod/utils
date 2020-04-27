<?php
 /* resolve database.gdriveplayer.us
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
 * $links --> video_links (array)
 * $subs -->  subtitles (array)
 */
error_reporting(0);
$imdb="tt1179933";
$filelink="https://database.gdriveplayer.us/player.php?imdb=".$imdb;
/* for opensubtitles */
/*
tip -> movie/series
title (optional daca exista imdb)
imdbid (imdb id dar fara "tt")
sez -> sezon numar
ep -> episod numar
opensubtitles_ua -> opensubtitles user agent (TemporaryUserAgent) see
https://trac.opensubtitles.org/projects/opensubtitles/wiki/DevReadFirst
*/

$tip="movie";
$sez="";
$ep="";
/* prepare */
$imdbid=str_replace("tt","",$imdb);
$ua_opensuptitles="TemporaryUserAgent"; // now you must set user and pass in LogIn
$max_download=10; // how many subtitles to display
$lang_search="rum,eng"; // language to search subtitles
function get_value($q, $string) {
   $t1=explode($q,$string);
   $t2=explode("<string>",$t1[1]);
   $t3=explode("</string>",$t2[1]);
   return $t3[0];
}
function generateResponse($request) {
  $ua = $_SERVER['HTTP_USER_AGENT'];
  $head = array(
  'Content-Type: text/xml',
  );
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "http://api.opensubtitles.org/xml-rpc");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
  curl_setopt ($ch, CURLOPT_POST, 1);
  curl_setopt ($ch, CURLOPT_POSTFIELDS, $request);
  $response = curl_exec($ch);
  curl_close($ch);
  return $response;
}
  /* use gdriveplayer.php to get links and subtitles */
  /* use other file to generate playable links! ex myfile.php?file=xxxxx */
  /* $links --> links */
  $sources="sources: ["."\n";
  for ($k=0;$k<count($links);$k++) {
    $sources .="{"."\n";
    $sources .='"file": "'.$links[$k]['file'].'",'."\n";
    $sources .='"label": "'.$links[$k]['label'].'",'."\n";
    if ($k==0) $sources .='"default": "true",'."\n";
    $sources .='"type": "mp4"'."\n";
    $sources .='},'."\n";
  }
  $sources = substr($sources, 0, -2)."],"."\n";
/* opensubtitles */
$arrsub = array();
/* Get token */
$token="";
$request="<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
<methodCall>
<methodName>LogIn</methodName>
<params>
 <param>
  <value>
   <string>user</string>
  </value>
 </param>
 <param>
  <value>
   <string>pass</string>
  </value>
 </param>
 <param>
  <value>
   <string>en</string>
  </value>
 </param>
 <param>
  <value>
   <string>".$ua_opensuptitles."</string>
  </value>
 </param>
</params>
</methodCall>";
$response = generateResponse($request);
if (preg_match("/200 OK/",$response))
 $token=get_value("token",$response);
if ($token) {
/* Get subtitles name and ID */
if ($tip=="movie") {
$request="<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
<methodCall>
<methodName>SearchSubtitles</methodName>
<params>
 <param>
  <value>
   <string>".$token."</string>
  </value>
 </param>
 <param>
  <value>
   <array>
    <data>
     <value>
      <struct>
       <member>
        <name>query</name>
        <value>
         <string>".str_replace("&","&amp;",$title)."</string>
        </value>
       </member>
       <member>
        <name>imdbid</name>
        <value>
         <string>".$imdbid."</string>
        </value>
       </member>
       <member>
        <name>sublanguageid</name>
        <value>
         <string>".$lang_search."</string>
        </value>
       </member>
      </struct>
     </value>
    </data>
   </array>
  </value>
 </param>
 <param>
  <value>
   <struct>
    <member>
     <name>limit</name>
     <value>
      <int>100</int>
     </value>
    </member>
   </struct>
  </value>
 </param>
</params>
</methodCall>";
$response = generateResponse($request);
if (preg_match("/200 OK/",$response)) {
$videos=explode("MatchedBy",$response);
unset($videos[0]);
$videos = array_values($videos);
foreach($videos as $video) {
 $MovieKind = get_value("MovieKind",$video);
 $SubFormat = get_value("SubFormat",$video);
 if ($MovieKind == "movie" && $SubFormat == "srt") {
   $SubFileName =get_value("SubFileName",$video);
   $id1 = get_value("IDSubtitleFile",$video);
   $SubLanguageID = get_value("SubLanguageID",$video);
   $id2=get_value("IDSubtitleFile",$video);
   array_push($arrsub ,array($SubLanguageID,$SubFileName, $id2));
 }
}
}
} else {
$request="<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
<methodCall>
<methodName>SearchSubtitles</methodName>
<params>
 <param>
  <value>
   <string>".$token."</string>
  </value>
 </param>
 <param>
  <value>
   <array>
    <data>
     <value>
      <struct>
       <member>
        <name>query</name>
        <value>
         <string>".str_replace("&","&amp;",$title)."</string>
        </value>
       </member>
       <member>
        <name>imdbid</name>
        <value>
         <string>".$imdbid."</string>
        </value>
       </member>
       <member>
        <name>season</name>
        <value>
         <int>".$sez."</int>
        </value>
       </member>
       <member>
        <name>episode</name>
        <value>
         <int>".$ep."</int>
        </value>
       </member>
       <member>
        <name>sublanguageid</name>
        <value>
         <string>".$lang_search."</string>
        </value>
       </member>
      </struct>
     </value>
    </data>
   </array>
  </value>
 </param>
 <param>
  <value>
   <struct>
    <member>
     <name>limit</name>
     <value>
      <int>100</int>
     </value>
    </member>
   </struct>
  </value>
 </param>
</params>
</methodCall>";
$response = generateResponse($request);
if (preg_match("/200 OK/",$response)) {
$videos=explode("MatchedBy",$response);
unset($videos[0]);
$videos = array_values($videos);
foreach($videos as $video) {
 $MovieKind = get_value("MovieKind",$video);
 $SubFormat = get_value("SubFormat",$video);
 if ($MovieKind == "episode" && $SubFormat == "srt") {
   $SubFileName =get_value("SubFileName",$video);
   $id1 = get_value("IDSubtitleFile",$video);
   $SubLanguageID = get_value("SubLanguageID",$video);
   $id2=get_value("IDSubtitleFile",$video);
   array_push($arrsub ,array($SubLanguageID,$SubFileName, $id2));
 }
}
}
}
arsort($arrsub); // optional if sublanguageid > 1
$z=0;
if (count($arrsub)>0) $tracks = "tracks: ["."\n";
foreach ($arrsub as $key => $val) {
  $lang=$arrsub[$key][0];
  $sub_name=$arrsub[$key][1];
  $sub_id=$arrsub[$key][2];
  $sub_filename="opensubtitles_down.php?id=".$arrsub[$key][2]."&token=".$token;
  $tracks .="{"."\n";
  $tracks .='"file": "'.$sub_filename.'",'."\n";
  $tracks .='"kind": "captions",'."\n";
  if ($z==0) $tracks .='"default": "true",'."\n";
  $tracks .='"label": "'.$arrsub[$key][0]." - ".$arrsub[$key][1].'"'."\n";
  $tracks .='},'."\n";
  $z++;
  if ($z==$max_download) break;
 }
 if (count($arrsub)>0) $tracks = substr($tracks, 0, -2)."],"."\n";
}
/* final */
echo '
<!doctype html>
<HTML>
<HEAD>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>'.$title.'</title>
<style type="text/css">
body {
margin: 0px auto;
overflow:hidden;
}
body {background-color:#000000;}
</style>
<style type="text/css">*{margin:0;padding:0}#player{position:absolute;width:100%!important;height:100%!important}.jw-button-color:hover,.jw-toggle,.jw-toggle:hover,.jw-open,.w-progress{color:#008fee!important;}.jw-active-option{background-color:#008fee!important;}.jw-progress{background:#008fee!important;}.jw-skin-seven .jw-toggle.jw-off{color:fff!important}</style>
<script type="text/javasript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script type="text/javascript" src="YOUR_JWPLAYER.js"></script>
<script type="text/javascript">jwplayer.key = "YOUR_KEY";</script>
</head>
<body>
<div id="player"></div>

<script type="text/javascript">
';
echo "
var jwDefaults = {
    'aspectratio': '16:9',
    'autostart': true,
    'controls': true,
    'displaydescription': false,
    'displaytitle': true,
    'flashplayer': '//ssl.p.jwpcdn.com/player/v/7.12.11/jwplayer.flash.swf',
    'height': 260,
    'mute': false,
    'volume': 100,
    'preload': 'auto',
    'androidhls': true,
    'hlshtml': true,
    'playbackRateControls': true,
    'ph': 1,
    'plugins': {
        'ping': {}
    },
    captions: {
        color: '#ffffff',
        fontOpacity: 100,
        edgeStyle: 'raised',
        backgroundOpacity: 0,
        fontFamily: 'Arial',
        fontSize: 20
    },
    'primary': 'html5',
    'repeat': false,
    'stagevideo': false,
    'stretching': 'uniform',
    'visualplaylist': true,
    'width': '100%'
};
jwplayer.defaults = jwDefaults;
var player = jwplayer('player');
";
echo '
player.setup({'
.$sources.$tracks.'
    title: "'.$title.'",
    image: "'.$image.'",
    logo: {
        file: ""
    },
});
jwplayer().addButton("download.svg", "Download Video", function() {
    window.location.href = player.getPlaylistItem()["file"];
}, "download");


 </script>
</body>
</html>
';
?>
