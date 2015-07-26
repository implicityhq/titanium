<? defined('PATH') or die;

use Titanium\Modules\Pipe as Pipe;
use Titanium\Modules\Bank as Bank;
use Titanium\Modules\Chug as Chug;
use Titanium\Modules\Crypto as Crypto;
use Titanium\Modules\Crypto\Random as Random;

class MainController {
  public function index($rs) {
    header('Content-Type: text/plain');
    // $chug_test = new Pipe(Bank::get('db'));
    // $chug_test->table = 'Persons';
    //
    // $results = $chug_test->selectAllWhere(['email', '~', '%@%']);
    // $rs->render(json_encode($results), ['Content-Type' => 'application/json']);

    $startTime = microtime(true);
    $key = 'super secret password';
    $message = 'there\'s always a message in the bottle';
    list($encryptedString, $hashedKey, $iv) = Crypto::encrypt($message, $key);
    $decryptedString = Crypto::decrypt($encryptedString, $hashedKey, $iv);
    $endTime = microtime(true);

    assert($message === $decryptedString);
    print 'YAY there twins' . PHP_EOL;
    print $startTime . PHP_EOL . $endTime;
  }

  public function other($rs) {
    $rs->render('MAIN@OTHERRRR');
  }

  public function team($rs, $member) {
    $rs->view('team/member')->render(['member' => $member]);
  }
}

class Person extends Chug {
  public function setId() {
    $this->id = Random::generateString(16);
  }
}
