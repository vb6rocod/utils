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
$filelink = "https://powvideo.net/o4xa8jywtx07";
if (strpos($filelink,"powvideo.") !== false || strpos($filelink,"povvideo.") !== false) {
require_once("JavaScriptUnpacker.php");
preg_match('/(powvideo|powvideo)\.(net|cc)\/(?:embed-|iframe-|preview-|)([a-z0-9]+)/',$filelink,$m);
$id=$m[3];
$filelink="https://povvideo.net/embed-".$id.".html";
$ua       = $_SERVER["HTTP_USER_AGENT"];
$head     = array(
    'Cookie: ref_url='.urlencode($filelink).'; BJS0=1; BJS1=1; e_'.$id.'=123456789'
);
$l="https://povvideo.net/iframe-".$id."-954x562.html";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $l);
curl_setopt($ch, CURLOPT_REFERER, "https://povvideo.net/preview-".$id."-732x695.html");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch,CURLOPT_HTTPHEADER,$head);
curl_setopt($ch, CURLOPT_USERAGENT, $ua);
curl_setopt($ch, CURLOPT_ENCODING, "");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
$h = curl_exec($ch);
curl_close($ch);
$t1=explode("function getCalcReferrer",$h);
$h=$t1[1];
$jsu = new JavaScriptUnpacker();
$out = $jsu->Unpack($h);
if (preg_match('/([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_]*(\.mp4))/', $out, $m)) {
    $link=$m[1];
    $t1=explode("/",$link);
    $a145=$t1[3];
    preg_match("/var\s+(_0x[a-z0-9]{4,})\s?\=\s?\[(.*)\]/", $h, $m);

    $t1       = explode(";", $m[2]);
    $php_code = "\$c0=array(" . str_replace("]", "", $t1[0]) . ");";
    eval($php_code);

    $pat = "/\(" . $m[1] . "\,(0x[a-z0-9]+)/";
    preg_match($pat, $m[0], $n);
    $x = hexdec($n[1]);

    for ($k = 0; $k < $x; $k++) {
        array_push($c0, array_shift($c0));
    }

    $t1=explode('Array[',$h);
    $t2=explode('(',$t1[1]);
    $t3=$t2[0];
    $pat="/(".$t3.")\(\'(0x[a-z0-9]+)\',\s*\'([\w\#\[\]\(\)\%\&\!\^\@\$\{\}]+)\'\)/";
    preg_match_all($pat,$h,$p);
    for ($z=0;$z<count($p[0]);$z++) {
     $h=str_replace($p[0][$z],abc($c0[hexdec($p[2][$z])],$p[3][$z]),$h);
    }
    $t1=explode("[reverse]();",$h);
    $t2=explode("return",$t1[1]);
    $js=$t2[0];
    $js=str_replace("+",";",$js);
    preg_match("/(_0x[a-z0-9]{4,})/",$js,$y);

    $js=str_replace($y[1],"r",$js);
    $js=str_replace("'","",$js);

    $js=str_replace("r[splice](","array_splice(\$r,",$js);
    $r = str_split(strrev($a145));
    eval($js);
    $x    = implode($r);
    $link = str_replace($a145, $x, $link);
} else {
    $link = "";
}
}
echo $link;
?>
