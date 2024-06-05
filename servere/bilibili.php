<?php
 /* resolve bilibili
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

$filelink="https://www.bilibili.tv/en/video/4791236418470402?bstar_from=bstar-web.homepage.recommend.all";
//$filelink="https://www.bilibili.tv/en/play/1031938?bstar_from=bstar-web.homepage.recommend.all";
//$filelink="https://www.bilibili.tv/en/play/1031938/10890881?bstar_from=bstar-web.pgc-video-detail.episode.all";
if (strpos($filelink,"bilibili") !== false) {
  if (preg_match("/\/video\/(\d+)/",$filelink,$m))
    $l="https://api.bilibili.tv/intl/gateway/web/playurl?s_locale=en_US&platform=web&aid=".$m[1];
  elseif (preg_match("/\/play(\/\d+)?\/(\d+)/",$filelink,$m))
    $l="https://api.bilibili.tv/intl/gateway/web/playurl?s_locale=en_US&platform=web&ep_id=".$m[2];
  $head=array('User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:125.0) Gecko/20100101 Firefox/125.0',
  'Accept: application/json, text/plain, */*',
  'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
  'Accept-Encoding: deflate',
  'Referer: https://www.bilibili.tv/',
  'Origin: https://www.bilibili.tv',
  'Connection: keep-alive',
  'Sec-Fetch-Dest: empty',
  'Sec-Fetch-Mode: cors',
  'Sec-Fetch-Site: same-site');
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $h = curl_exec($ch);
  curl_close($ch);
  $r=json_decode($h,1);
  $x=$r['data']['playurl'];
  //print_r ($x);
  $video=$x['video'][0]['video_resource']['url'];
  $audio=$x['audio_resource'][0]['url'];
}

?>

