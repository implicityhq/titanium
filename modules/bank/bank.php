<? namespace Titanium\Modules; defined('PATH') or die;

class Bank {
  protected static $information = [];

  public static function set($key, $value) {
    static::$information[$key] = $value;
  }

  public static function get($key, $fallback = false) {
    if (isset(static::$information[$key])) {
      return static::$information[$key];
    } else {
      return $fallback;
    }
  }

  public static function remove($key) {
    unset(static::$information[$key]);
  }
}
