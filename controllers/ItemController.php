<?php
/* 
 * Methods 
 * GET
 * 
 * Resources
 * 
 * /tsunamis/234		process request for given resource
 **/

class ItemController {

  function getAction($request) {

    // cast query params to int otherwise PDO throws its toys out
    if (isset($request->url_elements[1])) {
      $id = (int)$request->url_elements[1];
    } 
    if (! preg_match('/[\d]+/', $id))  {
      throw new Exception("Invalid request", 400);
    }
    return array(
      'model' => 'item',
       array('val' => $id,  'pdo' => PDO::PARAM_INT)
    );
  }
}
?>
