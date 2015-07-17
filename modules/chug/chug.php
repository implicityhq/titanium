<? namespace Titanium\Modules; defined('PATH') or die;

use Titanium\Core as Core;
use Titanium\Modules\Bank as Bank;
use Titanium\Modules\Pipe as Pipe;
use Titanium\Modules\Crypto as Crypto;
use Titanium\Modules\Chug\Store as Store;

class Chug extends Core\Module {

  /**
   * ----------
   * Properties
   * ----------
   */

  protected $store; // use to stored database values

  protected $hasBeenCreated; // if the object as been stored in the db

  protected static $db; // to access db. Pipe object

  // properties that don't belong in db
  protected static $excludedProperties = ['store', 'db', 'hasBeenCreated', 'bankDBName'];

  // the key where the db creds are stored in the Bank
  public static $bankDBName = 'db';

  /**
   * ----------
   * Static Functions *to access already stored objects*
   * ----------
   */

  // find a single model with an id
  public static function findWithId($id) {
    static::openDB();

    $results = static::$db->selectWhere(['id', '=', $id]);

    $results->hasBeenCreated = true;

    $class = get_called_class();

    $object = new $class($results);

    return $object;
  }

  // find some models with criteria (optional)
  public static function findAll($criteria = []) {
    static::openDB(); $results = [];

    $query = static::$db->selectAllWhere($criteria);

    foreach ($query as $row) {
      $row->hasBeenCreated = true;

      $class = get_called_class();

      $object = new $class($row);

      $results[] = $object;
    }

    return $results;
  }

  /**
   * ----------
   * Object Methods
   * ----------
   */

  // called when an object is initiated
  public function __construct($values = []) {
    static::openDB();

    // create a new store to store values in
    $this->store = new Store;

    foreach ($values as $k => $v) {
      $this->{$k} = $v;
    }

    // when new object is created this should be empty
    $this->store->changedVars = [];

    return $this;
  }

  // save an already created model
  public function save() {
    if (! $this->hasBeenCreated) {
      return $this->create();
    }


    $this->lastUpdatedTime = time();

    $data = [];
    foreach ($this->store->changedVars as $c) {
      $data[$c] = $this->{$c};
    }

    $status = static::$db->update($data, ['id', '=', $this->id]);
    if (! $status) {
      Core\Disease::annoyance("Could not save model '" . get_class($this) . "' with id {$this->id}");

      return false;
    }

    // empty changed array
    $this->store->changedVars = [];

    return true;
  }

  // save for the first time
  protected function create() {
    // set id
    if (! isset($this->id)) {
      $this->setId();
    }

    // set created time
    if (! isset($this->createdTime)) {
      $this->createdTime = time();
    }

    // set updated time
    $this->lastUpdatedTime = time();

    $data = $this->store->getAll();
    foreach ($data as $k => $v) {
      if (array_search($k, static::$excludedProperties) !== false) {
        unset($data[$k]);
      }
    }

    $status = static::$db->insert($data);
    if (! $status) {
      Core\Disease::annoyance("Could not save model '" . get_class($this) . "' with id {$this->id}");
      return false;
    } else {
      $this->hasBeenCreated = true; // so we don't create it again

      $this->store->changedVars = []; // empty changed vars array

      return true;
    }
  }

  // magic setter
  public function __set($key, $value) {
    if (array_search($key, static::$excludedProperties) === false) {
      $this->store->set($key, $value);
    } else {
      $this->{$key} = $value;
    }
  }

  // get a key from the store
  public function __get($key) {
    if (array_search($key, static::$excludedProperties) === false) {
      return $this->store->get($key);
    } else {
      return $this->{$key};
    }
  }

  // make sure db is initialize
  protected static function openDB() {
    if (! static::$db) {
      static::$db = new Pipe(Bank::get(static::$bankDBName));
      static::$db->table = ucfirst(get_called_class()) . 's';
    }
  }

  // set the id. this can be overrided to create a different type of id
  public function setId() {
    $this->id = Crypto::generateInt(12);
  }
}
