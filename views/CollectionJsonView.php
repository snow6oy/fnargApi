<?php
/* 
 * container for Collection+JSON documents
 **/
class CollectionJsonView {
  public function render($content) {
    header('Content-Type: application/vnd.collection+json; charset=utf8');

    $doc = $this->cjDoc();
    if (isset($content['items'])) {
      $doc['collection']['items'] = $content['items'];
    } 

    echo json_encode($doc);
    return true;
  }

  public function error($message) {
    header('Content-Type: application/vnd.collection+json; charset=utf8');

    $doc = $this->cjDoc();
    unset($doc['collection']['queries']);
    $doc['collection']['error'] = array(
      'message' => $message
    );

    echo json_encode($doc);
    return true;
  }


  // template for a Collection+JSON document
  function cjDoc() {

    $data = array(
      'name' => "year",
      'value' => ""
    );
    $queries = array(
      "href" => "http://api.fnarg.net/tsunamis",
      "rel" => "search",
      "prompt" => "Enter year in range -100 to current",
      "data" => array($data)
    );
    return array('collection' => 
      array(
        "version"=>"1.0",
        "href"=>"http://api.fnarg.net/tsunamis",
        'queries' => array($queries)
      ),
    );
  }
}
?>
