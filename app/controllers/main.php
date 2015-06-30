<? defined('PATH') or die;

use Titanium\Modules\Pipe as Pipe;
use Titanium\Modules\Bank as Bank;

class MainController {
  public function index($rs) {
    $pipe = new Pipe(Bank::get('db'));


  }

  public function other($rs) {
    $rs->render('MAIN@OTHERRRR');
  }

  public function team($rs, $member) {
    $rs->render('TEAM/' . $member);
  }
}
