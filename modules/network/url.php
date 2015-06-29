<? namespace Titanium\Modules\Network; defined('PATH') or die;

class URL {
  // you only need to set this to something else if you are using this in a directory.
  public static $baseURL = '/';

  // get the current URL
  public static function current() {
    $ugly = $_SERVER['REQUEST_URI'];
    $semi = str_replace(static::$baseURL, '', $ugly);
    $clean = preg_replace('/(\?.+)/', '', $semi);
    return $clean;
  }
}
