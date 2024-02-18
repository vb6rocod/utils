<?php
function CryptoJSAES_decrypt($saltCiphertextB64,$password) {
  $saltCiphertext = base64_decode($saltCiphertextB64);
  $salt = substr($saltCiphertext, 8, 8);
  $ciphertext = substr($saltCiphertext, 16);

  // Derive key and IV
  $keyIv = EVP_BytesToKey($salt, $password);
  $key = substr($keyIv, 0, 32);
  $iv = substr($keyIv, 32);

  // Decrypt
  $result = openssl_decrypt($ciphertext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
  return $result;
}
function EVP_BytesToKey($salt, $password) {
    $derived = '';
    $tmp = '';
    while(strlen($derived) < 48) {
        $tmp = md5($tmp . $password . $salt, true);
        // $tmp = hash('sha256', $tmp . $password . $salt, true);
        $derived .= $tmp;
    }
    return $derived;
}
?>
