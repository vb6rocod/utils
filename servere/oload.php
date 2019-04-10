<?php
/* resolve openload/oload
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
 * $link --> video_link
 */

$filelink = "http://openload.co/embed/j1dKV_UqHa8/";
if (strpos($filelink, "openload") !== false || strpos($filelink, "oload") !== false) {
    include("ol.php");
    function decode_code($code)
    {
        return preg_replace_callback("@\\\(x)?([0-9a-f]{2,3})@", function($m)
        {
            return chr($m[1] ? hexdec($m[2]) : octdec($m[2]));
        }, $code);
    }
    $filelink = str_replace("openload.co/f/", "openload.co/embed/", $filelink);
    $t1       = explode("/", $filelink);
    $filelink = "https://openload.co/embed/" . $t1[4];

    $ua = "Mozilla/5.0 (Windows NT 10.0; WOW64; rv:46.0) Gecko/20100101 Firefox/46.0";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $filelink);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:46.0) Gecko/20100101 Firefox/46.0');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_REFERER, "https://openload.co/");
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $h1 = curl_exec($ch);
    curl_close($ch);

    if (preg_match('/([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.(srt|vtt)))/', $h1, $m)) {
        $srt = $m[1];
        if ($srt) {
            if (strpos($srt, "http") === false)
                $srt = "https://openload.co" . $srt;
        }
    }

    $pattern = '/(embed|f)\/([0-9a-zA-Z-_]+)/';
    preg_match($pattern, $filelink, $m);
    $id = $m[2];

    if (preg_match("/[a-z0-9]{40,}/", $h1, $r))
        $enc_t = $r[0];
    $x = decode_code($h1);

    $x = str_replace(";", ";" . "\n", $x);
    preg_match_all("/case\'3\'(.*)/", $x, $m);
    $t1  = explode("parseInt('", $m[0][1]);
    $t8  = explode("0x4", $t1[1]);
    $t9  = explode(')', $t8[1]);
    $ch7 = $t9[0];
    $t2  = explode("'", $t1[1]);
    $t4  = explode("-", $t1[1]);
    $t5  = explode("+", $t4[1]);
    $t6  = explode("/(", $t1[1]);
    $t7  = explode("-", $t6[1]);
    $ch1 = $t2[0];
    $ch4 = $t5[0];
    $ch5 = $t7[0];
    preg_match_all("/case\'11\'(.*)/", $x, $m);
    $t1  = explode("parseInt('", $m[0][0]);
    $t2  = explode("'", $t1[1]);
    $ch2 = $t2[0];
    $t1  = explode(")", $m[0][0]);
    $t2  = explode(";", $t1[1]);
    $ch6 = trim($t2[0]);
    $ch1 = str_replace("0x", "", $ch1);
    $ch2 = str_replace("0x", "", $ch2);
    preg_match_all("/case\'4\'(.*)/", $x, $m);
    $t1 = explode("]", $m[0][2]);
    preg_match("/(\d+)((\-|\+)(\d+))/", $t1[1], $m);
    $ch3 = $m[2];
    $dec = ol($enc_t, $ch1, $ch2, $ch3, $ch4, $ch5, $ch6, $ch7);
    if (strpos($dec, $id) === false) {
        $l  = "https://api.openload.co/pair";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $l);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $h2 = curl_exec($ch);
        curl_close($ch);
        $l  = "https://api.openload.co/1/streaming/get?file=" . $id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $l);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $h2 = curl_exec($ch);
        curl_close($ch);
        $t1 = explode('url":"', $h2);
        $t2 = explode("?", $t1[1]);
        if ($t1)
            $link = str_replace("\\", "", $t2[0]) . ".mp4";
    } else {
        $link = "https://openload.co/stream/" . $dec . "?mime=true";
        $ch   = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_REFERER, "https://openload.co/");
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $ret = curl_exec($ch);
        curl_close($ch);
        $t1          = explode("Location:", $ret);
        $t2          = explode("?", $t1[1]);
        $link        = urldecode(trim($t2[0]));
        $movie_file  = substr(strrchr(urldecode($link), "/"), 1);
        $movie_file1 = substr($movie_file, 0, -4);
        $movie_file2 = preg_replace('/[^A-Za-z0-9_]/', '_', $movie_file1);
        $link        = str_replace($movie_file1, $movie_file2, $link);
        $link        = str_replace("https", "http", $link) . ".mp4";
    }
}
echo $link;
?>
