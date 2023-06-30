<?php
class cryptoJsAesDecrypt
{
  public function decrypt1($passphrase, $jsonString){
    $jsondata = json_decode($jsonString, true);
    try {
        $salt = hex2bin($jsondata["s"]);
        $iv  = hex2bin($jsondata["iv"]);
    } catch(Exception $e) { return null; }
    $ct = base64_decode($jsondata["ct"]);
    $concatedPassphrase = $passphrase.$salt;
    $md5 = array();
    $md5[0] = md5($concatedPassphrase, true);
    $result = $md5[0];
    for ($i = 1; $i < 3; $i++) {
        $md5[$i] = md5($md5[$i - 1].$concatedPassphrase, true);
        $result .= $md5[$i];
    }
    $key = substr($result, 0, 32);
    $data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
    return json_decode($data, true);
  }
  public function decrypt2($passphrase, $enc_text)
    {
        $enc_text = json_decode($enc_text, true);
        try {
            $slam_ol = hex2bin($enc_text["salt"]);
            $iavmol  = hex2bin($enc_text["iv"]);
        } catch (Exception $e) {
            return null;
        }
        $ciphertext = base64_decode($enc_text["ciphertext"]);
        $iterations = 999;
        $key = hash_pbkdf2("sha512", $passphrase, $slam_ol, $iterations, 64);
        $decrypted = openssl_decrypt($ciphertext, 'aes-256-cbc', hex2bin($key), OPENSSL_RAW_DATA, $iavmol);
        return $decrypted;
    }
}
?>
