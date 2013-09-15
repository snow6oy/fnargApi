<?php 
spl_autoload_register('apiAutoload');

function handle_exception($e) {
  // pull the correct format before we bail
  global $request, $view;
  header("Status: ". $e->getCode(), false, $e->getCode());
  $view = (isset($view)) ? new $view : new JsonView;
  $view->error($e->getMessage());
}
set_exception_handler('handle_exception');

$request = new Request;
$view = 'TextView'; // default

routeV1($request);

function apiAutoload($classname) {
  // echo 'classname '. $classname. "\n";
  if (preg_match('/[a-zA-Z]+Controller$/', $classname)) {
    include dirname(__FILE__) . '/controllers/' . $classname . '.php';
    return true;
  } elseif (preg_match('/[a-zA-Z]+Model$/', $classname)) {
    include dirname(__FILE__) . '/models/' . $classname . '.php';
    return true;
  } elseif (preg_match('/[a-zA-Z]+View$/', $classname)) {
    include dirname(__FILE__) . '/views/' . $classname . '.php';
    return true;
  }
}

/* 
 * route the request to the right place
 **/
function routeV1($request) {

  global $view;
  $format = ucfirst($request->format);
  $view_name = $format. 'View';
  if (class_exists($view_name)) {
    $view = new $view_name();
  }

  $noun = false;
  // only process requests for /tsunamis
  if ($request->url_elements[0] != 'tsunamis') {
    throw new Exception('Not Found', 404);
  }
  // set the controller based on the requested path
  foreach ($request->url_elements as $path) {
    $noun = preg_match('/^\d+$/', $path) ? 'item' : 'list';
  }
  // humans get text, machines need to ask
  if ($noun and $request->format == 'text') { 
    throw new Exception('Unsupported Media Type', 415);
  }

  $controller_name = ucfirst($noun). 'Controller';
  if (class_exists($controller_name)) {
    $controller = new $controller_name();
    $action_name = strtolower($request->verb). 'Action';
    $control = $controller->$action_name($request);
  } else {
    throw new Exception('Unknown noun', 404);
  }

  $model_name = ucfirst($noun). 'Model';
  if (class_exists($model_name)) {
    $model = new $model_name();
    $doc_type = array_shift($control); // index, list or item
    $data_name = $doc_type. $format. 'Data';
  }

  $result = $model->$data_name($control);
  $view->render($result);
}

class Request {
  public $url_elements;
  public $verb;
  public $parameters;
 
  public function __construct() {

    $this->verb = $_SERVER['REQUEST_METHOD'];
    $this->format = 'text';
    $this->url_elements = preg_split('/\//', $_SERVER['PATH_INFO'] , -1, PREG_SPLIT_NO_EMPTY);

    $this->parseIncomingParams();
    if (isset($this->parameters['format'])) {
      $this->format = $this->parameters['format'];
    }
    return true;
  }

  public function parseIncomingParams() {
    $parameters = array();
    $accept = false;
 
    // first of all, pull the GET vars
    if (isset($_SERVER['QUERY_STRING'])) {
      parse_str($_SERVER['QUERY_STRING'], $parameters);
    }
    if(isset($_SERVER['HTTP_ACCEPT'])) {
      $accept = $_SERVER['HTTP_ACCEPT'];
    }
    switch($accept) {
      case "text/html":
      case "text/plain":
        $this->format = "text";
        break;
      case "application/vnd.collection+json":
        $body_params = json_decode($body);
        if($body_params) {
          foreach($body_params as $param_name => $param_value) {
            $parameters[$param_name] = $param_value;
          }
        }
        $this->format = "collectionJson";
        break;
      default:
        // we could parse other supported formats here
        // case "application/x-www-form-urlencoded":
        break;
    }
    $this->parameters = $parameters;
  }
}

/*
 * http://www.lornajane.net/posts/2012/building-a-restful-php-server-understanding-the-request
 **/
?>
