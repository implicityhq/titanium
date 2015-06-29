<? namespace App; defined('PATH') or die;

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
Router::GET('/', function ($response) {
	$response->render('Hello!', ['Content-Type' => 'text/plain']);
});

Router::GET('/other', 'Main@other');

Router::GET('/team/(:any)', 'Main@team');

// scan for routes and then call the controller
Network::analyze();
