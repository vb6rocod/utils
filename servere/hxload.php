<?php
 /* resolve hxload "rc4"
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
 * $filelink = "https://hxload.co/embed/dwv1caux062f/";
 * $link --> video_link
 */
require_once( "rc4.php" );
function decode_code($code){
    return preg_replace_callback(
        "@\\\(x)?([0-9a-f]{2,3})@",
        function($m){
            return chr($m[1]?hexdec($m[2]):octdec($m[2]));
        },
        $code
    );
}
function abc($a52, $a10)
{
    global $mod;
    $a54 = array();
    $a55 = 0x0;
    $a56 = '';
    $a57 = '';
    $a58 = '';
    $a52 = base64_decode($a52);
    $a52 = mb_convert_encoding($a52, 'ISO-8859-1', 'UTF-8');
    /*
    for ($a72 = 0x0; $a72 < 0x100; $a72++) {
        $a54[$a72] = $a72;
    }
    */
    /*
    for ($a72 = 0x0; $a72 < 0x100; $a72++) {     //new
        $a54[$a72] = (0x3 + $a72) % 0x100;
    }
    */
    /*
    for ($a72 = 0x0; $a72 < 0x100; $a72++) {     //new
        $a54[$a72] = (0x3 + $a72 + pow(0x7c,0x0)) % 0x100;
    }
    */

    for ($a72 = 0x0; $a72 < 0x100; $a72++) {
      eval ($mod);
    }

    for ($a72 = 0x0; $a72 < 0x100; $a72++) {
        $a55       = ($a55 + $a54[$a72] + ord($a10[($a72 % strlen($a10))])) % 0x100;
        $a56       = $a54[$a72];
        $a54[$a72] = $a54[$a55];
        $a54[$a55] = $a56;
    }
    $a72 = 0x0;
    $a55 = 0x0;
    for ($a100 = 0x0; $a100 < strlen($a52); $a100++) {
        $a72       = ($a72 + 0x1) % 0x100;
        $a55       = ($a55 + $a54[$a72]) % 0x100;
        $a56       = $a54[$a72];
        $a54[$a72] = $a54[$a55];
        $a54[$a55] = $a56;
        $xx        = $a54[($a54[$a72] + $a54[$a55]) % 0x100];
        $a57 .= chr(ord($a52[$a100]) ^ $xx);
    }
    return $a57;
}
  $filelink="https://hxload.co/embed/dwv1caux062f/";
  $ua       = $_SERVER["HTTP_USER_AGENT"];
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $filelink);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_REFERER, $filelink);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
  curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  $h = curl_exec($ch);
  curl_close($ch);
  $h=decode_code($h);
  if (preg_match("/var (\w+)\s*\=\s*\'([\w\+\\_\/\=]{100,})\'\;/ms",$h,$m)) { // var hxstring = '8j.....
  $rc4=base64_decode($m[2]);
  // fix abc function
  $t1=explode('decodeURIComponent',$h);
  $t2=explode('{',$t1[1]);
  $t3=explode(';',$t2[1]);
  $mod=$t3[0];
  $mod=str_replace("Math.","",$mod);
  $mod=preg_replace_callback(
   "/Math\[(.*?)\]/",
   function ($matches) {
    return preg_replace("/(\s|\"|\'|\+)/","",$matches[1]);;
   },
   $mod
  );

  preg_match_all("/(_0x)?[a-zA-Z0-9]+/",$mod,$m);
  $mod=str_replace($m[0][0],"\$a54",$mod);
  $mod=str_replace($m[0][1],"\$a72",$mod);
  $mod=$mod.";";
  // end fix

  $h=str_replace(" ","",$h);
  $h=str_replace("'",'"',$h); // avoid abc('0x0','fg'x')
  $pat1="(var\s*((_0x)?[a-z0-9_]+)(\=))";
  $pat2="(function\s*((_0x)?[a-z0-9_]+)(\(\)\{return))";
  $pat3="\[(\"?[a-zA-Z0-9_\=\+\/]+\"?\,?)+\]";
  $pat="/(".$pat1."|".$pat2.")".$pat3."/ms";
  while (preg_match($pat,$h,$m)) {
  $c0=array();
  $x=0;
  $code=str_replace($m[1],"\$c0=",$m[0].";");
  eval ($code);
  $pat = "/\(" . $m[3].$m[6] . "\,(0x[a-z0-9_]+)/";
  if (preg_match($pat, $h, $n)) {
    $x = hexdec($n[1]);
    for ($k = 0; $k < $x; $k++) {
      array_push($c0, array_shift($c0));
    }
  }
  $h=str_replace("+","",$h);
  // _0x_0x36fc("0x0","UhHR")
  $pat="/((_0x)?[a-z0-9_]+)\(\"0x0\"\,\"/ms";
  if (preg_match($pat,$h,$f)) {
  $pat   = "/(".$f[1].")\(\"(0x[a-z0-9_]+)\",\s?\"(.*?)\"\)/ms"; //better
  if (preg_match_all($pat, $h, $p)) {
    for ($z = 0; $z < count($p[0]); $z++) {
      $h = str_replace($p[0][$z], '"'.abc($c0[hexdec($p[2][$z])], $p[3][$z]).'"', $h);
    }
  }
  }
 }
  preg_match("/eval\(\w+\((\"|\')(\w+)(\"|\')/",$h,$p);

  $dec = rc4($p[2], $rc4);
  } else {
    $dec=$h;
  }
  if (preg_match('/\/\/.+\.mp4/', $dec, $m)) {
  $link=$m[1];
  if (preg_match('/([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.(srt|vtt)))/', $dec, $s))
  $srt=$s[1];
  } else {
    $link="";
  }
  echo $link."<BR>"."\n";
  echo $dec;
?>
