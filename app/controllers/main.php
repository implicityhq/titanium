<? defined('PATH') or die;

use Titanium\Modules\Pipe as Pipe;
use Titanium\Modules\Bank as Bank;

class MainController {
  public function index($rs) {
    $chug_test = new Pipe(Bank::get('db'));
    $chug_test->table = 'chug_test';

    $results = $chug_test->selectAllWhere([['email', '~', '%oo%']]);
    $rs->render(json_encode($results), ['Content-Type' => 'application/json']);
  }

  public function other($rs) {
    $rs->render('MAIN@OTHERRRR');
  }

  public function team($rs, $member) {
    $rs->render('TEAM/' . $member);
  }
}
