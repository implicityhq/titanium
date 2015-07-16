<? defined('PATH') or die;

use Titanium\Core\Usher as Usher;

Usher::module(['network', 'pipe', 'chug', 'bank', 'crypto']);

use Titanium\Modules\Bank as Bank;

Bank::set('db', ['localhost', 'root', 'root', 'titanium', 'mysql']);

use Titanium\Modules\Network as Network;
use Titanium\Modules\Network\Router as Router;
use Titanium\Modules\Network\URL as URL;

// override the baseURL since we are not running this in the root dir
URL::$baseURL = '/titanium';

// set the base dir for controllers
Router::$controllersDirectory = APP . 'controllers/';

/**
 * Routes
 */
Router::GET('/', 'Main@index');

Router::GET('/other', 'Main@other');

Router::GET('/team/(:any)', 'Main@team');

// scan for routes and then call the controller
Network::analyze();
