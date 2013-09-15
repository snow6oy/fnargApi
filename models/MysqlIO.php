<?php

/*
 * Database IO functions
 **/
class MysqlIO {
  protected $dbh;

  function dbhandle() {
    try {
      $this->dbh = new PDO(
        'mysql:host=localhost;dbname=dishyzee_tsunami', 
        'dishyzee_tsunamR', 'KHJ5BLR6cVtX4T1i5vRj'
      );
      $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->dbh->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, TRUE);
    } catch (PDOException $e) {
      throw new Exception('Db connx error', 500);
    }
  }

  function getBoundRows($sql,$control) {
    $this->dbhandle();
    $i = 0;
    $rows = array();
    $stmt = $this->dbh->prepare($sql);
    $bind_id = 1;
    foreach ($control as $c) {
      $stmt->bindValue($bind_id,$c['val'],$c['pdo']);
      $bind_id++;
    }
    if ($stmt->execute()) {
      // PDO::FETCH_ASSOC returns the associate array names
      while ($cols = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $rows[$i] = $cols;
        $i++;
      }
    } else {
      $e = new PDOException();
      throw new Exception('Db query error: '. $e, 500);
    }
    return $rows;
  }
}
?>
