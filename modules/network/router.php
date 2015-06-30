<? namespace Titanium\Modules\Network; defined('PATH') or die;

use Titanium\Modules\Network\URL as URL;
use Titanium\Core\Disease as Disease;
use Titanium\Core\Usher as Usher;

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
      if (! is_array($options)) {
          $callback = $options;
          $options = ['callback' => $callback];
      }

      static::$routes[strtoupper($method)][] = ['path' => $path, 'options' => $options];
  }

  // matches routes with current url
  public static function provide($response) {
    $url = URL::current();
    $method = $_SERVER['REQUEST_METHOD'];

    $search = array('(:num)', '(:float)', '(:any)');
		$replace = array('([0-9]+)', '([0-9\.]+)', '([a-zA-Z0-9\.\-_%=]+)');

    // cycle through routes
    foreach (static::$routes[$method] as $object) {
      $path = str_replace($search, $replace, $object['path']);
      $options = $object['options'];

      // check to see if correct route
      if (preg_match('#^' . $path . '$#', $url, $matches)) {
        array_shift($matches);

        $type = 'error';
        if (is_string($options['callback'])) { // Controller@method
          $type = 'string';
        } else if (is_callable($options['callback'])) { // closure
          $type = 'function';
        } else if (is_array($options['callback'])) { // 'do' => closure/string
          $type = 'array';
        }

        static::handleAssignment($type, $options, $matches, $response);
      }
    }
  }

  // calls mvc function to initiate controller
  protected static function handleAssignment($type, $options, $matches = [], $response) {
    $controllerName = ''; $methodName = '';
    switch ($type) {
      case 'string': // if string then sepereate into Controller@method
        $parts = explode('@', $options['callback']);
        $controllerName = array_shift($parts);
        $methodName = array_shift($parts);
        break;

      case 'function': // if function call function if string return then call assignment:string
        $callback = call_user_func_array($options['callback'], [$response, $matches]);
        if ($callback && is_string($callback)) {
          static::handleAssignment('string', ['callback' => $callback], $matches, $response);
        } else if ($callback && (! is_string($callback))) {
          Disease::deadly('Unknown route callback return type.');
        }
        return;
        break;

      case 'array': // if array then pass to assignment again
        if (is_string($options['do'])) {
          static::handleAssignment('string', ['callback' => $options['do']], $matches, $response);
        } else if (is_callable($options['do'])) {
          static::handleAssignment('function', ['callback' => $options['do']], $matches, $response);
        }
        // TODO: add before and after hooks (for auth)
        return;
        break;

      default: // oops
        Disease::deadly('Unknown options array for route.');
        break;
    }

    static::initiateController($controllerName, $methodName, $matches, $response);
  }

  // this function initiates the controller
  protected static function initiateController($controllerName, $methodName, $matches, $response) {
    $controllerPath = static::$controllersDirectory . strtolower($controllerName) . Usher::$ext;

    if (file_exists($controllerPath)) {
      Usher::summon($controllerPath);

      $controllerName = '\\' . ucfirst(str_replace('/', '', $controllerName)) . 'Controller';

      if (class_exists($controllerName)) {
        $controller = new $controllerName;

        if (method_exists($controller, $methodName)) {
          call_user_func_array([$controller, $methodName], array_merge([$response], $matches));

          return;
        }
      }
    }

    Disease::deadly("Failed to load controller '{$controllerName}'");
  }
}
