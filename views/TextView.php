<?php
class TextView {
  public function error($content) {
    header('Content-Type: text/plain; charset=utf8');
    echo '-------------------------------------------------------------------'. "\n";
    echo('API Home Page'). "\n";
    echo '-------------------------------------------------------------------'. "\n";
    echo($content). "\n";
    return true;
  }
  public function render($content) {
    header('Content-Type: text/plain; charset=utf8');
    echo "api.fnarg.net\n";
    return true;
  }
}
?>
