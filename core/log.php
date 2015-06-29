<? namespace Titanium\Core; defined('PATH') or die;

/**
 * This class handles all logging
 */

class Log {
  // the path to the log file
  public static $currentLogFile;

  public static $enabled = true;

  // write to the error log
  public static function error($type, $error) {
    $now = microtime(true);
    $message = "[{$now}] [ERROR] ({$type}): {$error}" . PHP_EOL;
    static::write($message);
  }

  // write to a log file
  protected static function write($message) {
    if (static::$enabled) {
      file_put_contents(static::$currentLogFile, PHP_EOL . $message, FILE_APPEND);
    }
  }
}