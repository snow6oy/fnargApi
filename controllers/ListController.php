<?php
/* 
 * inspect incoming request and determine the relevant model to apply
 *
 * Methods 
 * GET
 * 
 * Resources
 * 
 * /				see RootController
 * /tsunamis			empty collection except for queries
 * /tsunamis?year=YYYY 		search for records matching given year
 * /tsunamis?year		from this current year
 * /tsunamis/234		see ItemController
 **/

class ListController {

  const LIMIT = 20; // records returned

  function getAction($request) {

    $year = date("Y");  // search default is this year
    $limit = self::LIMIT;
    $model = 'index';

    if (isset($request->parameters['year'])) {
      $year = $request->parameters['year'];
      if (! preg_match('/[0-9]+/', $year))  {
        throw new Exception("Invalid request", 400);
      }
      // cast query params to int otherwise PDO throws its toys out
      $year = (int)$year;
      $model = 'list';
    }

    // some vars are only used by ListModel->listData, but we still send em *shrug*
    return array('model' => $model,
      array('val' => $year, 'pdo' => PDO::PARAM_STR),
      array('val' => $limit,  'pdo' => PDO::PARAM_INT)
    );
  }
}
?>
