<?php
/* resolve kt_player.js (https://www.playhd.fun/player/kt_player.js?v=4.0.4)
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
$filelink = "https://www.playhd.fun/embed/2091";
$html = file_get_contents($filelink);
$t1 = explode("license_code: '", $html);
$t2 = explode("'", $t1[1]);
$d = $t2[0];
$t1 = explode("function/0/", $html);
$t2 = explode("'", $t1[1]);
$orig = $t2[0];
$c = 16;

for ($f = "", $g = 1; $g < strlen($d); $g++)
	{
	$f.= preg_match("/[1-9]/", $d[$g]) ? $d[$g] : 1;
	}

for ($j = intval(strlen($f) / 2) , $k = substr($f, 0, $j + 1) , $l = substr($f, $j) , $g = $l - $k, $g < 0 && ($g = - $g) , $f = $g, $g = $k - $l, $g < 0 && ($g = - $g) , $f+= $g, $f = $f * 2, $f = "" . $f, $i = $c / 2 + 2, $m = "", $g = 0; $g < $j + 1; $g++)
	{
	for ($h = 1; $h <= 4; $h++)
		{
		$n = $d[$g + $h] + $f[$g];
		$n >= $i && ($n-= $i);
		$m.= $n;
		}
	}

$t1 = explode("/", $orig);
$j = $t1[5];
$h = substr($j, 0, 32);
$i = $m;

for ($j = $h, $k = strlen($h) - 1; $k >= 0; $k--)
	{
	for ($l = $k, $m = $k; $m < strlen($i); $m++) $l+= $i[$m];
	for (; $l >= strlen($h);) $l-= strlen($h);
	for ($n = "", $o = 0; $o < strlen($h); $o++)
		{
		$n.= $o == $k ? $h[$l] : ($o == $l ? $h[$k] : $h[$o]);
		}

	$h = $n;
	}

$link = str_replace($j, $h, $orig);
echo $link;
?>
