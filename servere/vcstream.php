<?php
/* resolve vcstream
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
$filelink = "https://vcstream.to/embed/5b979c9fa292b/Strangers.S01E01.720p.HDTV.x264-ORGANiC.mp4";
//https://vidcloud.co/embed/5b979c9fa292b/Strangers.S01E01.720p.HDTV.x264-ORGANiC.mp4
if (strpos($filelink, "vcstream.to") !== false  || strpos($filelink,"vidcloud.co") !== false)
	{
    preg_match("/(embed\/|fid\=)([a-zA-Z0-9]+)/",$filelink,$m);
    $l="https://vidcloud.co/player?fid=".$m[2]."&page=embed";

    $h=file_get_contents($l);
    $h=str_replace("\\","",$h);
    $t1=explode('file":"',$h);
    $t2=explode('"',$t1[1]);
    $link=$t2[0];
	if (preg_match('/([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.(srt|vtt)))/', $h, $m)) $srt = $m[1];
	}

echo $link;
?>
