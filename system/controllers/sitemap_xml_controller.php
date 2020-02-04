<?php

class Sitemap_XML_Controller {
  public function defaultAction() {
    Load::model(array('Pages','Tree','Catalog'));
    $data['pages'] = Pages::getPagesList();
    $data['trees'] = Tree::getTrees();    
	$data['products'] = Catalog::getCollections(1,"id","ASC",Catalog::getTotalCollections(1));
    header('Content-Type: application/xml');
    Render::layout('sitemap', $data);
  }
}