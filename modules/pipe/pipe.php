<? namespace Titanium\Modules; defined('PATH') or die;

use Titanium\Core as Core;

class Pipe extends Core\Module {
  public $db;

  public function __construct($host, $username = '', $password = '', $database = '') {
    if (is_array($host)) {
      list($host, $username, $password, $database) = $host;
    }

    $this->db = new \PDO("mysql:host={$host};dbname={$database};charset=utf8", $username, $password);

    return $this;
  }
}
