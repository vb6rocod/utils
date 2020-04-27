<?php
 /* resolve movcloud.net
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

$filelink="https://movcloud.net/embed/ns-9qmdfjZfB";
if (strpos($filelink,"movcloud.net") !==false) {
  if (preg_match("/\/embed\/([a-zA-Z0-9_\-]+)/",$filelink,$m)) {
   $id=$m[1];
   $l="https://api.movcloud.net/stream/".$id;
   $head=array('User-Agent: Mozilla/5.0 (Windows NT 10.0; rv:75.0) Gecko/20100101 Firefox/75.0',
   'Accept: application/json',
   'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
   'Accept-Encoding: deflate',
   'Content-Type: application/json',
   'Origin: https://movcloud.net');
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h = curl_exec($ch);
  curl_close($ch);
  $r=json_decode($h,1);
  $link=$r['data']['sources'][0]['file'];
  if (strpos($link,"http") !== false && $flash <> "flash")  // $flash="jwplayer"
   $link=$link."|Origin=".urlencode("https://movcloud.net");
  }
}
echo $link;
?>
