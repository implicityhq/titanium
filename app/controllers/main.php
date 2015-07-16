<? defined('PATH') or die;

use Titanium\Modules\Pipe as Pipe;
use Titanium\Modules\Bank as Bank;
use Titanium\Modules\Chug as Chug;
use Titanium\Modules\Crypto as Crypto;

class MainController {
  public function index($rs) {
    header('Content-Type: text/plain');
    // $chug_test = new Pipe(Bank::get('db'));
    // $chug_test->table = 'Persons';
    //
    // $results = $chug_test->selectAllWhere([['email', '~', '%oo%']]);
    // $rs->render(json_encode($results), ['Content-Type' => 'application/json']);

    
  }

  public function other($rs) {
    $rs->render('MAIN@OTHERRRR');
  }

  public function team($rs, $member) {
    $rs->render('TEAM/' . $member);
  }
}

class Person extends Chug {
  public function setId() {
    $this->id = Crypto::generateString(16);
  }
}
