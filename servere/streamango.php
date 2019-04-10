<?php
 /* resolve streamango
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

$filelink="http://streamango.com/embed/ootscqdepantcrnl/?autoplay=yes";
if (strpos($filelink,"streamango.") !== false || strpos($filelink,"fruithosts.") !== false) {
 $pattern = '/(?:\/\/|\.)(streamango\.(?:io|com)|(fruithosts\.net))\/(?:embed|f)\/([0-9a-zA-Z-_]+)/';
 preg_match($pattern,$filelink,$m);
 $id=$m[3];
 $filelink="https://streamango.com/embed/".$id;
function indexOf($hack,$pos) {
    $ret= strpos($hack,$pos);
    return ($ret === FALSE) ? -1 : $ret;
}
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $filelink);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:22.0) Gecko/20100101 Firefox/22.0');
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h2 = curl_exec($ch);
  curl_close($ch);

  if (preg_match('/([http|https][\.\d\w\-\.\/\\\:\?\&\#\%\_\,]*(\.(srt|vtt)))/', $h2, $m))
    $srt=$m[1];
  $t1=explode('video/mp4"',$h2);
  $t2=explode("src:d('",$t1[1]);
  $t3=explode("'",$t2[1]);
  $a16=$t3[0];
  $t4=explode(",",$t3[1]);
  $t5=explode(")",$t4[1]);
  $a17=(int) $t5[0];
  $a86=0x0;
  $a84=explode("|","4|6|5|0|7|3|2|1|8");

 for ($zz=0;$zz<count($a84);$zz++)
		{
		switch($a84[$a86++])
			{
			case'0':
            $a92=0;
            $a89=0;
            $a91=0;
            $a92=0;
			continue;
			case'1':
             while ( $a94 < strlen($a16))
				{
				$a96 = explode("|","6|2|9|8|5|4|7|10|0|3|1");
				$a98=0;
                for ($yy=0;$yy<count($a96);$yy++)
					{
					switch($a96[$a98++])
						{
						case'0':
                         $a101=$a101.chr($a104);
						continue;
						case'1':
                         if($a92!=0x40)
							{
							$a101=$a101.chr($a110);
						}
						continue;
						case'2':
                         $a90=indexOf($k,$a16[$a94++]);
						continue;
						case'3':
                         if ($a91!=0x40)
							{
							$a101=$a101.chr($a119);
						}
						continue;
						case'4':
                          $a119 = (($a90&0xf) << 0x4)|($a91>>0x02);
						continue;
						case'5':
                          $a104 = ($a89<<0x2)|($a90>>0x4);
						continue;
						case'6':
                          $a89=indexOf($k,$a16[$a94++]);
						continue;
						case'7':
                          $a110=(($a91&0x3)<<0x6)|$a92;
						continue;
						case'8':
                          $a92=indexof($k,$a16[$a94++]);
						continue;
						case'9':
                          $a91=indexof($k,$a16[$a94++]);
						continue;
						case'10':
                          $a104 = $a104^$a17;
						continue;
					}
				}
			}
			continue;
			case'2':
              $a16=preg_replace("/[^A-Za-z0-9\+\/\=]/",'',$a16);
			  continue;
			case'3':
              $k="=/+9876543210zyxwvutsrqponmlkjihgfedcbaZYXWVUTSRQPONMLKJIHGFEDCBA";
			continue;
			case'4':
              $k="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
			  continue;
			case'5':
              $a104=0;
              $a119=0;
              $a110=0;
			  continue;
			case'6':
              $a101='';
			  continue;
			case'7':
              $a94=0x0;
			  continue;
			case'8':
              $dec = $a101;
			  continue;
		}
	}
	$link=$dec;
	if ($link) {
	if (strpos($link,"http") === false) $link="https:".$link;
	} else
      $link="":
}
echo $link;
?>
