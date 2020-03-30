<?php
$id=$_GET['id'];
$token=$_GET['token'];
function generateResponse($request) {
$ua = $_SERVER['HTTP_USER_AGENT'];
$head = array(
'Content-Type: text/xml',
);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://api.opensubtitles.org/xml-rpc");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, $ua);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
curl_setopt ($ch, CURLOPT_POST, 1);
curl_setopt ($ch, CURLOPT_POSTFIELDS, $request);
$response = curl_exec($ch);
curl_close($ch);
return $response;
}
$request="<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
<methodCall>
<methodName>DownloadSubtitles</methodName>
<params>
 <param>
  <value>
   <string>".$token."</string>
  </value>
 </param>
 <param>
  <value>
   <array>
    <data>
     <value>
      <string>".$id."</string>
     </value>
    </data>
   </array>
  </value>
 </param>
</params>
</methodCall>";
$response = generateResponse($request);
$t1=explode("data",$response);
$t2=explode("<string>",$t1[3]);
$t3=explode("</string>",$t2[1]);
$data=$t3[0];
$h = gzdecode(base64_decode($data));
// convert ANSI to UTF-8
 if (function_exists("mb_convert_encoding"))
    if (mb_detect_encoding($h, 'UTF-8', true)== false) $h=mb_convert_encoding($h, 'UTF-8','ISO-8859-2');
echo $h;
?>
