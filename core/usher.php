<? namespace Titanium\Core; defined('PATH') or die;

/**
 * Usher handles the loading of all files.
 * It autoloads core classes and manually loads modules
 */

class Usher {
  // Absolute path to the "core/" directory
  public static $corePath;

  // Absolute path to the "modules/" directory
  public static $modulesPath;

  // File extension
  public static $ext = '.php';

  // called by the php autoloader to include a core class
  public static function autoload($className) {
    if (($path = static::findFile($className))) {
      static::summon($path);
    }
  }

  // loads a module and its classes
  public static function module($paths) {
    // go through each module name (path)
    foreach ((array) $paths as $mod) {
      $modPath = static::$modulesPath . $mod . '/';
      // make sure module exists
      if (file_exists($modPath)) {
        // include initial module file
        static::summon($modPath . $mod . static::$ext);
      } else {
        Disease::deadly("Module: '{$mod}' does not exist.");
      }
    }
  }

  // this is the only place where files are required in the system
  public static function summon($path, $once = true) {
    if (! file_exists($path)) {
      Disease::deadly("File: '{$path}' does not exist.");
    }

    if ($once) {
      require_once $path;
    } else {
      require $path;
    }
  }

  // take a class name and return file to where it belongs
  protected static function findFile($className) {
    $className = strtolower(str_replace('\\', '/', $className));
    $className = str_replace('titanium/', '', $className);

    // check to see if is a core file
    $path = (strpos($className, '/') ? PATH : static::$corePath) . $className . static::$ext;
    if (file_exists($path)) {
      return $path;
    } else {
      return false;
    }
  }
}
