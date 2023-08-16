<?php
 /* resolve rutube.ru
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


$filelink="https://rutube.ru/video/b6d773c932cb14d6897b7666fdcfa82f/";
$filelink="https://rutube.ru/video/50feffcba8e7f174bc4f762545adf898/";
if (strpos($filelink,"rutube.ru") !== false) {
  $pattern = '/(rutube\.ru)\/(?:play\/embed|video)\/([0-9a-zA-Z]+)/';
  if (preg_match($pattern,$filelink,$m)) {
  $id=$m[2];
  $l="https://rutube.ru/api/play/options/".$id."/?no_404=true&referer=https%253A%252F%252Frutube.ru&pver=v2";
  $head=array('User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/116.0',
  'Accept: */*',
  'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
  'Accept-Encoding: deflate',
  'Referer: '.$filelink,
  'Origin: https://rutube.ru',
  'Connection: keep-alive');
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);
  curl_setopt($ch, CURLOPT_HTTPHEADER,$head);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h = curl_exec($ch);
  curl_close($ch);
  $r=json_decode($h,1);
  if (isset($r['video_balancer']['m3u8'])) {
    $link=$r['video_balancer']['m3u8'];
 } else
    $link="";
 }
}
/////////////////////////////////
  // if you want to play max resolution
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $link);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);
  //curl_setopt($ch, CURLOPT_HTTPHEADER,$head);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h = curl_exec($ch);
  curl_close($ch);
  if (preg_match_all ("/^(?!#).+/m",$h,$m)) {
    $pl=$m[0];
    $link_max_res=$pl[count($pl)-1];
  }
/////////////////////////////////

echo $link."<BR>";
echo '<a href="potplayer://'.$link.'">Play</a>';
echo '<a href="potplayer://'.$link_max_res.'">Play max res</a>';
?>
