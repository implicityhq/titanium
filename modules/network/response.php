<? namespace Titanium\Modules\Network; defined('PATH') or die;

class Response {
  // render the page
  public function render($data, $headers = []) {
    foreach ($headers as $key => $value) {
      header("{$key}: {$value}");
    }
    print 'RENDER ----' . PHP_EOL;
    print $data;
    print PHP_EOL . '---- RENDER';
  }
}
