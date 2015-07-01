<? namespace Titanium\Modules; defined('PATH') or die;

class Crypto {
  public static function generateString($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return (string)static::generateRandom($characters, $length);
  }

  public static function generateInt($length = 4) {
    $characters = '0123456789';
    return (int)static::generateRandom($characters, $length);
  }

  public static function generateRandom($pool, $length) {
    $charactersLength = strlen($pool);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $pool[mt_rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }
}
