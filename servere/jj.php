<?php
function indexOf($hack,$pos) {
    $ret= strpos($hack,$pos);
    return ($ret === FALSE) ? -1 : $ret;
}
function substring($str, $from = 0, $to = FALSE)
{
    if ($to !== FALSE) {
        if ($from == $to || ($from <= 0 && $to < 0)) {
            return '';
        }

        if ($from > $to) {
            $from_copy = $from;
            $from = $to;
            $to = $from_copy;
        }
    }

    if ($from < 0) {
        $from = 0;
    }

    $substring = $to === FALSE ? substr($str, $from) : substr($str, $from, $to - $from);
    return ($substring === FALSE) ? '' : $substring;
}


function jjdecode($t) {
            $startpos=0;
            $endpos=0;
            $gv="";;
            $gvl="";
            $out="";
            if (indexOf($t,"\"\'\\\"+\'+\",") == 0) //palindrome check
            {
                //locate jjcode
                $startpos	= indexOf($t,'$$+"\\""+') + 8;
                $endpos		= indexOf($t,'"\\"")())()');

                //get gv
                $gv=substring($t,8,1);
                $gvl	= strlen($gv);
            }
            else
            {
                //get gv
                $gv	= substr($t,0, indexOf($t,"="));
                //echo $gv."<BR>";
                //$gv="j";
                $gvl	= strlen($gv);

                //locate jjcode
                $startpos	= indexOf($t,'"\\""+') + 5;
                $endpos		= indexOf($t,'"\\"")())()');
            }
            if ($startpos == $endpos)
            {
                $out="";
            }
            //start decoding
            $data = substring($t,$startpos, $endpos);

            $b = array( "___+", "__\$+", "_\$_+", "_\$\$+", "\$__+", "\$_\$+", "\$\$_+", "\$\$\$+", "\$___+", "\$__\$+", "\$_\$_+", "\$_\$\$+", "\$\$__+", "\$\$_\$+", "\$\$\$_+", "\$\$\$\$+" );
            $str_l = "(![]+\"\")[".$gv."._\$_]+";
            $str_o = $gv."._\$+";
            $str_t = $gv.".__+";
            $str_u = $gv."._+";

            //0123456789abcdef
            $str_hex = $gv.".";

            //s
            $str_s = '"';
            $gvsig = $gv.".";

            $str_quote = '\\\\\\"';
            $str_slash = '\\\\\\\\';

            $str_lower = "\\\\\"+";
            $str_upper = "\\\\\"+".$gv."._+";

            $str_end	= '"+'; //end of s loop

            while($data != "")
            {
                //l o t u
                //echo $data;
                if (0 == indexOf($data,$str_l))
                {
                    //echo "find lotu str_l";
                    $data = substr($data,strlen($str_l));
                    $out .="l";
                    continue;
                }
                else if (0 == indexOf($data,$str_o))
                {
                    $data = substr($data,strlen($str_o));
                    $out .="o";
                    continue;
                }
                else if (0 == indexOf($data,$str_t))
                {
                    $data = substr($data,strlen($str_t));
                    $out .="t";
                    continue;
                }
                else if (0 == indexOf($data,$str_u))
                {
                    $data = substr($data,strlen($str_u));
                    $out .="u";
                    continue;
                }

                //0123456789abcdef
                if (0 == indexOf($data,$str_hex))
                {
                    $data = substr($data,strlen($str_hex));

                    //check every element of hex decode string for a match
                    $i = 0;
                    for ($i = 0; $i < count($b); $i++)
                    {
                        if (0 == indexOf($data,$b[$i]))
                        {
                            $data = substr($data, strlen($b[$i]) );
                            $out .= dechex($i);    //out(i.toString(16));
                            break;
                        }
                    }
                    continue;
                }
                //start of s block
                if (0 == indexOf($data,$str_s))
                {
                    $data = substr($data, strlen($str_s));

                    //check if "R
                    if (0 == indexOf($data,$str_upper)) // r4 n >= 128
                    {
                        $data = substr($data,strlen($str_upper)); //skip sig

                        $ch_str = "";
                        for ($j = 0; $j < 2; $j++) //shouldn't be more than 2 hex chars
                        {
                            //gv + "."+b[ c ]
                            if (0 == indexOf($data,$gvsig))
                            {
                                $data = substr($data,strlen($gvsig)); //skip gvsig

                                for ($k = 0; $k < count($b); $k++)	//for every entry in b
                                {
                                    if (0 == indexOf($data,$b[$k]))
                                    {
                                        $data = substr($data,strlen($b[$k]));
                                        $ch_str .= dechex($k)."";
                                        break;
                                    }
                                }
                            }
                            else
                            {
                                break; //done
                            }
                        }

                        $out .=chr(intval($ch_str,16));
                        continue;
                    }
                    else if (0 == indexOf($data,$str_lower)) //r3 check if "R // n < 128
                    {
                        $data = substr($data, strlen($str_lower)); //skip sig

                        $ch_str = "";
                        $ch_lotux = "";
                        $temp = "";
                        $b_checkR1 = 0;
                        for ($j = 0; $j < 3; $j++) //shouldn't be more than 3 octal chars
                        {

                            if ($j > 1) //lotu check
                            {
                                if (0 == indexOf($data,$str_l))
                                {
                                    $data = substr($data, strlen($str_l));
                                    $ch_lotux = "l";
                                    break;
                                }
                                else if (0 == indexOf($data,$str_o))
                                {
                                    $data = substr($data, strlen($str_o));
                                    $ch_lotux = "o";
                                    break;
                                }
                                else if (0 == indexOf($data, $str_t))
                                {
                                    $data = substr($data,strlen($str_t));
                                    $ch_lotux = "t";
                                    break;
                                }
                                else if (0 == indexOf($data,$str_u))
                                {
                                    $data = substr($data,strlen($str_u));
                                    $ch_lotux = "u";
                                    break;
                                }
                            }

                            //gv + "."+b[ c ]
                            if (0 == indexOf($data,$gvsig))
                            {
                                $temp = substr($data,strlen($gvsig));
                                for ($k = 0; $k < 8; $k++)	//for every entry in b octal
                                {
                                    if (0 == indexOf($temp,$b[$k]))
                                    {
                                        if (intval($ch_str.$k."",8) > 128)
                                        {
                                            $b_checkR1 = 1;
                                            break;
                                        }

                                        $ch_str .= $k."";
                                        $data = substr($data, strlen($gvsig)); //skip gvsig
                                        $data = substr($data,strlen($b[$k]));
                                        break;
                                    }
                                }

                                if (1 == $b_checkR1)
                                {
                                    if (0 == indexOf($data,$str_hex)) //0123456789abcdef
                                    {
                                        $data = substr($data,strlen($str_hex));

                                        //check every element of hex decode string for a match
                                        $i = 0;
                                        for ($i = 0; $i < count($b); $i++)
                                        {
                                            if (0 == indexOf($data,$b[$i]))
                                            {
                                                $data = substr($data, strlen(($b[$i])));
                                                $ch_lotux = dechex($i);
                                                break;
                                            }
                                        }

                                        break;
                                    }
                                }
                            }
                            else
                            {
                                break; //done
                            }
                        }

                        $out .=chr(intval($ch_str,8));
                        continue; //step out of the while loop
                    }
                    else //"S ----> "SR or "S+
                    {

                        // if there is, loop s until R 0r +
                        // if there is no matching s block, throw error

                        $match = 0;
                        $n= 0;

                        //searching for mathcing pure s block
                        while(true)
                        {
                            $n = ord($data[0]);
                            if (0 == indexOf($data, $str_quote))
                            {
                                $data = substr($data, strlen($str_quote));
                                $out .='"';
                                $match += 1;
                                continue;
                            }
                            else if (0 == indexOf($data,$str_slash))
                            {
                                $data = substr($data, strlen($str_slash));
                                $out .='\\';
                                $match += 1;
                                continue;
                            }
                            else if (0 == indexOf($data,$str_end))	//reached end off S block ? +
                            {
                                if ($match == 0)
                                {
                                    //echo "+ no match S block: ";
                                    return;
                                }
                                $data = substr($data, strlen($str_end));

                                break; //step out of the while loop
                            }
                            else if (0 == indexOf($data, $str_upper)) //r4 reached end off S block ? - check if "R n >= 128
                            {
                                if ($match == 0)
                                {
                                    //echo "no match S block n>128: ";
                                    return;
                                }

                                $data = substr($data,strlen($str_upper)); //skip sig

                                $ch_str = "";
                                $ch_lotux = "";
                                for ($j = 0; $j < 10; $j++) //shouldn't be more than 10 hex chars
                                {

                                    if ($j > 1) //lotu check
                                    {
                                        if (0 == indexOf($data, $str_l))
                                        {
                                            $data = substr($data, strlen($str_l));
                                            $ch_lotux = "l";
                                            break;
                                        }
                                        else if (0 == indexOf($data, $str_o))
                                        {
                                            $data = substr($data,strlen($str_o));
                                            $ch_lotux = "o";
                                            break;
                                        }
                                        else if (0 == indexOf($data, $str_t))
                                        {
                                            $data = substr($data, strlen($str_t));
                                            $ch_lotux = "t";
                                            break;
                                        }
                                        else if (0 == indexOf($data, $str_u))
                                        {
                                            $data = substr($data, strlen($str_u));
                                            $ch_lotux = "u";
                                            break;
                                        }
                                    }

                                    //gv + "."+b[ c ]
                                    if (0 == indexOf($data,$gvsig))
                                    {
                                        $data = substr($data, strlen($gvsig)); //skip gvsig

                                        for ($k = 0; $k < count($b); $k++)	//for every entry in b
                                        {
                                            if (0 == indexOf($data,$b[$k]))
                                            {
                                                $data = substr($data, strlen($b[$k]));
                                                $ch_str .= dechex($k)."";
                                                break;
                                            }
                                        }
                                    }
                                    else
                                    {
                                        break; //done
                                    }
                                }

                                $out .=chr(intval($ch_str,16));
                                break; //step out of the while loop
                            }
                            else if (0 == indexOf($data, $str_lower)) //r3 check if "R // n < 128
                            {
                                if ($match == 0)
                                {
                                    //echo "no match S block n<128: ";
                                    return;
                                }

                                $data = substr($data, strlen($str_lower)); //skip sig

                                $ch_str = "";
                                $ch_lotux = "";
                                $temp = "";
                                $b_checkR1 = 0;
                                for ($j = 0; $j < 3; $j++) //shouldn't be more than 3 octal chars
                                {

                                    if ($j > 1) //lotu check
                                    {
                                        if (0 == indexOf($data, $str_l))
                                        {
                                            $data = substr($data,strlen($str_l));
                                            $ch_lotux = "l";
                                            break;
                                        }
                                        else if (0 == indexOf($data,$str_o))
                                        {
                                            $data = substr($data,strlen($str_o));
                                            $ch_lotux = "o";
                                            break;
                                        }
                                        else if (0 == indexOf($data,$str_t))
                                        {
                                            $data = substr($data, strlen($str_t));
                                            $ch_lotux = "t";
                                            break;
                                        }
                                        else if (0 == indexOf($data, $str_u))
                                        {
                                            $data = substr($data, strlen($str_u));
                                            $ch_lotux = "u";
                                            break;
                                        }
                                    }

                                    //gv + "."+b[ c ]
                                    if (0 == indexOf($data, $gvsig))
                                    {
                                        $temp = substr($data, strlen($gvsig));
                                        for ($k = 0; $k < 8; $k++)	//for every entry in b octal
                                        {
                                            if (0 == indexOf($temp, $b[$k]))
                                            {
                                                if (intval($ch_str.$k."",8) > 128)
                                                {
                                                    $b_checkR1 = 1;
                                                    break;
                                                }

                                                $ch_str .= $k."";
                                                $data = substr($data, strlen($gvsig)); //skip gvsig
                                                $data = substr($data, strlen($b[$k]));
                                                break;
                                            }
                                        }

                                        if (1 == $b_checkR1)
                                        {
                                            if (0 == indexOf($data, $str_hex)) //0123456789abcdef
                                            {
                                                $data = substr($data, strlen($str_hex));

                                                //check every element of hex decode string for a match
                                                $i = 0;
                                                for ($i = 0; $i < count($b); $i++)
                                                {
                                                    if (0 == indexOf($data, $b[$i]))
                                                    {
                                                        $data = substr($data, strlen($b[$i]));
                                                        $ch_lotux = intval($i,16) + 16;  //// original e fara  + 16
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    else
                                    {
                                        break; //done
                                    }
                                }

                                $out .=chr(intval($ch_str,8) + $ch_lotux);
                                break; //step out of the while loop
                            }
                            else if( (0x21 <= $n && $n <= 0x2f) || (0x3A <= $n && $n <= 0x40) || ( 0x5b <= $n && $n <= 0x60 ) || ( 0x7b <= $n && $n <= 0x7f ) )
                            {
                                $out .=$data[0];
                                $data = substr($data, 1 );
                                $match += 1;
                            }

                        }
                        continue;
                    }
                }

                echo "no match : ";
                break;
            }
return $out;
}
?>
