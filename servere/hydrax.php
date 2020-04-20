<?php
/* resolve hydrax links, works only with player with custom headers !
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
*/
    $filelink="https://hydrax.net/watch?v=FyGe4YTxHl";
    $ua = $_SERVER['HTTP_USER_AGENT'];
    if (preg_match("/watch\?v\=([a-zA-Z0-9_\-]+)/",$filelink,$m)) {
      $slug=$m[1];
    }
    $host="hydrax.net";
    $l="https://ping.idocdn.com/";
    $post="slug=".$slug;
    $host="hydrax.net";
    $head=array('Accept: */*',
    'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
    'Accept-Encoding: deflate',
    'Content-Type: application/x-www-form-urlencoded',
    'Origin: https://'.$host.'',
    'Referer: '.$filelink.'',
    'Content-Length: '.strlen($post).'',
    'Connection: keep-alive');
    $ch = curl_init($l);
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt ($ch, CURLOPT_POST, 1);
    curl_setopt ($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $html = curl_exec($ch);
    curl_close ($ch);
    $x=json_decode($html,1);
    $serv=$x['url'];
    $l="https://".$serv."/";
    $l1=$l."ping.gif";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $l1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    curl_setopt($ch,CURLOPT_REFERER,$filelink);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_HEADER,1);
    curl_setopt($ch, CURLOPT_NOBODY,1);
    $h = curl_exec($ch);
    curl_close($ch);
    if (preg_match("/hx_stream\=(.*?)\;/",$h,$m)) {
      $link="https://".$serv."/#st=".(1000*time());
      $link=$link."|Cookie=".urlencode("hx_stream=".$m[1])."&Referer=".urlencode($filelink);
    }

?>
