<?php

class Raffle_Controller {
  private $_content, $_config;

	public function __construct() {
		$this->_config = Config::getParam('modules->raffle');
		Load::model(array('Raffle','Zakaz','Zakaz_client'));
	
		$this->_content['left'] = Render::view('catalog/razdel');
	}

	public function defaultAction() {

		$totals = Raffle::getTotalRaffle();	
			
		$raffle = Raffle::getRaffle("timestamp", "DESC", $totals);	
			
		$this->_content['content'] = Render::view (
			'raffle/list', Array (
				'items' => $raffle,
				'imagepath' => $this->_config['image']['small']['path']
			)
		);

		Render::layout('page',  $this->_content);
	
	}

	public function detailedAction() {
		
		$item = Raffle::getRaffleByPath(URL::getSegment(3));

		if ($item) {
			
			$this->_content['title'] = $item['title'];
			$this->_content['keywords'] = $item['keywords'];
			$this->_content['description'] = $item['description'];
		
			if (URL::getSegment(4)==1) {
				$date_ot = $item['timestamp'];
				$date_do = $item['timestampend'];
			}
					
			if (URL::getSegment(4)==2) {
				$date_ot = $item['timestamp2'];
				$date_do = $item['timestampend2'];
			}
					
			if (URL::getSegment(4)==3) {
				$date_ot = $item['timestamp3'];
				$date_do = $item['timestampend3'];
			}
					
			if (URL::getSegment(4)==4) {
				$date_ot = $item['timestamp4'];
				$date_do = $item['timestampend4'];
			}		
			
			if (URL::getSegment(4)==5) {
				$date_ot = $item['timestamp'];
				$date_do = $item['timestampend4'];
			}
			
			$zakazs = Zakaz::getZakazsByDate($item['id'],$date_ot,$date_do);
		
			$this->_content['content'] = Render::view (
				'raffle/detailed', Array (
					'item' => $item,
					'zakazs' => @$zakazs,
					'date_ot' => @$date_ot,
					'date_do' => @$date_do,
					'imagepath' => $this->_config['image']
				)
			);
			
			Render::layout('page',  $this->_content);
			
		} else {
			Header("Location: /raffle");
		}
	}
	
}