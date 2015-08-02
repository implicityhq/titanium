<? namespace Titanium\Modules\Network; defined('PATH') or die;

class Response {
  protected $content, $view;

  public static $viewsDirectory = '';

  // render the page
  public function render($data = [], $headers = []) {
    foreach ($headers as $key => $value) {
      header("{$key}: {$value}");
    }

    if ((! empty($data)) && is_string($data)) {
      $this->content .= $data;
    }

    if (! empty($this->view)) {
      list($html, $layout) = $this->parseView($this->view, $data);

      $data['__view'] = $html;

      $response = $this->parseLayout($this->layoutPath($layout), $data);
      $this->content = $response;
    }

    echo $this->content;
    return;
  }

  // this function tells the response to render a view
  public function view($viewPath) {
    $directory = static::$viewsDirectory;
    $this->view = "{$directory}{$viewPath}.php";
    return $this;
  }

  // get the path of the layout (this can sub classed if needed)
  protected function layoutPath($layoutName) {
    $directory = static::$viewsDirectory;
    return "{$directory}layouts/{$layoutName}.php";
  }

  // this function parses a view and returns the html and the layout
  private function parseView($path, $data = []) {
    ob_start();
    extract($data, EXTR_SKIP);
    require $path;
    $layout = $__layout;
    $html = ob_get_contents();
    ob_end_clean();
    return [$html, $layout];
  }

  // this will parse the layout and the view to create the final html
  private function parseLayout($path, $data = []) {
    ob_start();
    extract($data, EXTR_SKIP);
    require $path;
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
  }
}
