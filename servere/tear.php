<?php
/* see https://playtube.ws/js/tear.js */
function uRShift($a, $b)
{
    $z = hexdec(80000000);
    if($z & $a)
    {
        $a = ($a >> 1);
        $a &= (~$z);
        $a |= 0x40000000;
        $a = ($a >> ($b - 1));
    } else {
        $a = ($a >> $b);
    }
    return $a;
}

function ascii2binary($a0) {
    return bytes2blocks(ascii2bytes($a0));
}

function ascii2bytes($bb) {
    $x = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_";
    $a2b = array();
    for($k = 0; $k < strlen($x); $k++) {
        $a2b[$x[$k]] = $k;
    }
    $a2 = 0;
    $a6 = -(1);
    $a7 = strlen($bb);
    $a9 = 0;
    $a8 = Array();
    while(true) {
        while(true) {
            $a6++;
            if($a6 >= $a7) return $a8;
            if(isset($a2b[$bb[$a6]])) break;
        }
        $a8[$a9] = $a2b[$bb[$a6]] << 2;
        while(true) {
            $a6++;
            if($a6 >= $a7) return $a8;
            if(isset($a2b[$bb[$a6]])) break;
        }
        $a3 = $a2b[$bb[$a6]];
        $a8[$a9] |= uRShift($a3, 4);
        $a9++;
        $a3 = (15 & $a3);
        if(($a3 == 0) && ($a6 == ($a7 - 1))) return $a8;
        $a8[$a9] = ($a3 << 4);
        while(true) {
            $a6++;
            if($a6 >= $a7) return $a8;
            if(isset($a2b[$bb[$a6]])) break;
        }
        $a3 = $a2b[$bb[$a6]];
        $a8[$a9] |= uRShift($a3, 2);
        $a9++;
        $a3 = (3 & $a3);
        if(($a3 == 0) && ($a6 == ($a7 - 1))) return $a8;
        $a8[$a9] = ($a3 << 6);
        while(true) {
            $a6++;
            if($a6 >= $a7) return $a8;
            if(isset($a2b[$bb[$a6]])) break;
        }
        $a8[$a9] |= $a2b[$bb[$a6]];
        $a9++;
    }
    return $a8;
}

function bytes2str($a10) {
    while(true) {
        $a13 = 0;
        $a14 = count($a10);
        $a15 = '';
        while(true) {
            if($a13 >= $a14) break;
            $a15  .= chr(255 & $a10[$a13]);
            $a13++;
        }
        break;
    }
    return $a15;
}

function str2bytes($a16) {
    while(true) {
        $a20 = 0;
        $a19 = strlen($a16);
        $a21 = Array();
        while(true) {
            if($a20 >= $a19) break;
            $a21[$a20] = ord($a16[$a20]);
            $a20++;
        }
        break;
    }
    return $a21;
}

function bytes2blocks($a22) {
    while(true) {
        $a27 = Array();
        $a28 = 0;
        $a26 = 0;
        $a25 = count($a22);
        while(true) {
            $a27[$a28] = (255 & $a22[$a26]) << 24;
            $a26++;
            if($a26 >= $a25) break;
            $a27[$a28] |= (255 & $a22[$a26]) << 16;
            $a26++;
            if($a26 >= $a25) break;
            $a27[$a28] |= (255 & $a22[$a26]) << 8;
            $a26++;
            if($a26 >= $a25) break;
            $a27[$a28] |= (255 & $a22[$a26]);
            $a26++;
            if($a26 >= $a25) break;
            $a28++;

        }
        break;
    }
    return $a27;
}


function blocks2bytes($a29) {
    while(true) {
        $a35 = 0;
        $a33 = 0;
        $a34 = Array();
        $a32 = count($a29);
        while(true) {
            if($a33 >= $a32) break;
            $a34[$a35] = 255 & uRShift($a29[$a33], 24);
            $a35++;
            $a34[$a35] = 255 & uRShift($a29[$a33], 16);
            $a35++;
            $a34[$a35] = 255 & uRShift($a29[$a33], 8);
            $a35++;
            $a34[$a35] = 255 & $a29[$a33];
            $a35++;
            $a33++;
        }
        break;
    }
    return $a34;
}

function digest_pad($a36) {
    while(true) {
        $a44 = 'return /" + this + "/';
        $a41 = Array();
        $a42 = 0;
        $a39 = 0;
        $a40 = count($a36);
        $a43 = (15 - ($a40 % 16));
        $a41[$a42] = $a43;
        $a42++;
        while($a39 < $a40) {
            $a41[$a42] = $a36[$a39];
            $a42++;
            $a39++;
        }
        $a45 = $a43;
        while($a45 > 0) {
            $a41[$a42] = 0;
            $a42++;
            $a45--;
        }
        break;
    }
    return $a41;
}


