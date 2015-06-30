<? namespace Titanium\Core; defined('PATH') or die;

require_once CORE . 'usher.php';
require_once CORE . 'log.php';
require_once CORE . 'disease.php';

// set paths for Usher so it can autoload
Usher::$corePath = CORE;
Usher::$modulesPath = MODULES;

// set log file
Log::$currentLogFile = PATH . 'log/dev.log';
// Log::$enabled = false;

// make sure all errors are severe (dev only)
Disease::$severe = true;

// tell php to use Disease::control to show error messages
set_exception_handler(['Titanium\Core\Disease', 'control']);

// tell php to autoload using Usher::autoload
spl_autoload_register(['Titanium\Core\Usher', 'autoload']);

Usher::summon(APP . 'runway.php');
