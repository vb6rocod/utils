 <?php
/* resolve Video URL shortener service, event.2target.net
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
$filelink = "https://event.2target.net/jc1M";

if (strpos($filelink, "2target.net") !== false) {
    $ua     = $_SERVER['HTTP_USER_AGENT'];
    $cookie = dirname($_SERVER['SCRIPT_FILENAME']) . "/event.txt";
    if (file_exists($cookie))
        unlink($cookie);
    $head = array(
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept-Language: en-US,en;q=0.5',
        'Accept-Encoding: deflate',
        'Connection: keep-alive',
        'Upgrade-Insecure-Requests: 1'
    );
    $ch   = curl_init();
    curl_setopt($ch, CURLOPT_URL, $filelink);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    $h1 = curl_exec($ch);
    $t1 = explode('class="timer">',$h1);
    $t2 = explode('<',$t1[1]);
    $sec = $t2[0];
    if (!$sec) $sec=2;
    $t1 = explode('<form method="post', $h1);
    $t2 = explode("</form", $t1[1]);
    $xx = '<form method="post' . $t2[0] . "</form>";

    $t1           = explode('name="_csrfToken', $h1);
    $t2           = explode('value="', $t1[1]);
    $t3           = explode('"', $t2[1]);
    $csrfToken    = $t3[0];
    $t1           = explode('name="ad_form_data', $h1);
    $t2           = explode('value="', $t1[1]);
    $t3           = explode('"', $t2[1]);
    $ad_form_data = $t3[0];

    $t1     = explode('name="_Token[fields]', $h1);
    $t2     = explode('value="', $t1[1]);
    $t3     = explode('"', $t2[1]);
    $token1 = $t3[0];

    $t1     = explode('name="_Token[unlocked]', $h1);
    $t2     = explode('value="', $t1[1]);
    $t3     = explode('"', $t2[1]);
    $token2 = $t3[0];
    $data   = array(
        '_method' => 'POST',
        '_csrfToken' => $csrfToken,
        'ad_form_data' => $ad_form_data,
        '_Token[fields]' => $token1,
        '_Token[unlocked]' => $token2
    );
    $post   = http_build_query($data);
    $l2     = "https://event.2target.net/links/go";
    $head   = array(
        'Accept: application/json, text/javascript, */*; q=0.01',
        'Accept-Language: en-US,en;q=0.5',
        'Accept-Encoding: deflate',
        'X-CSRF-Token: ' . $csrfToken . '',
        'Referer: https://event.2target.net/jc1M',
        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        'X-Requested-With: XMLHttpRequest',
        'Content-Length: ' . strlen($post) . ''
    );
    sleep($sec);
    curl_setopt($ch, CURLOPT_URL, $l2);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $x = curl_exec($ch);
    curl_close($ch);

    $r = json_decode($x, 1);
    if (isset($r['url']))
        $link = $r['url'];
    else
        $link = "";
}
echo $link;
?>
