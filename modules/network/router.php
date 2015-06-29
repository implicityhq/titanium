<? namespace Titanium\Modules\Network; defined('PATH') or die;

use Titanium\Modules\Network\URL as URL;

class Router {
  /**
   * an array of routes
   * [type [path, options]]
   */
  protected static $routes = [];

  // directory to the controllers
  public static $controllersDirectory = [];

  // a GET request
  public static function GET($path, $options, $callback = null) {
      static::route('GET', $path, $options, $callback);
  }

  // a POST request
  public static function POST($path, $options, $callback = null) {
      static::route('POST', $path, $options, $callback);
  }

  // a ANY request
  public static function route($method, $path, $options, $callback = null) {
      if ((! is_array($options)) && is_callable($options)) {
          $callback = $options;
          $options = ['callback' => $callback];
      }

      static::$routes[strtoupper($method)][] = ['path' => $path, 'options' => $options];
  }

  // matches routes with current url
  public static function provide($response) {
    $url = URL::current();
  }
}
