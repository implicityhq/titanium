<? namespace Titanium\Modules\Chug; defined('PATH') or die;

class Store {
  protected $information = []; // where all the data is stored

  public $changedVars = []; // everything thats changed

  public function set($key, $value) {
    $this->information[$key] = $value;
    $this->changedVars[] = $key;
  }

  public function get($key) {
    return $this->information[$key];
  }

  public function getAll() {
    return $this->information;
  }
}
