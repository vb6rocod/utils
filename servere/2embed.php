<?php
 /* resolve 2embed
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
 * return an array
 */

$filelink="https://www.2embed.to/embed/imdb/movie?id=tt6806448";
function resolve2E($filelink) {
  $r=array();
  $t1=explode("?",$filelink);
  $host=parse_url($t1[0])['host'];
  $ua = $_SERVER['HTTP_USER_AGENT'];
  require_once ("rec.php");
  $ch = curl_init($filelink);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
  curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  $h = curl_exec($ch);
  curl_close($ch);
  $t1=explode('data-recaptcha-key="',$h);
  $t2=explode('"',$t1[1]);
  $key=$t2[0];

  preg_match_all("/data-id=\"(\d+)\"/",$h,$m);
  $head=array('Accept: */*',
  'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
  'Accept-Encoding: deflate',
  'X-Requested-With: XMLHttpRequest',
  'Connection: keep-alive',
  'Referer: https://'.$host.'/embed/imdb/tv?id=tt9737326&s=1&e=3');
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
  curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  for ($z=0;$z<count($m[0]);$z++) {
  $co="aHR0cHM6Ly93d3cuMmVtYmVkLnJ1OjQ0Mw..";
  $loc="https://".$host;
  $sa="get_link";
  $id=$m[1][$z];
  $token=rec($key,$co,$sa,$loc);
  $l="https://".$host."/ajax/embed/play?id=".$id."&_token=".$token;
  curl_setopt($ch, CURLOPT_URL, $l);
  $h = curl_exec($ch);
  $x=json_decode($h,1);
  $r[]=$x['link'];
  }
  curl_close($ch);
  return ($r);
}
print_r (resolve2E($filelink));
?>
