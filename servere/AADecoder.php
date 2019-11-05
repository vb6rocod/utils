<?php
/**
 * Class AADecoder
 * @author Andrey Izman <izmanw@gmail.com>
 * @link https://github.com/mervick/php-aaencoder
 * @license MIT
 */

/**
 * Class AADecoder
 */
class AADecoder
{
    const BEGIN_CODE = "ﾟωﾟﾉ=/｀ｍ´）ﾉ~┻━┻/['_'];o=(ﾟｰﾟ)=_=3;c=(ﾟΘﾟ)=(ﾟｰﾟ)-(ﾟｰﾟ);(ﾟДﾟ)=(ﾟΘﾟ)=(o^_^o)/(o^_^o);(ﾟДﾟ)={ﾟΘﾟ:'_',ﾟωﾟﾉ:((ﾟωﾟﾉ==3)+'_')[ﾟΘﾟ],ﾟｰﾟﾉ:(ﾟωﾟﾉ+'_')[o^_^o-(ﾟΘﾟ)],ﾟДﾟﾉ:((ﾟｰﾟ==3)+'_')[ﾟｰﾟ]};(ﾟДﾟ)[ﾟΘﾟ]=((ﾟωﾟﾉ==3)+'_')[c^_^o];(ﾟДﾟ)['c']=((ﾟДﾟ)+'_')[(ﾟｰﾟ)+(ﾟｰﾟ)-(ﾟΘﾟ)];(ﾟДﾟ)['o']=((ﾟДﾟ)+'_')[ﾟΘﾟ];(ﾟoﾟ)=(ﾟДﾟ)['c']+(ﾟДﾟ)['o']+(ﾟωﾟﾉ+'_')[ﾟΘﾟ]+((ﾟωﾟﾉ==3)+'_')[ﾟｰﾟ]+((ﾟДﾟ)+'_')[(ﾟｰﾟ)+(ﾟｰﾟ)]+((ﾟｰﾟ==3)+'_')[ﾟΘﾟ]+((ﾟｰﾟ==3)+'_')[(ﾟｰﾟ)-(ﾟΘﾟ)]+(ﾟДﾟ)['c']+((ﾟДﾟ)+'_')[(ﾟｰﾟ)+(ﾟｰﾟ)]+(ﾟДﾟ)['o']+((ﾟｰﾟ==3)+'_')[ﾟΘﾟ];(ﾟДﾟ)['_']=(o^_^o)[ﾟoﾟ][ﾟoﾟ];(ﾟεﾟ)=((ﾟｰﾟ==3)+'_')[ﾟΘﾟ]+(ﾟДﾟ).ﾟДﾟﾉ+((ﾟДﾟ)+'_')[(ﾟｰﾟ)+(ﾟｰﾟ)]+((ﾟｰﾟ==3)+'_')[o^_^o-ﾟΘﾟ]+((ﾟｰﾟ==3)+'_')[ﾟΘﾟ]+(ﾟωﾟﾉ+'_')[ﾟΘﾟ];(ﾟｰﾟ)+=(ﾟΘﾟ);(ﾟДﾟ)[ﾟεﾟ]='\\\\';(ﾟДﾟ).ﾟΘﾟﾉ=(ﾟДﾟ+ﾟｰﾟ)[o^_^o-(ﾟΘﾟ)];(oﾟｰﾟo)=(ﾟωﾟﾉ+'_')[c^_^o];(ﾟДﾟ)[ﾟoﾟ]='\\\"';(ﾟДﾟ)['_']((ﾟДﾟ)['_'](ﾟεﾟ+(ﾟДﾟ)[ﾟoﾟ]+";

    const END_CODE = "(ﾟДﾟ)[ﾟoﾟ])(ﾟΘﾟ))('_');";


    /**
     * Decode encoded-as-aaencode JavaScript code.
     * @param string $js
     * @return string
     */
    public static function decode($js)
    {
        if (self::hasAAEncoded($js, $start, $next, $encoded)) {
            $decoded = self::deobfuscate($encoded);
            if (substr(rtrim($decoded), -1) !== ';') {
                $decoded .= ';';
            }
            return mb_substr($js, 0, $start, 'UTF-8') . $decoded . self::decode(mb_substr($js, $next, null, 'UTF-8'));
        }
        return $js;
    }

