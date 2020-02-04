<?php

class Sale_Controller 
{
	private $_content, $_config;

	public function __construct() 
	{
		$this->_config = Config::getParam('modules->akcii');
		$this->_table = get_table('akcii');	
		Load::model('Akcii');
	
		$this->_content['left'] = Render::view('catalog/razdel');
	}

	public function defaultAction() 
	{
		$totals = Database::getCount($this->_table);	
		
		$pagination = new Pagination (
			$totals,
			$this->_config['pagination']['rows_on_page'],
			$this->_config['pagination']['link_by_side'],
			$this->_config['pagination']['url_segment'],
			$this->_config['pagination']['base_url']
			);
		
		$akcii = Database::getRows($this->_table, 'timestamp', 'DESC', $pagination->getLimit(),'`active` = 1');	
			
		$this->_content['content'] = Render::view (
			'akcii/list', Array (
				'items' => $akcii,
				'imagepath' => $this->_config['image']['small']['path'],
				'pagination' => $pagination->getPagination()
			)
		);

		Render::layout('page',  $this->_content);
	}

  public function detailedAction() {
    $item = Akcii::getAkciiByPath(URL::getSegment(3));

    if ($item) {
      $this->_content['title'] = $item['title'];
      $this->_content['keywords'] = $item['keywords'];
      $this->_content['description'] = $item['description'];
    }

     $this->_content['content'] = Render::view (
      'akcii/detailed', Array (
        'item' => $item
      )
    );

    Render::layout('page',  $this->_content);
  }
}