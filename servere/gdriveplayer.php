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
$filelink="https://databasegdriveplayer.co/player.php?imdb=tt10692788"; // new 09.2021
$filelink="https://databasegdriveplayer.co/player.php?imdb=tt15831226";
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
  curl_setopt($ch, CURLOPT_HEADER,1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
  curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  $h = curl_exec($ch);
  curl_close($ch);
  // get only main server......
  require_once("JavaScriptUnpacker.php");
  $jsu = new JavaScriptUnpacker();
  $h = $jsu->Unpack($h);
  //echo $h;
  $t1=explode("null,'",$h);
  $t2=explode("'",$t1[1]);
  $js=$t2[0];
  $keywords = preg_split("/[a-zA-Z]{1,}/",$js);
  $out="";
  for ($k=0;$k<count($keywords);$k++) {
   $out .=chr($keywords[$k]);
  }
  //echo $out;
  $t1=explode('pass = "',$out);
  $t2=explode('"',$t1[1]);
  $pass=$t2[0];
  $t1=explode("'",$h);
  $t1=explode("data='",$h);
  $t2=explode("';",$t1[1]);
  $x=cryptoJsAesDecrypt($pass,$t2[0]);
  //echo $x;
  $h1 = $jsu->Unpack($x);
  //echo $h1;
  $links_gd=array();
  $subs=array();
  /* GET LINKS */
  // "file":"//redir.gdrivecdn.work/redirector.php?id=cGgxaU5EbW9TL0pKT1pOd0JuZTBDZz09&t="+Date.now()+"&ref="+encodeURI(document.referrer)+"&res=720","label":"720p","type":"mp4"
  preg_match_all("/file\":\"([\w\/\=\.\?\:\%\&\+\_\-\"\+\(\)]+)\"\,\"label\":\"(\w+)\"\,\"type\":\"(\w+)\"/msi",$h1,$m);
  print_r ($m);
  if (isset($m[1])) {
   $links_gd=array($m[2],$m[1]);
  }
  /* GET SUBTITLES */
  preg_match_all("/file\":\"([\w\/\=\.\?\:\%\&\+\_\-]+)\"\,\"kind\":\"(\w+)\"\,\"label\":\"(\w+)\"/msi",$h1,$s);
  if (isset($s[1])) {
   $subs=array($s[3],$s[1]);
  }

  print_r ($links_gd);
  print_r ($subs);

  /* resolve first.... */
  $l="https:".$links_gd[1][0];
  $l=str_replace('"+Date.now()+"&ref="+encodeURI(document.referrer)+"&res=',time()."&ref=".urlencode("https://databasegdriveplayer.co")."&res=",$l);
  //echo $l;

  $head=array('Accept: video/webm,video/ogg,video/*;q=0.9,application/ogg;q=0.7,audio/*;q=0.6,*/*;q=0.5',
   'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
   'Range: bytes=0-',
   'Connection: keep-alive');
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,0);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_HTTPHEADER,$head);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
  curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  curl_setopt($ch, CURLOPT_HEADER,1);
  curl_setopt($ch, CURLOPT_NOBODY,1);
  $h = curl_exec($ch);
  curl_close($ch);
  //echo $h;
  $movie="";
  if (preg_match_all("/location:\s*(.+)/i",$h,$m))
    $movie=trim($m[1][count($m[1])-1]); // https://storage.googleapis.com/fb34547a56d5e3fcd02.appspot.com/1e7973531048cc29fc6dbc13dda90f1f_1629966086.mp4
  //echo $movie;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https:".$movie);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,0);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_HTTPHEADER,$head);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
  curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  curl_setopt($ch, CURLOPT_HEADER,1);
  curl_setopt($ch, CURLOPT_NOBODY,1);
  $h = curl_exec($ch);
  curl_close($ch);
  $movie="";
  if (preg_match_all("/location:\s*(.+)/i",$h,$m))
    $movie=trim($m[1][count($m[1])-1]); // https://storage.googleapis.com/fb34547a56d5e3fcd02.appspot.com/1e7973531048cc29fc6dbc13dda90f1f_1629966086.mp4
  echo $movie;
?>
