<? namespace Titanium;

date_default_timezone_set('America/Los_Angeles');
ini_set('display_errors', true);
define('PATH', dirname(__FILE__) . '/');
define('CORE', PATH . 'core/');
define('MODULES', PATH . 'modules/');
define('APP', PATH . 'app/');

require CORE . 'init.php';

exit;
