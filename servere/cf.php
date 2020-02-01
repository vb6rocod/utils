<?php
/* resolve cloudflare challenge-form
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
* get getClearanceLink
*/
function rr($js_code)
	{
	$js_code = str_replace(array(
		")+(",
		"![]",
		"!+[]",
		"[]"
	) , array(
		").(",
		"(!1)",
		"(!0)",
		"(0)"
	) , $js_code);
	return $js_code;
	}
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
function getClearanceLink($content, $url)
	{
	sleep(5);
    preg_match_all('/name="\w+" value="(.*?)"/', $content, $matches);
	$params = array();
    list($params['r'],$params['jschl_vc'], $params['pass']) = $matches[1];
	$uri = parse_url($url);
	$host = $uri["host"];
	$result = "";
	$t1 = explode('id="cf-dn', $content);
	$t2 = explode(">", $t1[1]);
	$t3 = explode("<", $t2[1]);
    eval("\$cf=".rr($t3[0]).";");
	preg_match("/f\,\s?([a-zA-z0-9]+)\=\{\"([a-zA-Z0-9]+)\"\:\s?([\/!\[\]+()]+|[-*+\/]?=[\/!\[\]+()]+)/", $content, $m);
	eval("\$result=" . rr($m[3]) . ";");
	$pat = "/" . $m[1] . "\." . $m[2] . "(.*)+\;/";
	preg_match($pat, $content, $p);
	$t = explode(";", $p[0]);
	for ($k = 0; $k < count($t); $k++)
		{
		if (substr($t[$k], 0, strlen($m[1])) == $m[1])
			{
			if (strpos($t[$k], "function(p){var p") !== false)
				{
				$a1 = explode("function(p){var p", $t[$k]);
				$t[$k] = $a1[0] . $cf;
				$line = str_replace($m[1] . "." . $m[2], "\$result ", rr($t[$k])) . ";";
				eval($line);
				}
			  else
			if (strpos($t[$k], "(function(p){return") !== false)
				{
				$a1 = explode("(function(p){return", $t[$k]);
				$a2 = explode('("+p+")")}', $a1[1]);
				$line = "\$index=" . rr(substr($a2[1], 0, -2)) . ";";
				eval($line);
				$line = str_replace($m[1] . "." . $m[2], "\$result ", rr($a1[0]) . " " . ord($host[$index]) . ");");
				eval($line);
				}
			  else
				{
				$line = str_replace($m[1] . "." . $m[2], "\$result ", rr($t[$k])) . ";";
				eval($line);
				}
			}
		}

	$params['jschl_answer'] = round($result, 10);
	return sprintf("%s://%s/cdn-cgi/l/chk_jschl?%s", $uri['scheme'], $uri['host'], http_build_query($params));
	}
	
function getClearanceLink_old($content, $url)
	{
	/*
	* 1. Mimic waiting process
	*/
	sleep(4);
	/*
	* 2. Extract "jschl_vc" and "pass" params
	*/
	preg_match_all('/name="\w+" value="(.+?)"/', $content, $matches);
	$params = array();

	list($params['s'], $params['jschl_vc'], $params['pass']) = $matches[1];

	// Extract CF script tag portion from content.

	$cf_script_start_pos = strpos($content, 's,t,o,p,b,r,e,a,k,i,n,g,f,');
	$cf_script_end_pos = strpos($content, '</script>', $cf_script_start_pos);
	$cf_script = substr($content, $cf_script_start_pos, $cf_script_end_pos - $cf_script_start_pos);
	/*
	* 3. Extract JavaScript challenge logic
	*/
	preg_match_all('/:[\/!\[\]+()]+|[-*+\/]?=[\/!\[\]+()]+/', $cf_script, $matches);

	/*
	* 4. Convert challenge logic to PHP
	*/
	$php_code = "";
	foreach($matches[0] as $js_code)
		{

		// [] causes "invalid operator" errors; convert to integer equivalents

		$js_code = str_replace(array(
			")+(",
			"![]",
			"!+[]",
			"[]"
		) , array(
			").(",
			"(!1)",
			"(!0)",
			"(0)"
		) , $js_code);

		$php_code.= '$params[\'jschl_answer\']' . ($js_code[0] == ':' ? '=' . substr($js_code, 1) : $js_code) . ';';
		}


	/*
	* 5. Eval PHP and get solution
	*/

	eval($php_code);

	// toFixed(10).

	$params['jschl_answer'] = round($params['jschl_answer'], 10);

	// Split url into components.

	$uri = parse_url($url);

	// Add host length to get final answer.
	// echo $uri['host'];

	$params['jschl_answer']+= strlen($uri['host']);


	/*
	* 6. Generate clearance link
	*/

	// echo http_build_query($params);

	return sprintf("%s://%s/cdn-cgi/l/chk_jschl?%s", $uri['scheme'], $uri['host'], http_build_query($params));
	}
