<? namespace Titanium\Core; defined('PATH') or die;

/**
 * This is the error handling class
 */

// This is only used to tell where the exception came from
class Exception extends \Exception {}

// slightly modified version from https://gist.github.com/abtris/1437966
function getExceptionTrace($exception) {
  $rtn = [];
  $count = count($exception->getTrace());
  foreach ($exception->getTrace() as $frame) {
    $args = "";
    if (isset($frame['args'])) {
      $args = array();
      foreach ($frame['args'] as $arg) {
        if (is_string($arg)) {
          $args[] = "'" . $arg . "'";
        } elseif (is_array($arg)) {
          $args[] = "Array";
        } elseif (is_null($arg)) {
          $args[] = 'NULL';
        } elseif (is_bool($arg)) {
          $args[] = ($arg) ? "true" : "false";
        } elseif (is_object($arg)) {
          $args[] = get_class($arg);
        } elseif (is_resource($arg)) {
          $args[] = get_resource_type($arg);
        } else {
          $args[] = $arg;
        }
      }   
      $args = join(", ", $args);
    }

    $rtn[] = sprintf("#%s %s(%s): %s(%s)",
      --$count,
      isset($frame['file']) ? $frame['file'] : 'unknown file',
      isset($frame['line']) ? $frame['line'] : 'unknown line',
      (isset($frame['class']))  ? $frame['class'].$frame['type'].$frame['function'] : $frame['function'],
      $args);

  }
  return $rtn;
}

class Disease {
  // if $severe then all errors will kill application
  public static $severe = false;

  // this will kill the application and show the message or show error page
  public static function deadly($message) {
    throw new Exception($message);
  }

  // this will just log an error or call deadly if $severe is enabled.
  public static function annoyance($message) {
    if (static::$severe) {
      static::deadly($message);
    } else {
      Log::error('mild', $message);
    }
  }

  // handles and prints out all exceptions
  public static function control($error) {
    $trace = getExceptionTrace($error);
    $message = $error->getMessage();

    $exceptionClass = get_class($error);
    $displayMessage = "'{$exceptionClass}' with message '{$message}'";

    print 'EXCEPTION ------' . PHP_EOL;
    print $displayMessage . PHP_EOL;
    print implode("\n", $trace);
    print PHP_EOL . '------ EXCEPTION';

    Log::error('severe', $displayMessage . ' --- ' . implode(' --- ', $trace));
  }
}