    /**
     * @param string $js
     * @return string
     */
    protected static function deobfuscate($js)
    {
        $bytes = array(
            9 => '((ﾟｰﾟ)+(ﾟｰﾟ)+(ﾟΘﾟ))',
            6 => '((o^_^o)+(o^_^o))',
            2 => '((o^_^o)-(ﾟΘﾟ))',
            7 => '((ﾟｰﾟ)+(o^_^o))',
            5 => '((ﾟｰﾟ)+(ﾟΘﾟ))',
            8 => '((ﾟｰﾟ)+(ﾟｰﾟ))',
            10 => '(ﾟДﾟ).ﾟωﾟﾉ',
            11 => '(ﾟДﾟ).ﾟΘﾟﾉ',
            12 => '(ﾟДﾟ)[\'c\']',
            13 => '(ﾟДﾟ).ﾟｰﾟﾉ',
            14 => '(ﾟДﾟ).ﾟДﾟﾉ',
            15 => '(ﾟДﾟ)[ﾟΘﾟ]',
            3 => '(o^_^o)',
            0 => '(c^_^o)',
            4 => '(ﾟｰﾟ)',
            1 => '(ﾟΘﾟ)',
        );
        $native = array(
            '-~' => '1+',
            '!' => '1',
            '[]' => '0',
        );
        $native = array(
            array_keys($native),
            array_values($native),
        );
        $chars = array();
        $hex = '(oﾟｰﾟo)+';
        $hexLen = mb_strlen($hex, 'UTF-8');
        $calc = function($expr) {
            return eval("return $expr;");
        };
        $convert = function ($block, $func) use ($bytes, $calc) {
            while (preg_match('/\([0-9\-\+\*\/]+\)/', $block)) {
                $block = preg_replace_callback('/\([0-9\-\+\*\/]+\)/', function($matches) use ($calc) {
                    return $calc($matches[0]);
                }, $block);
            }
            $split = array();
            foreach (explode('+', trim($block, '+')) as $num) {
                if ($num === '') continue;
                $split[] = $func(intval(trim($num)));
            }
            return implode('', $split);
        };
        foreach ($bytes as $byte => $search) {
            $js = implode($byte, mb_split(preg_quote($search), $js));
        }
        foreach (mb_split(preg_quote('(ﾟДﾟ)[ﾟεﾟ]+'), $js) as $block) {
            $block = trim(trim(str_replace($native[0], $native[1], $block), '+'));
            if ($block === '') continue;
            if (mb_substr($block, 0, $hexLen, 'UTF-8') === $hex) {
                $code = hexdec($convert(mb_substr($block, $hexLen, null, 'UTF-8'), 'dechex'));
            }
            else {
                $code = octdec($convert($block, 'decoct'));
            }
            $chars[] = mb_convert_encoding('&#' . intval($code) . ';', 'UTF-8', 'HTML-ENTITIES');
        }
        return implode('', $chars);
    }

    /**
     * Detect aaencoded JavaScript code.
     * @param string $js
     * @param null|int $start
     * @param null|int $next
     * @param null|string $encoded
     * @return bool
     */
    public static function hasAAEncoded($js, &$start=null, &$next=null, &$encoded=null)
    {
        $find = function($haystack, $needle, $offset=0) {
            $matches = array();
            for ($i = 0; $i < 6 && $offset !== false; $i ++) {
                if (($offset = mb_strpos($haystack, $needle, $offset, 'UTF-8')) !== false) {
                    $matches[$i] = $offset;
                    $offset ++;
                }
            }
            return count($matches) >= 6 ? array($matches[4], $matches[5]) : false;
        };
        $start = -1;
        while (($start = mb_strpos($js, 'ﾟωﾟﾉ', $start + 1, 'UTF-8')) !== false) {
            $clear = preg_replace('/\/\*.+?\*\//', '', preg_replace('/[\x03-\x20]/', '', $code = mb_substr($js, $start, null, 'UTF-8')));
            $len = mb_strlen(self::BEGIN_CODE, 'UTF-8');
            if (mb_substr($clear, 0, $len, 'UTF-8') === self::BEGIN_CODE &&
                mb_strpos($clear, self::END_CODE, $len, 'UTF-8') &&
                ($matches = $find($js, 'ﾟoﾟ', $start))
            ) {
                list($beginAt, $endAt) = $matches;
                $beginAt = mb_strpos($js, '+', $beginAt, 'UTF-8');
                $endAt = mb_strrpos($js, '(', - mb_strlen($js, 'UTF-8') + $endAt, 'UTF-8');
                $next = mb_strpos($js, ';', $endAt + 1, 'UTF-8') + 1;
                $encoded = preg_replace('/[\x03-\x20]/', '', mb_substr($js, $beginAt, $endAt - $beginAt, 'UTF-8'));
                return true;
            }
        }
        return false;
    }
}
