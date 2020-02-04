<?php
class Pagination {
  private $_total_rows_in_page, $_current_page, $_pagination;

  public function __construct($total_rows, $rows_on_page, $link_by_side, $url_segment, $base_url, $anchor = '') {
    $current_page = @$_GET['page'];//($url_segment);
	
	foreach($_GET as $index => $val) {
		if (($index != "page") and ($index != "rows_on_page")) {
			if ((!empty($val)) and (!is_array($val))) $anchor .= '&'.$index.'='.@$val;
			if (is_array($val)) {
				foreach($val as $elem) {
					$anchor .= '&'.$index.'[]='.@$elem;	
				}
			}
		
		}
	}
	
    if (!is_numeric($current_page)) {
      $current_page = 1;
    }

    $total_page = ceil($total_rows / $rows_on_page);

    if (($current_page > $total_page) || ($current_page < 1)) {
      $current_page = 1;
    }

    $this->_total_rows_in_page = $rows_on_page;
    $this->_current_page = $current_page;

    if ($total_page < 2) {
      return '';
    }

    $output = '';

    $start = (($current_page - $link_by_side) > 1) ? ($current_page - $link_by_side) : 1;
    $end = (($current_page + $link_by_side) > $total_page) ? $total_page : $current_page + $link_by_side;

    if ($start > 1) {
      $output .= '<a href="?page=1'.$anchor.'">1</a>...';
    }

    for ($i = $start; $i <= $end; $i++) {
      if ($i == $current_page) {
		$output_start = '<a href="?page='.($current_page-1).$anchor.'" class="prev">&nbsp;</a>';
        $output .= '<b>'.$i.'</b> ';
		$output_end = '<a href="?page='.($current_page+1).$anchor.'" class="next">&nbsp;</a>';		
      } else {
        $output .= '<a href="?page='.$i.$anchor.'">'.$i.'</a>';
      }
    }

    if ($end < $total_page) {
      $output .= '...<a href="?page='.$total_page.$anchor.'">'.$total_page.'</a>';
    }

    $this->_pagination = $output_start.$output.$output_end;
  }

  public function getPagination() {
    return $this->_pagination;
  }

  public function getLimit() {
    return (($this->_current_page * $this->_total_rows_in_page) - $this->_total_rows_in_page) . ', ' . $this->_total_rows_in_page;
  }

  public function getLimitas() {
    
	$first_znak = ($this->_current_page * $this->_total_rows_in_page) - $this->_total_rows_in_page;
	if ($first_znak==0) $first_znak = 1;
	
	return ($first_znak) . ' - ' . ((($this->_current_page * $this->_total_rows_in_page) - $this->_total_rows_in_page) + $this->_total_rows_in_page);
  }
  
}