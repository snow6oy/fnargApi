<?php

/* 
 * return list data from search query
 **/

include 'MysqlIO.php';

class ListModel extends MysqlIO {
  function listCollectionJsonData($control) {

    $sql = <<<SQL
SELECT CONCAT('http://api.fnarg.net/tsunamis/', ID) as href
FROM events 
WHERE YEAR <= ?
ORDER BY ID DESC 
LIMIT 0 , ?
SQL;
    $items = $this->getBoundRows($sql, $control);
    return array('items' => $items);
  }

  function indexCollectionJsonData($control) {
    return array();
  }
}
?>
