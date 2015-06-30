<? defined('PATH') or die;

class MainController {
  public function index($rs) {
    $rs->render('MAIN@INDEX');
  }

  public function other($rs) {
    $rs->render('MAIN@OTHERRRR');
  }

  public function team($rs, $member) {
    $rs->render('TEAM/' . $member);
  }
}
