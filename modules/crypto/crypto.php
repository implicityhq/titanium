<? namespace Titanium\Modules; defined('PATH') or die;

use Titanium\Modules\Crypto\Random as Random;

class Crypto {

  public static $keyHashRounds = '06';

  public static function encrypt($string, $key, $iv = null, $options = []) {
    $options = array_merge(['base64' => true, 'hashKey' => true], $options);

    if (! $iv) {
      $iv = static::createIV(); // create the IV if not given
    }

    if ($options['hashKey'] === true) {
      $key = static::hashKey($key); // hash key
    }

    $encoded = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_CBC, $iv); // encrypt

    $encoded = (($options['base64'] === true) ? base64_encode($encoded) : $encoded); // base64 encode?

    return [$encoded, $key, $iv];
  }

  public static function decrypt($string, $key, $iv, $options = []) {
    $options = array_merge(['base64' => true], $options);

    if ($options['base64'] === true) {
      $string = base64_decode($string); // base64 decode?
    }

    $d = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_CBC, $iv); // decrypt

    return rtrim($d, "\0"); // return clean string
  }

  public static function hashKey($key) {
    $time = microtime(true);
		$pad = Random::generateString(32);
		$salt = substr(str_replace('+', '.', base64_encode(sha1($time, true))), 0, 22);
    $hashedKey = crypt(implode(':', [$time, $key, $pad]), '$2a$' . static::$keyHashRounds . '$' . $salt);
    return hash('sha256', $hashedKey);
  }

  public static function createIV() {
    return mcrypt_create_iv(32);
  }
}
