<? namespace Titanium\Modules; defined('PATH') or die;

use Titanium\Modules\Crypto\Random as Random;
use Titanium\Modules\Crypto as Crypto;

/**
 * Octopus User Encryption
 *
 * docs coming soon...
 */

class Octopus {
  public static $hashRounds = 1000;

  public static function initiateUser($userId, $password) {
    $key = Random::generateString(32);
    $recoveryCode = Random::generateString(32);
    $F_u = base64_encode(static::encryptWithKey($key, $recoveryCode));
    $S_u = Crypto::hashKey(Random::generateString(32));
    $H_u = hash_pbkdf2('sha256', $password, $S_u, static::$hashRounds);
    $V_u = substr($H_u, 0, (int)(strlen($H_u)/2));
    $E_u = base64_encode(static::encryptWithKey($key, substr($H_u, (int)(strlen($H_u)/2))));
    return compact('key', 'recoveryCode', 'F_u', 'S_u', 'H_u', 'V_u', 'E_u');
  }

  public static function verifyPassword($userObj, $password) {
    $passwordHash = hash_pbkdf2('sha256', $password, $userObj->salt, static::$hashRounds);
    $passwordHashLength = (int)(strlen($passwordHash) / 2);
    $firstHalf = substr($passwordHash, 0, $passwordHashLength);
    $secondHalf = substr($passwordHash, $passwordHashLength);
    if ($userObj->verificationToken === $firstHalf) { // correct password
        $key = static::decryptWithKey(base64_decode($userObj->passwordKey), $secondHalf);
        return compact('key');
    } else {
      return false;
    }
  }

  public static function encryptWithKey($string, $key) {
    $encryptionMethod = "AES-256-CBC";

    return @openssl_encrypt($string, $encryptionMethod, $key);
  }

  public static function decryptWithKey($string, $key) {
    $encryptionMethod = "AES-256-CBC";

    return openssl_decrypt($string, $encryptionMethod, $key);
  }

  public static function createSalt() {
    $time = microtime(true);
    $salt = substr(str_replace('+', '.', base64_encode(sha1($time, true))), 0, 22);
    return '$2a$' . static::$hashRounds . '$' . $salt;
  }
}
