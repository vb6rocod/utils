<?php
/* resolve rec***** only less secure
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


function rec($site_key,$co,$sa,$loc) {
  $ua = $_SERVER['HTTP_USER_AGENT'];
  $head = array(
   'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
   'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2'
  );
  $l="https://www.google.com/recaptcha/api.js";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_REFERER, $loc);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
  curl_setopt($ch, CURLOPT_HEADER, 1);
  $h = curl_exec($ch);
  curl_close($ch);
  $t1=explode("releases/",$h);
  $t2=explode("/",$t1[1]);
  $v=$t2[0];
  $cb="123456789";
  $l2="https://www.google.com/recaptcha/api2/anchor?ar=1&k=".$site_key."&co=".$co."&hl=ro&v=".$v."&size=invisible&cb=".$cb;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l2);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_REFERER, $loc);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
  curl_setopt($ch, CURLOPT_HEADER, 1);
  $h = curl_exec($ch);
  curl_close($ch);
  $h=str_replace('\x22','"',$h);
  $t1=explode('recaptcha-token" value="',$h);
  $t2=explode('"',$t1[1]);
  $c=$t2[0];
  $l6="https://www.google.com/recaptcha/api2/reload?k=".$site_key;
  $p=array('v' => $v,
  'reason' => 'q',
  'k' => $site_key,
  'c' => $c,
  'sa' => $sa,
  'co' => $co);
  $post=http_build_query($p);
  $head=array(
  'Accept: */*',
  'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
  'Accept-Encoding: deflate',
  'Content-Type: application/x-www-form-urlencoded;charset=utf-8',
  'Content-Length: '.strlen($post).'',
  'Connection: keep-alive');
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l6);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_REFERER, $l2);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
  curl_setopt ($ch, CURLOPT_POST, 1);
  curl_setopt ($ch, CURLOPT_POSTFIELDS, $post);
  $h = curl_exec($ch);
  curl_close($ch);
  $t1=explode('rresp","',$h);
  $t2=explode('"',$t1[1]);
  $r=$t2[0];
  return $r;
}
?>


