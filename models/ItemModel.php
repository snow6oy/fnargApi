<?php
/* 
 * fetch one record
 **/

include 'MysqlIO.php';

class ItemModel extends MysqlIO {
  function itemCollectionJsonData($control) {

    $sql = <<<SQL
SELECT 
  e.ID as id,
  YEAR as year,
  COUNTRY as country,
  LOCATION_NAME as location,
  LATITUDE as latitude,
  LONGITUDE as longitude,
  CAUSE as cause         
FROM events AS e, causes AS c
WHERE e.CAUSE_CODE = c.ID
AND e.ID = ?
SQL;
    $rs = $this->getBoundRows($sql, $control);

    // domain agnostic aint pretty
    $data = array(
      array('name' => 'id',        'value' => $rs[0]['id']),
      array('name' => 'year',      'value' => $rs[0]['year']),
      array('name' => 'country',   'value' => $rs[0]['country']),
      array('name' => 'location',  'value' => $rs[0]['location']),
      array('name' => 'latitude',  'value' => $rs[0]['latitude']),
      array('name' => 'longitude', 'value' => $rs[0]['longitude']),
      array('name' => 'cause',     'value' => $rs[0]['cause']) 
    );

    return array('items' => 
      array(
        'href' => "http://api.fnarg.net/tsunamis/". $control[0]['val'],
        'data' => $data
      )
    );
  }
}
?>
