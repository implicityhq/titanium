<? namespace Titanium\Modules\Octopus; defined('PATH') or die;

class Safe {
  public static function generateKeys() {
    $config = array(
      'digest_alg' => 'sha512',
      'private_key_bits' => 4096,
      'private_key_type' => OPENSSL_KEYTYPE_RSA
    );

    $res = openssl_pkey_new($config);

    openssl_pkey_export($res, $privateKey);

    $publicKey = openssl_pkey_get_details($res);
    $publicKey = $publicKey['key'];

    return compact('publicKey', 'privateKey');
  }

  public static function lockWithPrivateKey($data, $key) {
    openssl_private_encrypt($data, $crypted, $key);
    return base64_encode($crypted);
  }

  public static function lockWithPublicKey($data, $key) {
    openssl_public_encrypt($data, $crypted, $key);
    return base64_encode($crypted);
  }

  public static function unlockWithPrivateKey($data, $key) {
    openssl_private_decrypt(base64_decode($data), $decrypted, $key);
    return $decrypted;
  }

  public static function unlockWithPublicKey($data, $key) {
    openssl_public_decrypt(base64_decode($data), $decrypted, $key);
    return $decrypted;
  }
}
