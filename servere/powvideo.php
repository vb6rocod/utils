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
    for ($a72 = 0x0; $a72 < 0x100; $a72++) {     //new
        $a54[$a72] = (0x3 + $a72 + pow(0x7c,0x0)) % 0x100;
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
$filelink="https://powvideo.net/0ouzz4i4yvvs";
if (strpos($filelink, "powvideo.") !== false || strpos($filelink, "povvideo.") !== false) {
    require_once("JavaScriptUnpacker.php");
    preg_match('/(powvideo|powvideo)\.(net|cc)\/(?:embed-|iframe-|preview-|)([a-z0-9]+)/', $filelink, $m);
    $id       = $m[3];
    $filelink = "https://povvideo.net/embed-" . $id . ".html";
    $ua       = $_SERVER["HTTP_USER_AGENT"];
    $head     = array(
        'Cookie: ref_url=' . urlencode($filelink) . '; BJS0=1; BJS1=1; e_' . $id . '=123456789'
    );
    $l        = "https://powvideo.net/iframe-" . $id . "-954x562.html";
    $ch       = curl_init();
    curl_setopt($ch, CURLOPT_URL, $l);
    curl_setopt($ch, CURLOPT_REFERER, "https://povvideo.net/preview-" . $id . "-732x695.html");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    curl_setopt($ch, CURLOPT_ENCODING, "");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $h = curl_exec($ch);
    curl_close($ch);

    $h = str_replace("/player7", "https://povvideo.net/player7", $h);
    $h = str_replace("/js", "https://povvideo.net/js", $h);
    //file_put_contents("s1.html",$h);
    //die();

    if (strpos($h, "function getCalcReferrer") !== false) {
       $t1 = explode("function getCalcReferrer", $h);
       $h  = $t1[1];
    }
    $jsu   = new JavaScriptUnpacker();
    $out   = $jsu->Unpack($h);
    if (preg_match('/([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_]*(\.mp4))/', $out, $m)) {
        $link = $m[1];
        $t1   = explode("/", $link);
        $a145 = $t1[3];
    if (preg_match('/([\.\d\w\-\.\/\\\:\?\&\#\%\_]*(\.(srt|vtt)))/', $out, $xx)) {
        $srt = $xx[1];
    if (strpos("http", $srt) === false && $srt)
        $srt = "https://powvideo.net" . $srt;
    }

    /* search first array var _0x1107=['asass','ssdsds',.....] */
    /*
    $c0 fisrt array
    $c1 second array (if exist) but only after replace with function abc
    */

    /* search first array var _0x1107=['asass','ssdsds',.....] */
    if (preg_match("/(var\s+(_0x[a-z0-9]+))\=\[(\'[a-zA-Z0-9\=\+\/]+\'\,?)+\]/ms", $h, $m)) {
        $php_code = str_replace($m[1], "\$c0", $m[0]) . ";";
        eval($php_code);
        //print_r ($c0);
        /* rotate with 0xd0 search (_0x1107,0xd0)) */
        $pat = "/\(" . $m[2] . "\,(0x[a-z0-9]+)/";
        if (preg_match($pat, $h, $n)) {
            $x = hexdec($n[1]);
            for ($k = 0; $k < $x; $k++) {
                array_push($c0, array_shift($c0));
            }
        }
        //echo $x;
        $h = str_replace("+", "", $h);
        /* check if exist second array and get replacement for abc function and slice*/
        /* search Array[_0x3504(_0xfcc8('0x22','uSSR'))] */
        if (preg_match("/Array\[(_0x[a-z0-9]+)\((_0x[a-z0-9]+)\(/", $h, $f)) {
            $func  = $f[2];
            $func1 = $f[1];
            /* find and replace _0xfcc8('0x24','EOVX') with abc(a,b) */
            $pat   = "/(" . $func . ")\(\'(0x[a-z0-9]+)\',\s?\'(.*?)\'\)/"; //better
            if (preg_match_all($pat, $h, $p)) {
                for ($z = 0; $z < count($p[0]); $z++) {
                    $h = str_replace($p[0][$z], "'".abc($c0[hexdec($p[2][$z])], $p[3][$z])."'", $h);
                }
            }
            //echo $h;
            /* search for second array var _0x13e4=[xcxcxc,xcxc,xcxcx ...] */
            if (preg_match_all("/(var\s+(_0x[a-z0-9]+))\=\[(\'[a-zA-Z0-9\=\+\/]+\'\,?)+\]/ms", $h, $m)) {
            //print_r ($m);
            if (isset($m[1][1])) {
                $php_code = $m[0][1];
                $php_code = str_replace($m[1][1], "\$c1", $php_code) . ";";
                /* get second array $c1 and rotate */
                eval($php_code);
                //print_r ($c1);
                //die();
                $pat = "/\(" . $m[2][1] . "\,(0x[a-z0-9]+)/ms";
                if (preg_match($pat, $h, $n)) {
                    $x = hexdec($n[1]);
                    for ($k = 0; $k < $x; $k++) {
                        array_push($c1, array_shift($c1));
                    }
                }
                //print_r ($c1);
                /* search and replace _0x3504(0x6) etc with second array $c1 */
                $pat = "/" . $func1 . "\(\'(0x[0-9a-f]+)\'\)/ms";
                $pat1   = "/(" . $func1 . ")\(\'(0x[a-z0-9]+)\',\s?\'(.*?)\'\)/"; //better
                if (preg_match_all($pat, $h, $q)) {
                   for ($k = 0; $k < count($q[1]); $k++) {
                    $h = str_replace($q[0][$k], base64_decode($c1[hexdec($q[1][$k])]), $h);
                   }
                } else if (preg_match_all($pat1, $h, $p)) {
                   //print_r ($p);
                   for ($z = 0; $z < count($p[0]); $z++) {
                    $h = str_replace($p[0][$z], abc($c1[hexdec($p[2][$z])], $p[3][$z]), $h);
                   }
                   //echo $h;
                }
            }
            }
            /* now $h contain  var _0x1d4745=r.splice ..... eval(_0x1d4745) */
            if (preg_match("/((\w)\.splice.*?)eval/ms", $h, $e)) {
                $let = $e[2];
                /* now is "r" - for future.... */
                $out = str_replace(";;", ";", $e[1]);
            } else {
                $out = "";
            }
            /* if not second array search Array[_0x5f0b('0x0','9YsV')] */
        } else if (preg_match("/Array\[(_0x[a-z0-9]+)\(\'0x/ms", $h, $f)) {
            $func = $f[1];
            /* find and replace _0xfcc8('0x24','EOVX') with abc(a,b) */
            $pat  = "/(" . $func . ")\(\'(0x[a-z0-9]+)\',\s?\'(.*?)\'\)/ms";
            preg_match_all($pat, $h, $p);
            for ($z = 0; $z < count($p[0]); $z++) {
                $h = str_replace($p[0][$z], abc($c0[hexdec($p[2][$z])], $p[3][$z]), $h);
            }
            //echo $h;
            /* now $h contain  var _0x1d4745=r.splice ..... eval(_0x1d4745) */
            if (preg_match("/((\w)\.splice.*?)eval/ms", $h, $e)) {
                $out = str_replace(";;", ";", $e[1]);
            } else {
                $out = "";
            }
        }
        /* $out can like this r.splice( "3", 1);$("body").data("f 0",197);r[$("body").data("f 0")&15]=r.splice($("body").data("f 0")>>(33), 1 */


    } else if (preg_match("/(function\s?(_0x[a-z0-9]+)\(\)\{return)\[(\'[a-zA-Z0-9\=\+\/]+\'\,?)+\]/ms", $h, $m)) {
        $php_code = str_replace($m[1], "\$c0=", $m[0]) . ";";
        eval($php_code);
        $pat = "/\(" . $m[2] . "\,(0x[a-z0-9]+)/";
        if (preg_match($pat, $h, $n)) {
            $x = hexdec($n[1]);
            for ($k = 0; $k < $x; $k++) {
                array_push($c0, array_shift($c0));
            }
        }
        $h = str_replace("+", "", $h);
        if (preg_match("/Array\[(_0x[a-z0-9]+)\(\'0x/ms", $h, $f)) {
            $func = $f[1];
            /* find and replace _0xfcc8('0x24','EOVX') with abc(a,b) */
            $pat  = "/(" . $func . ")\(\'(0x[a-z0-9]+)\',\s?\'(.*?)\'\)/ms";
            preg_match_all($pat, $h, $p);
            for ($z = 0; $z < count($p[0]); $z++) {
                $h = str_replace($p[0][$z], abc($c0[hexdec($p[2][$z])], $p[3][$z]), $h);
            }
            if (preg_match("/((\w)\.splice.*?)eval/ms", $h, $e)) {
                $out = str_replace(";;", ";", $e[1]);
            } else {
                $out = "";
            }
        } else if (preg_match("/Array\[(_0x[a-z0-9]+)\((_0x[a-z0-9]+)\(/", $h, $f)) {
            $func  = $f[2];
            $func1 = $f[1];
            /* find and replace _0xfcc8('0x24','EOVX') with abc(a,b) */
            $pat   = "/(" . $func . ")\(\'(0x[a-z0-9]+)\',\s?\'(.*?)\'\)/"; //better
            if (preg_match_all($pat, $h, $p)) {
                for ($z = 0; $z < count($p[0]); $z++) {
                    $h = str_replace($p[0][$z], "'".abc($c0[hexdec($p[2][$z])], $p[3][$z])."'", $h);
                }
            }
            /* search for second array var _0x13e4=[xcxcxc,xcxc,xcxcx ...] */
            if (preg_match_all("/(var\s+(_0x[a-z0-9]+))\=\[(\'[a-zA-Z0-9\=\+\/]+\'\,?)+\]/ms", $h, $m)) {
            //print_r ($m);
            if (isset($m[1][0])) {
                $php_code = $m[0][0];
                $php_code = str_replace($m[1][0], "\$c1", $php_code) . ";";
                /* get second array $c1 and rotate */
                eval($php_code);
                //print_r ($c1);
                //die();
                $pat = "/\(" . $m[2][0] . "\,(0x[a-z0-9]+)/ms";
                if (preg_match($pat, $h, $n)) {
                    $x = hexdec($n[1]);
                    for ($k = 0; $k < $x; $k++) {
                        array_push($c1, array_shift($c1));
                    }
                }
                //print_r ($c1);
                /* search and replace _0x3504(0x6) etc with second array $c1 */
                $pat = "/" . $func1 . "\(\'(0x[0-9a-f]+)\'\)/ms";
                $pat1   = "/(" . $func1 . ")\(\'(0x[a-z0-9]+)\',\s?\'(.*?)\'\)/"; //better
                if (preg_match_all($pat, $h, $q)) {
                   for ($k = 0; $k < count($q[1]); $k++) {
                    $h = str_replace($q[0][$k], base64_decode($c1[hexdec($q[1][$k])]), $h);
                   }
                } else if (preg_match_all($pat1, $h, $p)) {
                   //print_r ($p);
                   for ($z = 0; $z < count($p[0]); $z++) {
                    $h = str_replace($p[0][$z], abc($c1[hexdec($p[2][$z])], $p[3][$z]), $h);
                   }
                   //echo $h;
                }
            }
            }
            /* now $h contain  var _0x1d4745=r.splice ..... eval(_0x1d4745) */
            if (preg_match("/((\w)\.splice.*?)eval/ms", $h, $e)) {
                $let = $e[2];
                /* now is "r" - for future.... */
                $out = str_replace(";;", ";", $e[1]);
            } else {
                $out = "";
            }
        }
    }
    /* $out */
    echo $out;
    $out=str_replace("(Math.round(","",$out);
    $out=str_replace("Math.sqrt","sqrt",$out);
    $out=str_replace("))","",$out);
    if (preg_match_all("/\(\"body\"\)\.data\(\"(\w\s*\d)\"\,(\d+)\)/", $out, $u)) {
        for ($k = 0; $k < count($u[0]); $k++) {
            $out = str_replace("$" . $u[0][$k] . ";", "", $out);
            $out = str_replace('$("body").data("' . $u[1][$k] . '")', $u[2][$k], $out);
        }
    }
    $out = str_replace('"', "", $out);
    /* now is like array_splice($r, 3, 1);$r[388&15]=array_splice($r,388>>(3+3), 1, $r[388&15])[0]; etc */
    $d   = str_replace("r.splice(", "array_splice(\$r,", $out);
    $d   = str_replace("r.splice (", "array_splice(\$r,", $d);
    $d   = str_replace("r[", "\$r[", $d);

    if (preg_match("/(array\_splice(.*))\;/", $d, $f)) {
        $d = $f[0];
    }
    $r = str_split(strrev($a145));
    eval($d);
    $x    = implode($r);
    $link = str_replace($a145, $x, $link);
    //var_dump (get_headers($link));
} else {
    $link = "";
}
}
echo "<BR>".$a145."<BR>".$link;
?>
