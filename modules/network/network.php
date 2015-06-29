<? namespace Titanium\Modules; defined('PATH') or die;

use Titanium\Core as Core;
use Titanium\Modules\Network\Router as Router;
use Titanium\Modules\Network\Response as Response;

class Network extends Core\Module {
  //
  public static function analyze() {
    $response = new Response;
    Router::provide($response);
  }
}
