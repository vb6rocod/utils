<?php
/* resolve cloudflare
* adapted from https://github.com/KyranRana/cloudflare-bypass
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
* get cf_clearance cookie
* usage $html=cf_pass($url,$cookie);
*/

function cf_pass ($url,$cookie) {
 $ua = $_SERVER['HTTP_USER_AGENT'];
 $DEFAULT_CIPHERS =array(
            "ECDHE+AESGCM",
            "ECDHE+CHACHA20",
            "DHE+AESGCM",
            "DHE+CHACHA20",
            "ECDH+AESGCM",
            "DH+AESGCM",
            "ECDH+AES",
            "DH+AES",
            "RSA+AESGCM",
            "RSA+AES",
            "!aNULL",
            "!eNULL",
            "!MD5",
            "!DSS",
            "!ECDHE+SHA",
            "!AES128-SHA",
            "!DHE"
        );
 if (defined('CURL_SSLVERSION_TLSv1_3'))
  $ssl_version=7;
 else
  $ssl_version=0;
 $ch = curl_init($url);
 curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
 curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($ch, CURLOPT_SSLVERSION,$ssl_version);
 curl_setopt($ch, CURLINFO_HEADER_OUT, true);
 curl_setopt($ch, CURLOPT_HTTPHEADER,
    array(
        "Upgrade-Insecure-Requests: 1",
        "User-Agent: ".$ua."",
        "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3",
        "Accept-Language: en-US,en;q=0.9"
    ));
 $page = curl_exec($ch);
 $info = curl_getinfo($ch);
 if ($info['http_code'] === 403 && strpos($page, "captcha")) {
  curl_close($ch);
 } elseif ($info['http_code'] === 503) {
  $scheme = parse_url($info['url'], PHP_URL_SCHEME);
  $host   = parse_url($info['url'], PHP_URL_HOST);
  if ($scheme === "https") {
   curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, implode(":", $DEFAULT_CIPHERS));
   if (strpos($info['request_header'], 'HTTP/2') !== false) {
    $headers   = array_filter(array_slice(preg_split("/\r\n|\n/", $info['request_header']), 1));
    $headers[] = "sec-fetch-mode: navigate";
    $headers[] = "sec-fetch-site: none";
    $headers[] = "sec-fetch-user: ?1";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
   }
  $page = curl_exec($ch);
  $info = curl_getinfo($ch);
  }
  $post= getClearanceLink($page,$url);
  $t1=explode('action="',$page);
  $t2=explode('"',$t1[1]);
  $requestLink="https://".$host.$t2[0];
  curl_setopt($ch, CURLOPT_URL, $requestLink);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
  $page    = curl_exec($ch);
  curl_close($ch);
 } else {
  curl_close($ch);
 }
 return $page;
}
function rr($js_code) {
                $js_code = str_replace(array(
                    ")+(",
                    "![]",
                    "!+[]",
                    "[]"
                ), array(
                    ").(",
                    "(!1)",
                    "(!0)",
                    "(0)"
                ), $js_code);
return $js_code;
}

function getClearanceLink($content, $url) {
  sleep (5);
  $params = array();
  if (preg_match_all('/name="(\w+)" value="(.*?)"/', $content, $matches))
   $params=array_combine($matches[1], $matches[2]);
  $uri = parse_url($url);
  $host=$uri["host"];
  $result="";
  if (preg_match("/id\=\"cf\-dn/",$content)) {
  $t1=explode('id="cf-dn',$content);
  $t2=explode(">",$t1[1]);
  $t3=explode("<",$t2[1]);
  eval("\$cf=".rr($t3[0]).";");
  preg_match("/f\,\s?([a-zA-Z0-9]+)\=\{\"([a-zA-Z0-9]+)\"\:\s?([\/!\[\]+()]+|[-*+\/]?=[\/!\[\]+()]+)/",$content,$m);
  eval("\$result=".rr($m[3]).";");
  $pat="/".$m[1]."\.".$m[2]."(.*)+\;/";
  preg_match($pat,$content,$p);
  $t=explode(";",$p[0]);
  for ($k=0;$k<count($t);$k++) {
   if (substr($t[$k], 0, strlen($m[1])) == $m[1]) {
    if (strpos($t[$k],"function(p){var p") !== false) {
     $a1=explode ("function(p){var p",$t[$k]);
     $t[$k]=$a1[0].$cf;
     $line = str_replace($m[1].".".$m[2],"\$result ",rr($t[$k])).";";
     eval($line);
    } else if (strpos($t[$k],"(function(p){return") !== false) {
     $a1=explode("(function(p){return",$t[$k]);
     $a2=explode('("+p+")")}',$a1[1]);
     $line = "\$index=".rr(substr($a2[1], 0, -2)).";";
     eval ($line);
     $line=str_replace($m[1].".".$m[2],"\$result ",rr($a1[0])." ".ord($host[$index]).");");
     eval ($line);
    } else {
     $line = str_replace($m[1].".".$m[2],"\$result ",rr($t[$k])).";";
     eval($line);
    }
   }
  }
  $params['jschl_answer'] = round($result, 10);
  } else {
    $cf_script_start_pos    = strpos($content, 's,t,o,p,b,r,e,a,k,i,n,g,f,');
    $cf_script_end_pos      = strpos($content, '</script>', $cf_script_start_pos);
    $cf_script              = substr($content, $cf_script_start_pos, $cf_script_end_pos-$cf_script_start_pos);
    preg_match_all('/:[\/!\[\]+()]+|[-*+\/]?=[\/!\[\]+()]+/', $cf_script, $matches);
    $php_code = "";
    foreach ($matches[0] as $js_code) {
      // [] causes "invalid operator" errors; convert to integer equivalents
      $js_code = str_replace(array(
                    ")+(",
                    "![]",
                    "!+[]",
                    "[]"
                ), array(
                    ").(",
                    "(!1)",
                    "(!0)",
                    "(0)"
                ), $js_code);
      $php_code .= '$params[\'jschl_answer\']' . ($js_code[0] == ':' ? '=' . substr($js_code, 1) : $js_code) . ';';
      eval($php_code);
      $params['jschl_answer'] = round($params['jschl_answer'], 10);
      $uri = parse_url($url);
      $params['jschl_answer'] += strlen($uri['host'])  ;
    }
  }
  return http_build_query($params);
}
?>
