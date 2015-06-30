<? namespace Titanium\Modules; defined('PATH') or die;

use Titanium\Core as Core;
use Titanium\Modules\Bank as Bank;
use Titanium\Modules\Pipe as Pipe;

class Chug extends Core\Module {
  protected $changedVars = []; // keys of changed variables
  protected $db;
  protected $hasBeenSaved;

  // find a single model with an id
  public static function findWithId($id) {

  }

  // find some models with criteria (optional)
  public static function findAll($criteria = []) {

  }

  // called when an object is initiated
  public function __construct() {
    $this->db = new Pipe(Bank::get('db'));
    $this->db->table = ucfirst(get_class($this)) . 's';
    return $this;
  }

  // save an already created model
  public function save() {
    if (! $this->hasBeenSaved) {
      return $this->create();
    }

    $data = [];
    foreach ($this->changedVars as $c) {
      $data[$c] = $this->{$c};
    }

    $status = $this->db->update($data, [['id', '=', $this->id]]);
    if (! $status) {
      Core\Disease::annoyance("Could not save model '" . get_class($this) . "' with id {$this->id}");

      return false;
    }

    return true;
  }

  // save for the first time
  protected function create() {
    $data = get_object_vars($this);
  }
}
