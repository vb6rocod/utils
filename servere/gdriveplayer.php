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
$filelink="https://database.gdriveplayer.us/player.php?imdb=tt1179933";
function cryptoJsAesDecrypt($passphrase, $jsonString){
    $jsondata = json_decode($jsonString, true);
    try {
        $salt = hex2bin($jsondata["s"]);
        $iv  = hex2bin($jsondata["iv"]);
    } catch(Exception $e) { return null; }
    $ct = base64_decode($jsondata["ct"]);
    $concatedPassphrase = $passphrase.$salt;
    $md5 = array();
    $md5[0] = md5($concatedPassphrase, true);
    $result = $md5[0];
    for ($i = 1; $i < 3; $i++) {
        $md5[$i] = md5($md5[$i - 1].$concatedPassphrase, true);
        $result .= $md5[$i];
    }
    $key = substr($result, 0, 32);
    $data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
    return json_decode($data, true);
}
  $ua = $_SERVER['HTTP_USER_AGENT'];
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $filelink);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
  curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  $h = curl_exec($ch);
  curl_close($ch);
  require_once("JavaScriptUnpacker.php");
  $jsu = new JavaScriptUnpacker();
  $h = $jsu->Unpack($h);
  $t1=explode("null,'",$h);
  $t2=explode("'",$t1[1]);
  $js=$t2[0];
  $keywords = preg_split("/[a-zA-Z]{1,}/",$js);
  $out="";
  for ($k=0;$k<count($keywords);$k++) {
   $out .=chr($keywords[$k]);
  }
  $t1=explode('pass = "',$out);
  $t2=explode('"',$t1[1]);
  $pass=$t2[0];
  $t1=explode("'",$h);
  $x=cryptoJsAesDecrypt($pass,$t1[1]);
  $h1 = $jsu->Unpack($x);
  //echo $h1;
  $links=array();
  $subs=array();
  /* GET LINKS */
  preg_match_all("/file\":\"([\w\/\=\.\?\:\%\&\+\_\-]+)\"\,\"label\":\"(\w+)\"\,\"type\":\"(\w+)\"/msi",$h1,$m);
  if (isset($m[1])) {
   $links=$m[1];
   //echo 'LINKS:<BR>';
   for ($k=0;$k<count($m[1]);$k++) {
    //echo '<a href="'.$m[1][$k].'" target="_blank">'.$m[2][$k].' ('.$m[3][$k].')</a> == ';
   }
  }
  /* GET SUBTITLES */
  preg_match_all("/file\":\"([\w\/\=\.\?\:\%\&\+\_\-]+)\"\,\"kind\":\"(\w+)\"\,\"label\":\"(\w+)\"/msi",$h1,$s);
  if (isset($s[1])) {
   $subs=$s[1];
   //echo '<BR>Captions:<BR>';
   for ($k=0;$k<count($s[1]);$k++) {
    //echo '<a href="'.$s[1][$k].'" target="_blank">'.$s[3][$k].'</a> == ';
   }
  }
/* TEST */
$t1=explode('var host2',$h1);
$h=$t1[0];
echo '
<!doctype html>
<HTML>
<HEAD>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TEST</title>
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
echo $h."\n";
echo ' </script>
</body>
</html>
';
?>
