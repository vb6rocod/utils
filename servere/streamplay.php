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
    preg_match("/return\s+r\[(_[a-z0-9]+)/", $m[0], $p);
    $t1 = explode("var r", $m[0]);
    $t2 = explode("var", $t1[1]);
    $t3 = explode('=', $t2[1]);
    $t4 = explode(";", $t3[1]);
    $t5 = str_replace($p[1], "abc", $t4[0]);
    $a  = preg_replace("/\'(0x[0-9a-z]+)\'/", '\$c0[\\1]', $t5);
    $a  = str_replace("+", ".", $a);
    $b  = "\$d=" . $a . ";";
    eval($b);
    $d = str_replace("r.splice(", "array_splice(\$r,", $d);
    $d = str_replace("r[", "\$r[", $d);

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
