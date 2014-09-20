<?php

/* 
 * return list data from search query
 **/

include 'MysqlIO.php';

class ListModel extends MysqlIO {
  function listCollectionJsonData($control) {
    $items = array();

    $sql = <<<SQL
SELECT CONCAT('http://api.fnarg.net/tsunamis/', ID) as href,
  YEAR as year,
  COUNTRY as country
FROM events 
WHERE YEAR <= ?
ORDER BY ID DESC 
LIMIT 0 , ?
SQL;
    $rows = $this->getBoundRows($sql, $control);
    foreach($rows as $r){
      array_push($items, 
        array('href'=>$r['href'], 'data'=>
          array(
            array('name'=>'year', 'value'=>$r['year']),
            array('name'=>'country', 'value'=>$r['country'])
          )
        )
      );
    }
    return array('items' => $items);
  }

  function indexCollectionJsonData($control) {
    return array();
  }
}
?>
