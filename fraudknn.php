<?php
require_once("/usr/share/php/xapian.php");

$docs = array(
  array(   
      'fraud' => false,
      'price' => 1699, 'desc' => 'toy ninja', 'ship' => 'US'
  ),  
  array(   
      'fraud' => false,
      'price' => 20000, 'desc' => 'TV','ship' => 'US'
  ),
  array(   
      'fraud' => false,
      'price' => 2500, 'desc' => 'cds', 'ship' => 'US'
  ),
  array(   
      'fraud' => true,
      'price' => 20000, 'desc' => 'console', 'ship' => 'CN'
  ),
  array(   
      'fraud' => true,
      'price' => 5000, 'desc' => 'books', 'ship' => 'US'
  ),
  array(   
      'fraud' => true,
      'price' => 15000, 'desc' => 'ipod', 'ship' => 'CN'
  ),    
);

$db = new XapianWritableDatabase("index", Xapian::DB_CREATE_OR_OPEN);
$indexer = new XapianTermGenerator();
$stemmer = new XapianStem("english");
$indexer->set_stemmer($stemmer);

foreach($docs as $key => $doc) {
    $xdoc = new XapianDocument();
    $xdoc->set_data($doc['fraud'] ? "fraud" : "clean");
    $indexer->set_document($xdoc);
    $indexer->index_text($doc['price'] . ' ' . $doc['desc'] . ' ' . $doc['ship']);
    
    $db->add_document($xdoc, $key);
}

$db = null;



