 <?php
 /* resolve streamplay "splice"
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
 * $filelink = "https://streamplay.to/hpeg1vyu75yc";
 * $link --> video_link
 */
include ("obfJS.php");
require_once("JavaScriptUnpacker.php");
$filelink = "https://streamplay.to/hpeg1vyu75yc";
if (strpos($filelink,"streamplay.") !== false) {
    preg_match('/(?:\/\/|\.)(streamplay\.(?:to|club|top|me))\/(?:embed-|player-)?([0-9a-zA-Z]+)/', $filelink, $m);
    $id=$m[2];
    $ua       = $_SERVER["HTTP_USER_AGENT"];
    $host=parse_url($filelink)['host'];
    $l1="https://".$host."/embed-".$id.".html";
    $ua = $_SERVER["HTTP_USER_AGENT"];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $l1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    curl_setopt($ch, CURLOPT_HEADER,1);
    curl_setopt($ch, CURLOPT_NOBODY,1);

    curl_setopt($ch, CURLOPT_REFERER, $filelink);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $h = curl_exec($ch);
    curl_close($ch);
    if (preg_match("/Location:\s*(http.+)/",$h,$m))
      $host=parse_url(trim($m[1]))['host'];
    $head=array('Cookie: file_id=13136922; ref_yrp=; ref_kun=1; BJS0=1');
    $l="https://".$host."/player-".$id.".html";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $l);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
    curl_setopt($ch, CURLOPT_REFERER, $l1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $h = curl_exec($ch);
    curl_close($ch);

    $jsu = new JavaScriptUnpacker();
    $out = $jsu->Unpack($h);
    if (preg_match('/([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_]*(\.mp4))/', $out, $m)) {
    $link = $m[1];
    $t1   = explode("/", $link);
    $a145 = $t1[3];
    if (preg_match('/([\.\d\w\-\.\/\\\:\?\&\#\%\_]*(\.(srt|vtt)))/', $out, $xx)) {
        //src:"/srt/00686/ic19hoyeob1d_Italian.vtt"
        $srt = $xx[1];
        if (strpos("http", $srt) === false && $srt)
            $srt = "https://".$host.$srt;
    }
    $enc=$h;
    $dec = obfJS();
    $dec=str_replace("Math.","",$dec);
    $dec=preg_replace_callback(
     "/Math\[(.*?)\]/",
     function ($matches) {
      return preg_replace("/(\s|\"|\+)/","",$matches[1]);;
     },
     $dec
    );
    $dec=preg_replace_callback(
     "/\[([a-dt\"\+]+)\]/",
     function ($matches) {
      return ".".preg_replace("/(\s|\"|\+)/","",$matches[1]);;
     },
     $dec
    );
    $dec=str_replace("PI","M_PI",$dec);
    $dec=preg_replace("/\/\*.*?\*\//","",$dec);  // /* ceva */

    if (preg_match_all("/(\\$\(\s*\"\s*([a-zA-Z0-9_\.\:\_\-]+)\s*\"\)\.data\s*\(\s*\"(\w+)\")\s*\,([a-zA-Z0-9-\s\+\)\(\"]+)\)/", $dec, $m)) {
     for ($k=0;$k<count($m[0]);$k++) {
      $orig=$m[0][$k];
      $rep=$m[1][$k];
      $func=$m[3][$k];
      $val=$m[4][$k];
      $func=str_replace(" ","_",$func);
      $dec=str_replace($orig,"\$".$func."=".$val,$dec).";";
      $pat="/".preg_quote($rep)."\s*\)"."/";
      $dec=preg_replace($pat,"\$".$func,$dec);
     }
    }
    if (preg_match("/((r\=)|(r\.splice)(.*?))\';eval/ms",$dec,$m)) {
     $rez=$m[1];
     $rez=preg_replace("/r\.splice\s*\(/","array_splice(\$r,",$rez);
     $rez=preg_replace("/r\s*\[/","\$r[",$rez);
     $rez=preg_replace("/r\s*\=/","\$r=",$rez);
     $r = str_split(strrev($a145));
     eval($rez);
     $x    = implode($r);
     $link = str_replace($a145, $x, $link);
    } else {
     $link="";
    }
    } else {
     $link = "";
    }
}
echo "<BR>".$rez;
echo "<BR>".$a145."<BR>".$link."<BR>";
var_dump (get_headers($link));
$t1=explode("function getCalcReferrer",$enc);
$t2=explode("</script",$t1[1]);
echo "\n"."<BR>".$t2[0];
?>
