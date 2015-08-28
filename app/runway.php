<? defined('PATH') or die;

use Titanium\Core\Usher as Usher;

Usher::module(['network', 'pipe', 'chug', 'bank', 'crypto', 'octopus']);

use Titanium\Modules\Bank as Bank;

Bank::set('db', ['127.0.0.1', 'homestead', 'secret', 'titanium', 'mysql', 3306]);

use Titanium\Modules\Network as Network;
use Titanium\Modules\Network\Router as Router;
use Titanium\Modules\Network\URL as URL;
use Titanium\Modules\Network\Response as Response;

// override the baseURL since we are not running this in the root dir
URL::$baseURL = '/';

// set the base dir for controllers
Router::$controllersDirectory = APP . 'controllers/';

// set the base dir for views
Response::$viewsDirectory = APP . 'views/';

/**
 * Routes
 */
Router::GET('/', 'Main@index');

Router::GET('/other', 'Main@other');

Router::GET('/team/(:any)', 'Main@team');

// scan for routes and then call the controller
Network::analyze();
