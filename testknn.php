<?php
include "/usr/share/php/xapian.php";

$test = array(   
      'price' => 10000,
      'desc' => 'TV',
      'ship' => 'CN'
);

$db = new XapianWritableDatabase("index", Xapian::DB_CREATE_OR_OPEN);
$idx = new XapianTermGenerator();
$stemmer = new XapianStem("english");
$idx->set_stemmer($stemmer);

$xdoc = new XapianDocument();
$idx->set_document($xdoc);
$idx->index_text($test['price'] . ' ' . $test['description'] . ' ' . $test['shipping_country']);
$id = $db->add_document($xdoc);

$enquire = new XapianEnquire($db);
$rset = new XapianRSet();
$rset->add_document($id);
$eset = $enquire->get_eset(10, $rset);

$terms = array();
$i = $eset->begin();
while ( !$i->equals($eset->end()) ) {
    $terms[] = $i->get_term();
    $i->next();
}

$q = new XapianQuery(XapianQuery::OP_OR, $terms);
$enquire->set_query($q);
$matches = $enquire->get_mset(0, 4, $rset);

$i = $matches->begin();
while (!$i->equals($matches->end())) {
	if($i->get_document()->get_docid() != $id) {
	    var_dump($i->get_document()->get_data());
	}
	$i->next();
}

$db->delete_document($id);
