<? namespace Titanium\Modules; defined('PATH') or die;

use Titanium\Core as Core;
use Titanium\Modules\Pipe\Syntax as Syntax;

class Pipe extends Core\Module {
  public $db;
  public $table;
  public $fetchStyle;

  // create a connection
  public function __construct($host, $username = '', $password = '', $database = '', $driver = 'mysql') {
    if (is_array($host)) {
      list($host, $username, $password, $database, $driver) = $host;
    }

    $this->db = new \PDO("{$driver}:host={$host};dbname={$database};charset=utf8", $username, $password);

    $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    $this->fetchStyle = \PDO::FETCH_OBJ;

    return $this;
  }

  /**
   * Querying
   */

  // get all rows from a table
  public function selectAll(array $keys = [], $orderby = '') {
    list($query, $params) = Syntax::selectAll($this->table, $keys, $orderby);
    return $this->query($query, $params)->fetchAll($this->fetchStyle);
  }

  // get all rows from a table with a where condition
  public function selectAllWhere($search, array $keys = [], $orderby = '') {
    list($query, $params) = Syntax::selectWhere($this->table, $search, $keys, $orderby);
    return $this->query($query, $params)->fetchAll($this->fetchStyle);
  }

  // get first row from a table with a where condition
  public function selectWhere($search, array $keys = [], $orderby = '') {
    list($query, $params) = Syntax::selectWhere($this->table, $search, $keys, $orderby);
    return $this->query($query, $params)->fetch($this->fetchStyle);
  }

  // insert a new row
  public function insert(array $data) {
    list($query, $params) = Syntax::insert($this->table, $data);
    return $this->exec($query, $params);
  }

  // run a query and get results
  public function query($sql, $params = []) {
    $query = $this->db->prepare($sql);
		$query->execute((array) $params);
		return $query;
  }

  // run a query and return query status
  public function exec($sql, $params = []) {
    $query = $this->db->prepare($sql);
		return $query->execute((array) $params);
  }

}