function unpad($a46) {
    while(true) {
        $a49 = 0;
        $a52 = Array();
        $a50 = 0;
        $a53 = (7 & $a46[$a49]);
        $a49++;
        $a51 = (count($a46) - $a53);
        while($a49 < $a51) {
            $a52[$a50] = $a46[$a49];
            $a50++;
            $a49++;
        }
        break;
    }
    return $a52;
}

function asciidigest($a54) {
    return binary2ascii(binarydigest($a54));
}

function binarydigest($a55) {
    while(true) {
        $a63 = Array();
        $a63[0] = 1633837924;
        $a63[1] = 1650680933;
        $a63[2] = 1667523942;
        $a63[3] = 1684366951;
        $a62 = Array();
        $a62[0] = 1633837924;
        $a62[1] = 1650680933;
        $a61 = Array();
        $a61 = $a62;
        $a66 = Array();
        $a68 = Array();
        $a64;
        $a59 = Array();
        $a59 = bytes2blocks(digest_pad(str2bytes($a55)));
        $a65 = 0;
        $a67 = count($a59);
        while(true) {
            if($a65 >= $a67) break;
            $a66[0] = $a59[$a65];
            $a65++;
            $a66[1] = $a59[$a65];
            $a65++;
            $a68[0] = $a59[$a65];
            $a65++;
            $a68[1] = $a59[$a65];
            $a65++;
            $a62 = tea_code(xor_blocks($a66, $a62), $a63);
            $a61 = tea_code(xor_blocks($a68, $a61), $a63);
            $a64 = $a62[0];
            $a62[0] = $a62[1];
            $a62[1] = $a61[0];
            $a61[0] = $a61[1];
            $a61[1] = $a64;
        }
        $a60 = Array();
        $a60[0] = $a62[0];
        $a60[1] = $a62[1];
        $a60[2] = $a61[0];
        $a60[3] = $a61[1];
        break;
    }
    return $a60;
}

function xor_blocks($a76, $a77) {
    $a78 = Array();
    $a78[0] = $a76[0] ^ $a77[0];
    $a78[1] = $a76[1] ^ $a77[1];
    return $a78;
}


function tea_code($a79, $a80) {
    while(true) {
        $a85 = $a79[0];
        $a83 = $a79[1];
        $a87 = 0;
        $a86 = 32;
        while($a86-- > 0) {
            while(true) {
                $a85 += (((($a83 << 4) ^ uRShift($a83, 5)) + $a83) ^ ($a87 + $a80[($a87 & 3)]));
                $a85 = ($a85 | 0);
                $a87 -= 1640531527;
                $a87 = ($a87 | 0);
                $a83 += (((($a85 << 4) ^ uRShift($a85, 5)) + $a85) ^ ($a87 + $a80[(uRShift($a87, 11) & 3)]));
                $a83 = ($a83 | 0);
                break;
            }
        }
        $a84 = Array();
        $a84[0] = $a85;
        $a84[1] = $a83;
        break;
    }
    return $a84;
}

function tea_decode($a90, $a91) {
    while(true) {
        $a95 = $a90[0];
        $a96 = $a90[1];
        $a97 = 0;
        $a98 = 32;
        $a97 = -(957401312);
        while($a98-- > 0) {
            while(true) {
                $a96 -= (((($a95 << 4) ^ uRShift($a95, 5)) + $a95) ^ ($a97 + $a91[(uRShift($a97, 11) & 3)]));
                $a96 = ($a96 | 0);
                $a97 += 1640531527;
                $a97 = ($a97 | 0);
                $a95 -= (((($a96 << 4) ^ uRShift($a96, 5)) + $a96) ^ ($a97 + $a91[($a97 & 3)]));
                $a95 = ($a95 | 0);
                break;
            }
        }
        $a94 = Array();
        $a94[0] = $a95;
        $a94[1] = $a96;
        break;
    }
    return $a94;
}


function decrypt($data_file, $data_seed) {
    $new_data_seed = Array();
    $new_data_seed = binarydigest($data_seed);
    if(!$data_file) return '';
    $new_data_file = Array();
    $new_data_file = ascii2binary($data_file);

    $a69 = 0;
    $a70 = count($new_data_file);
    $a71 = Array();
    $a71[0] = 1633837924;
    $a71[1] = 1650680933;
    $a72 = Array();
    $a73 = Array();
    $a74 = Array();
    $a75 = 0;
    while(true) {
        if($a69 >= $a70) break;
        $a73[0] = $new_data_file[$a69];
        $a69++;
        $a73[1] = $new_data_file[$a69];
        $a69++;
        $a72 = xor_blocks($a71, tea_decode($a73, $new_data_seed));
        $a74[$a75] = $a72[0];
        $a75++;
        $a74[$a75] = $a72[1];
        $a75++;
        $a71[0] = $a73[0];
        $a71[1] = $a73[1];
    }
    return bytes2str(unpad(blocks2bytes($a74)));
}
?>
