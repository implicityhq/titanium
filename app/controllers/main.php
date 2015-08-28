<? defined('PATH') or die;

// use Titanium\Modules\Pipe as Pipe;
// use Titanium\Modules\Bank as Bank;
use Titanium\Modules\Chug as Chug;
// use Titanium\Modules\Crypto as Crypto;
use Titanium\Modules\Octopus as Octopus;
use Titanium\Modules\Octopus\Safe as Safe;
use Titanium\Modules\Crypto\Random as Random;

class MainController {
  public function index($rs) {
    // header('Content-Type: text/plain');
    // $chug_test = new Pipe(Bank::get('db'));
    // $chug_test->table = 'Persons';
    //
    // $results = $chug_test->selectAllWhere(['email', '~', '%@%']);
    // $rs->render(json_encode($results), ['Content-Type' => 'application/json']);

    // $o = Octopus::initiateUser('9801', 'supersecureftw');
    // var_dump($o);

    $jason = Person::findWithId('rH3UBxiees95Qd3H');
    var_dump(Octopus::verifyPassword($jason, 'supersecureftw'));

    $keys = Safe::generateKeys();
    $priv = $keys['privateKey'];
    $pub = $keys['publicKey'];

    $message = 'hello, this is a secret message.';

    $enc = Safe::lockWithPrivateKey($message, $priv);
    $dec = Safe::unlockWithPublicKey($enc, $pub);

    assert($dec === $message);

    $enc = Safe::lockWithPublicKey($message, $pub);
    $dec = Safe::unlockWithPrivateKey($enc, $priv);

    assert($dec === $message);

    print 'All Good.' . PHP_EOL;
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
