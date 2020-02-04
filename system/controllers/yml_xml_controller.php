<?php

class Yml_XML_Controller {
  public function defaultAction() {
    Load::model(array('Pages','Tree','Catalog'));
    $data['pages'] = Pages::getPagesList();
    $data['trees'] = Database::getRows(get_table('catalog_tree'));  
	$table = get_table('catalog').' as t1 JOIN '.get_table('catalog_categories').' as t2 ON t1.id = t2.id_catalog';
	$data['products'] = Database::getRows($table,'id','asc',3000,'active=1','id');
    header('Content-Type: application/xml');
    Render::layout('yandexmap', $data);
  }
}