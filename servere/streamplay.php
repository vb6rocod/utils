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
$filelink = "https://streamplay.to/hpeg1vyu75yc";
if (strpos($filelink,"streamplay.") !== false) {
require_once("JavaScriptUnpacker.php");
function abc($a52, $a10)
{
    $a54 = array();
    $a55 = 0x0;
    $a56 = '';
    $a57 = '';
    $a58 = '';
    $a52 = base64_decode($a52);
    $a52 = mb_convert_encoding($a52, 'ISO-8859-1', 'UTF-8');
    for ($a72 = 0x0; $a72 < 0x100; $a72++) {
        $a54[$a72] = $a72;
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

preg_match('/(?:\/\/|\.)(streamplay\.(?:to|club|top|me))\/(?:embed-|player-)?([0-9a-zA-Z]+)/', $filelink, $m);
$filelink = "https://streamplay.to/player-" . $m[2] . "-920x360.html";
$ua       = $_SERVER["HTTP_USER_AGENT"];
$head     = array(
    'Cookie: lang=1; ref_yrp=http%3A%2F%2Fcecileplanche-psychologue-lyon.com%2Fshow%2Fthe-good-cop%2Fseason-1%2Fepisode-2; ref_kun=1'
);
$ch       = curl_init($filelink);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_USERAGENT, $ua);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_ENCODING, "");
curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
curl_setopt($ch, CURLOPT_REFERER, $filelink);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
$h = curl_exec($ch);
curl_close($ch);
//echo $h;
//die();
$jsu = new JavaScriptUnpacker();
$out = $jsu->Unpack($h);
if (preg_match('/([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_]*(\.mp4))/', $out, $m)) {
    $link = $m[1];
    $t1   = explode("/", $link);
    $a145 = $t1[3];
    if (preg_match('/([\.\d\w\-\.\/\\\:\?\&\#\%\_]*(\.(srt|vtt)))/', $out, $xx)) {
        //src:"/srt/00686/ic19hoyeob1d_Italian.vtt"
        $srt = $xx[1];
    }
    if (strpos("http", $srt) === false && $srt)
        $srt = "http://streamplay.to" . $srt;

    preg_match("/var\s+(_0x[a-z0-9]{4,})\s?\=\s?\[(.*)\]/", $h, $m);
    $t1       = explode(";", $m[2]);
    $php_code = "\$c0=array(" . str_replace("]", "", $t1[0]) . ");";
    eval($php_code);
    $pat = "/\(" . $m[1] . "\,(0x[a-z0-9]+)/";
    preg_match($pat, $m[0], $n);
    $x = hexdec($n[1]);
    //echo $x;
    for ($k = 0; $k < $x; $k++) {
        array_push($c0, array_shift($c0));
    }
    $t1=explode('Array[',$h);     //Array[_0x52e0(_0x54d7('0x22','suqw'
    $t2=explode('(',$t1[1]);
    $t3=$t2[1];
    $pat="/(".$t3.")\(\'(0x[a-z0-9]+)\',\s*\'([\w\#\[\]\(\)\%\&\!\^\@\$\{\}]+)\'\)/";
    $pat="/(".$t3.")\(\'(0x[a-z0-9]+)\',\s*\'(.*?)\'\)/"; //better
    preg_match_all($pat,$h,$p);
    $js="";
    $code=array();
    for ($z=0;$z<count($p[0]);$z++) {
     $v= abc($c0[hexdec($p[2][$z])],$p[3][$z]);
     if (preg_match("/^0x[a-f0-9]+/",$v,$index))
       $code[hexdec($index[0])]=base64_decode($code[hexdec($index[0])]);
     else
       $code[$z]=$v;
    }
    $js=implode($code);
    preg_match("/\(\"body\"\)\.data\(\"e0\"\,(\d+)\)/",$js,$e0);
    $js=str_replace("$".$e0[0].";","",$js);
    $js=str_replace('$("body").data("e0")',$e0[1],$js);

    preg_match("/\(\"body\"\)\.data\(\"e1\"\,(\d+)\)/",$js,$e1);
    $js=str_replace("$".$e1[0].";","",$js);
    $js=str_replace('$("body").data("e1")',$e1[1],$js);
    
    preg_match("/\(\"body\"\)\.data\(\"e2\"\,(\d+)\)/",$js,$e2);
    $js=str_replace("$".$e2[0].";","",$js);
    $js=str_replace('$("body").data("e2")',$e2[1],$js);
    
    $js=str_replace('"',"",$js);
    $d = str_replace("r.splice(", "array_splice(\$r,", $js);
    $d = str_replace("r[", "\$r[", $d);
    preg_match("/(array\_splice(.*))\;/",$d,$f);
    $d=$f[0];
    $r = str_split(strrev($a145));
    eval($d);
    $x    = implode($r);
    $link = str_replace($a145, $x, $link);
} else {
    $link = "";
}
}
echo $link;
?>
