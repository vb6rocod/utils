<?php
 /* resolve clicknupload.co
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

$filelink="https://www.clicknupload.co/rhqqbz328ejz";
if (strpos($filelink,"clicknupload.") !== false) {
  $pattern = '@(clicknupload\.(?:com|me|link|co))/(?:f/)?([0-9A-Za-z]+)@';
  preg_match($pattern,$filelink,$m);
  $id=$m[2];
  $ua='Mozilla/5.0 (Windows NT 10.0; rv:66.0) Gecko/20100101 Firefox/66.0';
  $l="https://www.clicknupload.co/".$id;
  $post="op=download2&id=".$id."&rand=&referer=https://www.clicknupload.co/".$id."&method_free=Free+Download+>>&method_premium=&adblock_detected=0";
  $head=array('Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
   'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
   'Accept-Encoding: deflate',
   'Content-Type: application/x-www-form-urlencoded',
   'Content-Length: '.strlen($post).'',
   'Origin: https://www.clicknupload.co',
   'Connection: keep-alive',
   'Referer: https://www.clicknupload.co/'.$id);
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch,CURLOPT_HTTPHEADER,$head);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_POST,1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h = curl_exec($ch);
  curl_close($ch);
  $pat='@class\=\"downloadbtn\"[^>]+onClick\s*\=\s*\"window\.open\(\'([^\']+)@';
  if (preg_match($pat,$h,$r))
   $link=$r[1];
  else
   $link="";
}
echo $link;
?>
