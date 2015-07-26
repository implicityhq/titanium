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

    $key = 'this is a super secret password';
    $m = microtime(true);
		$k = $key;
		$a = defined('APP_KEY') ? APP_KEY : $this->random_str(32);
		$salt = substr(str_replace('+', '.', base64_encode(sha1(microtime(true), true))), 0, 22);
		$t = $this->_tcrypt($m . $k . $a, 10, $salt);
    $t = md5($t);

    $iv = mcrypt_create_iv(32);
    $string = 'hello my name is bob thomas and i like things that are very encrypted.';
    $e = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $t, $string, MCRYPT_MODE_CBC, $iv);
    var_dump(base64_encode($e));
    $d = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $t, $e, MCRYPT_MODE_CBC, $iv);
    var_dump($string === rtrim($d, "\0"));
    var_dump(base64_encode($iv));
  }

  protected function random_str($l = 16) {
		$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		return substr(str_shuffle(str_repeat($pool, 5)), 0, $l);
	}

  protected function _tcrypt($str, $rounds, $salt) {
		return crypt($str, '$2a$' . $rounds . '$' . $salt);
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
