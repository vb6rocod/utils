 <?php
/* resolve powvideo "splice"
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
 * $filelink = "https://powvideo.net/o4xa8jywtx07";
 * $link --> video_link
 */
include ("obfJS.php");
require_once("JavaScriptUnpacker.php");
$filelink = "https://powvideo.net/o4xa8jywtx07";
$filelink="https://powvideo.net/0ouzz4i4yvvs";
if (strpos($filelink, "powvideo.") !== false || strpos($filelink, "povvideo.") !== false) {
    preg_match('/(powvideo|povvideo)\.(net|cc|co)\/(?:embed-|iframe-|preview-|)([a-z0-9]+)/', $filelink, $m);
    $id       = $m[3];
    $host=parse_url($filelink)['host'];
    $filelink="https://".$host."/embed-".$id.".html";
    $ua = $_SERVER["HTTP_USER_AGENT"];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $filelink);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    curl_setopt($ch, CURLOPT_HEADER,1);
    curl_setopt($ch, CURLOPT_NOBODY,1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $h = curl_exec($ch);
    if (preg_match("/Location:\s*(http.+)/",$h,$m))
      $host=parse_url(trim($m[1]))['host'];
    $head=array('Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
     'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
     'Accept-Encoding: deflate',
     'Connection: keep-alive',
     'Referer: https://'.$host.'/preview-'.$id.'-1280x665.html',
     'Cookie: ref_url='.urlencode($filelink).'; e_'.$id.'=123456789',
     'Upgrade-Insecure-Requests: 1');
    $l = "https://".$host."/iframe-" . $id . "-954x562.html";

    curl_setopt($ch, CURLOPT_URL, $l);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
    curl_setopt($ch, CURLOPT_NOBODY,0);
    $h = curl_exec($ch);
    curl_close($ch);

    $jsu   = new JavaScriptUnpacker();
    $out   = $jsu->Unpack($h);
    if (preg_match('/([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_]*(\.mp4))/', $out, $m)) {
        $link = $m[1];
        $t1   = explode("/", $link);
        $a145 = $t1[3];
    if (preg_match('/([\.\d\w\-\.\/\\\:\?\&\#\%\_]*(\.(srt|vtt)))/', $out, $xx)) {
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
