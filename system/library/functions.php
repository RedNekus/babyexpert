<?php

function safe($values) {
  if (is_array($values)) {
    foreach ($values as $key => $value) {
      $values[$key] = safe($value);
    }
  } else if ($values === null) {
    $values = 'NULL';
  } else if (is_bool($values)) {
    $values = $values ? 1 : 0;
  } else if (!is_numeric($values)) {
    $values = mysql_real_escape_string($values);
  }
  return $values;
}

function xls_conv($str = '') {
	$data = iconv('UTF-8', 'Windows-1251//TRANSLIT', $str);
	return $data;
}

function html_chars_conv($data, $key_ignor_list = '', $is_key='') {
  if (is_array($data)) {
    foreach ($data as $key => $val) {
      $data[$key] = html_chars_conv($val, $key_ignor_list, $key);
    }
  } else {
    if (is_array($key_ignor_list) && !in_array($is_key, $key_ignor_list)) {
      $data = htmlspecialchars($data, ENT_QUOTES);
    }
    if (!is_array($key_ignor_list)) {
      $data = htmlspecialchars($data, ENT_QUOTES);
    }
  }
  return $data;
}

function get_ext_file($file) {
  return end(explode(".", $file));
}

function get_image_info($file = NULL) {
  if (!is_file($file))
    return false;
  if (!$data = getimagesize($file) or !$filesize = filesize($file))
    return false;
  $extensions = array(1 => 'gif', 2 => 'jpg',
      3 => 'png', 4 => 'swf',
      5 => 'psd', 6 => 'bmp',
      7 => 'tiff', 8 => 'tiff',
      9 => 'jpc', 10 => 'jp2',
      11 => 'jpx', 12 => 'jb2',
      13 => 'swc', 14 => 'iff',
      15 => 'wbmp', 16 => 'xbmp');

  $result = array('width' => $data[0],
      'height' => $data[1],
      'extension' => $extensions[$data[2]],
      'size' => $filesize,
      'mime' => $data['mime']);
  return $result;
}

function image_resize($img_file, $target_file, $width, $height, $mode = 1) {
  if (!file_exists($img_file))
    return false;
  if (!$source_im_info = @getimagesize($img_file))
    return false;
  $valid_im_types = array(1 => 'gif', 2 => 'jpeg', 3 => 'png');
  $img_open_func = 'imagecreatefrom' . $valid_im_types[$source_im_info[2]];
  $source_im = $img_open_func($img_file);
  $k1 = $width / imagesx($source_im);
  $k2 = $height / imagesy($source_im);
  $k = ($mode == 1) ? (($k1 > $k2) ? $k2 : $k1) : (($k1 < $k2) ? $k2 : $k1);
  $width = intval(imagesx($source_im) * $k);
  $height = intval(imagesy($source_im) * $k);
  $result_im = imagecreatetruecolor($width, $height);
  if (!@imagecopyresampled($result_im, $source_im, 0, 0, 0, 0, $width, $height, $source_im_info[0], $source_im_info[1]))
    return false;
  $img_close_func = 'image' . $valid_im_types[$source_im_info[2]];
  if (!$img_close_func($result_im, $target_file))
    return false;
  imagedestroy($source_im);
  imagedestroy($result_im);
  return true;
}

function mb_wordwrap($str, $len = 50, $break = ' ', $cut=true) {
  if (empty($str))
    return "";

  $pattern = "";
  if (!$cut)
    $pattern = "/(\S{" . $len . "})/u";
  else
    $pattern="/(.{" . $len . "})/u";

  return preg_replace($pattern, "$1" . $break, $str);
}

function get_upload_max_file_size($in_byte = FALSE) {

  function let_to_num($v) { //This function transforms the php.ini notation for numbers (like '2M') to an integer (2*1024*1024 in this case)
    $l = substr($v, -1);
    $ret = substr($v, 0, -1);
    switch (strtoupper($l)) {
      case 'P':
        $ret *= 1024;
      case 'T':
        $ret *= 1024;
      case 'G':
        $ret *= 1024;
      case 'M':
        $ret *= 1024;
      case 'K':
        $ret *= 1024;
        break;
    }
    return $ret;
  }

  $max_upload_size = min(let_to_num(ini_get('post_max_size')), let_to_num(ini_get('upload_max_filesize')));

  if ($in_byte) {
    return $max_upload_size;
  }

  return ($max_upload_size / (1024 * 1024)) . 'MB';
}

function array2json($arr) {
  if (function_exists('json_encode'))
    return json_encode($arr); //Lastest versions of PHP already has this functionality.
  $parts = array();
  $is_list = false;

  //Find out if the given array is a numerical array
  $keys = array_keys($arr);
  $max_length = count($arr) - 1;
  if (($keys[0] == 0) and ($keys[$max_length] == $max_length)) {//See if the first key is 0 and last key is length - 1
    $is_list = true;
    for ($i = 0; $i < count($keys); $i++) { //See if each key correspondes to its position
      if ($i != $keys[$i]) { //A key fails at position check.
        $is_list = false; //It is an associative array.
        break;
      }
    }
  }

  foreach ($arr as $key => $value) {
    if (is_array($value)) { //Custom handling for arrays
      if ($is_list)
        $parts[] = array2json($value); /* :RECURSION: */
      else
        $parts[] = '"' . $key . '":' . array2json($value); /* :RECURSION: */
    } else {
      $str = '';
      if (!$is_list)
        $str = '"' . $key . '":';

      //Custom handling for multiple data types
      if (is_numeric($value))
        $str .= $value; //Numbers
      elseif ($value === false)
        $str .= 'false'; //The booleans
      elseif ($value === true)
        $str .= 'true';
      else
        $str .= '"' . addslashes($value) . '"'; //All other things
        
// :TODO: Is there any more datatype we should be in the lookout for? (Object?)

      $parts[] = $str;
    }
  }
  $json = implode(',', $parts);

  if ($is_list)
    return '[' . $json . ']'; //Return numerical JSON
  return '{' . $json . '}'; //Return associative JSON
}

function translit($str) {
  $tr = Array (
    'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g',  'д' => 'd', 'е' => 'e',
    'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k',
    'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
    'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
    'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '',
    'э' => 'e', 'ю' => 'yu', 'я' => 'ya',

    'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G',  'Д' => 'D', 'Е' => 'E',
    'Ё' => 'YO', 'Ж' => 'ZH', 'З' => 'Z', 'И' => 'I', 'Й' => 'Y', 'К' => 'K',
    'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R',
    'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
    'Ч' => 'CH', 'Ш' => 'SH', 'Щ' => 'SCH', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '',
    'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA'
  );

  return strtr($str, $tr);
}

function create_file_name($name) {
  return preg_replace(Array('/[^a-z0-9\-_.\s]+/iu', '/\s/'), '_', translit($name));
}

function translit_path($name) {
  return preg_replace(Array('/[^a-z0-9\-_.\s]+/iu', '/\s/'), '-', mb_strtolower(translit($name)));
}

function send_header_json() {
  header('Content-Type: application/json; charset=utf-8');
}

function valid_email($address) {
  return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $address)) ? FALSE : TRUE;
}

function getXLS($xls){
	Load::library('php_excel/PHPExcel/IOFactory.php');
	$objPHPExcel = PHPExcel_IOFactory::load($xls);
	$objPHPExcel->setActiveSheetIndex(0);
	$aSheet = $objPHPExcel->getActiveSheet();
		
	$array = array();//этот массив будет содержать массивы содержащие в себе значения ячеек каждой строки
	//получим итератор строки и пройдемся по нему циклом
	foreach($aSheet->getRowIterator() as $row){
		//получим итератор ячеек текущей строки
		$cellIterator = $row->getCellIterator();
		//пройдемся циклом по ячейкам строки
		$item = array();//этот массив будет содержать значения каждой отдельной строки
		foreach($cellIterator as $cell){
			//заносим значения ячеек одной строки в отдельный массив
			array_push($item, iconv('utf-8', 'utf-8', $cell->getCalculatedValue()));
		}
		//заносим массив со значениями ячеек отдельной строки в "общий массв строк"
		array_push($array, $item);
		}
	return $array;
}

function cut_str($str, $len, $end = "...") {
	if (mb_strlen($str, "utf-8") <= $len)
		return $str;
	else
	{
		$str = mb_substr($str, 0, $len, "utf-8");
		//$str = mb_substr($str, 0, mb_strrpos($str, " ", 0, "utf-8"), "utf-8");
		 
		return $str.$end;    
	}
} 
	 	
  
function get_tree($tree, $pid)
{
    $html = '';
 
    foreach ($tree as $row)
    {
        if ($row['pid'] == $pid)
        {
			$clss = ($row['pid'] == 0) ? 'open' : '';
            $html .= '<li class="'.$clss.'">';
			$html .= '	<span class="file">';
			$html .= '		<a href="#" class="linkrel" id="'.$row['id'].'">'.$row['name'].'</a>';
			$html .= '	</span>'.get_tree($tree, $row['id']);
			$html .= '</li>';
        }
    }
 
    return $html ? '<ul id="pid'.$pid.'">' . $html . '</ul>' . "\n" : '';
}  

function get_parents($tree, $id)
{
    $html = '';
 
    foreach ($tree as $row)
    {
		if ($row['id']==$id) {
			$html .= $row['id'].", ".get_parents($tree, $row['pid']);
		}
    }

    return $html;
} 

function get_tree_characteristics($tree) {
    $html = '';
 
    foreach ($tree as $row) {
        $html .= '<li>';
		$html .= '	<span class="file">';
		$html .= '		<a href="#" class="linkrel" id="'.$row['id'].'" rel="'.$row['id'].'">'.$row['name'].'</a>';
		$html .= '	</span>';
		$html .= '</li>';
    }
 
    return $html ? '<ul>' . $html . '</ul>' . "\n" : '';
} 

function get_kassa_tree_by_access($tree, $pid = 0) {
    $html = '';
	$access = get_array_access();
    foreach ($tree as $row) {
		if ($row['pid'] == $pid) {
			$name = (empty($row['name'])) ? '(пусто)' : $row['name'];
			$arr = explode(',',$access['id_kassa']);
			$clss = ($row['pid'] == 0) ? 'open' : '';
			if (in_array($row['id'],$arr)) {
				$html .= '<li class="'.$clss.'">';
				$html .= '	<span class="file">';
				$html .= '		<a href="#" class="linkrel" id="'.$row['id'].'" rel="'.$row['id'].'">'.$name.'</a>';
				$html .= '	</span>'.get_kassa_tree_by_access($tree, $row['id']);
				$html .= '</li>';
			} elseif ($access['id']==1) {
				$html .= '<li class="'.$clss.'">';
				$html .= '	<span class="file">';
				$html .= '		<a href="#" class="linkrel" id="'.$row['id'].'" rel="'.$row['id'].'">'.$name.'</a>';
				$html .= '	</span>'.get_kassa_tree_by_access($tree, $row['id']);
				$html .= '</li>';			
			}
		}
    }
	
    return $html ? '<ul id="pid'.$pid.'">' . $html . '</ul>' . "\n" : '';
}

function get_courier_tree_by_access($trees) {
    $html = '';
	$access = get_array_access();
    foreach ($trees as $tree) {
		if ($tree['couriers_show']==1) {
			$name = (empty($tree['name'])) ? '(пусто)' : $tree['name'];
			$html .= '<li>';
			$html .= '	<span class="file">';
			$html .= '		<a href="#" class="linkrel" data-tip="'.$tree['id'].'">'.$name.'</a>';
			$html .= '	</span>';			
			$couriers  = Database::getRows(get_table('kontragenty'),'name','asc',false,'id_tip='.$tree['id']);
			$html .= '<ul>';
			foreach($couriers as $courier) {	
				$name = (empty($courier['name'])) ? '(пусто)' : $courier['name'];
				if ($courier['id']==$access['id_courier']) {
					$html .= '<li>';
					$html .= '	<span class="file">';
					$html .= '		<a href="#" class="linkrel" id="'.$courier['id'].'" rel="'.$courier['id'].'">'.$name.'</a>';
					$html .= '	</span>';
					$html .= '</li>';
				} elseif ($access['id']==1 or $access['id']==2 or $access['id']==4) {
					$html .= '<li>';
					$html .= '	<span class="file">';
					$html .= '		<a href="#" class="linkrel" id="'.$courier['id'].'" rel="'.$courier['id'].'">'.$name.'</a>';
					$html .= '	</span>';
					$html .= '</li>';		
				}				
				}
				$html .= '</ul>';
				$html .= '</li>';
		}
    }
	
    return $html ? $html : '';
}

function get_tree_by_access($tree, $field) {
    $html = '';
	$access = get_array_access();
	
    foreach ($tree as $row) {
		$name = (empty($row['name'])) ? '(пусто)' : $row['name'];
		$arr = explode(',',$access[$field]);
		if (in_array($row['id'],$arr)) {
			$html .= '<li>';
			$html .= '	<span class="file">';
			$html .= '		<a href="#" class="linkrel" id="'.$row['id'].'" rel="'.$row['id'].'">'.$name.'</a>';
			$html .= '	</span>';
			$html .= '</li>';
		} elseif ($access['id']==1) {
			$html .= '<li>';
			$html .= '	<span class="file">';
			$html .= '		<a href="#" class="linkrel" id="'.$row['id'].'" rel="'.$row['id'].'">'.$name.'</a>';
			$html .= '	</span>';
			$html .= '</li>';			
		}
    }
 
    return $html ? '<ul>' . $html . '</ul>' . "\n" : '';
} 

function get_tree_adminusers($tree)
{
    $html = '';
 
    foreach ($tree as $row)
    {
        $html .= '<li>';
		$html .= '	<span class="file">';
		$html .= '		<a href="#" class="linkrel" id="'.$row['id'].'" rel="'.$row['id'].'">'.$row['login'].'</a>';
		$html .= '	</span>';
		$html .= '</li>';
    }
 
    return $html ? '<ul>' . $html . '</ul>' . "\n" : '';
} 

function getValueBySelect($post_value, $session_value, $default_value) {
	
	if (@$post_value) $result = $post_value;
	else $result = (@$session_value) ? $session_value : $default_value; 
			
	return $result;
}
	
 
function formcreate($id, $id_catalog) {

$char_group = Characteristics_group::getCollections($id);
$charactrform = "";
			
foreach ($char_group as $item) {
	
$charactrform .= '<fieldset>';
				
$charactrform .= '<table class="table-tabs-catalog CharacteristicsTable">';
				
$charactrform .= '<tr><td class="fieldset_name" colspan="2"><b>'.$item['name'].'</b></td></tr>';
				
	foreach (Characteristics::getCollections($item['id']) as $char_item) {
				
		if ($char_item['tip']==1) {
		
			$charvalue = Catalog_characteristics::getCollectionCNI($id_catalog, "text", $char_item['id']);
			
			$form_item = '<input type="text" value="'.@$charvalue['value'].'" name="text['.$char_item['id'].']" />';
						
		}
						
		if ($char_item['tip']==2) {
			
			$charvalue = Catalog_characteristics::getCollectionCNI($id_catalog, "radio", $char_item['id']);
			
			if (@$charvalue['value']==1) {
			$form_item = '<input type="radio" name="radio['.$char_item['id'].']" value="1" checked id="new_yes" /> <label for="new_yes">Есть</label>';
			$form_item .= '<input type="radio" name="radio['.$char_item['id'].']" value="0" id="new_no"  /> <label for="new_no">Нет</label><br/>';
			} else {
			$form_item = '<input type="radio" name="radio['.$char_item['id'].']" value="1" id="new_yes" /> <label for="new_yes">Есть</label>';
			$form_item .= '<input type="radio" name="radio['.$char_item['id'].']" value="0" checked id="new_no"  /> <label for="new_no">Нет</label><br/>';			
			}
													
		}	
						
		if ($char_item['tip']==3) {
						
			$form_item = '<select name="select['.$char_item['id'].']">';
							
				foreach (Characteristics_group_tip::getTipsByIdCharacter($char_item['id']) as $tip_item) {
				
					$charvalue = Catalog_characteristics::getCollectionCNI($id_catalog, "select", $char_item['id']);	
					if (@$charvalue['value']==$tip_item['id']) {				
					$form_item .= 	'<option value="'.$tip_item['id'].'" selected >'.$tip_item['name'].'</option>';
					} else {
					$form_item .= 	'<option value="'.$tip_item['id'].'">'.$tip_item['name'].'</option>';
					}
				}	
							
			$form_item .= '</select>';
		
		}
						
		if ($char_item['tip']==4) {
						
			$form_item = "";
							
			foreach (Characteristics_group_tip::getTipsByIdCharacter($char_item['id']) as $tip_item) {
								
				$charvalue = Catalog_characteristics::getCollectionCNI($id_catalog, "checkbox", $tip_item['id']);	
				
				if (@$charvalue['value']=="on") {				
					$form_item .= 	'<input type="checkbox" name="checkbox['.$tip_item['id'].']" checked id="tmp_'.$tip_item['id'].'"> <label for="tmp_'.$tip_item['id'].'">'.$tip_item['name'].'</label><br/>';
				} else {
					$form_item .= 	'<input type="checkbox" name="checkbox['.$tip_item['id'].']" id="tmp_'.$tip_item['id'].'"> <label for="tmp_'.$tip_item['id'].'">'.$tip_item['name'].'</label><br/>';
				}				
			}
						
		}	
					
	$charactrform .= '<tr>';
	$charactrform .= '<td style="width: 250px; vertical-align: top;">'.$char_item['name'].'</td><td>'.$form_item.'</td>';
	$charactrform .= '</tr>';
				
	}
$charactrform .= '</table>';				
$charactrform .= '</fieldset>';
			
}
		
return $charactrform;
	
}

function get_tree_item($id_catalog) {

	$item = Catalog_category::getCollections($id_catalog);

	if (!empty($item[0]['id_razdel5'])) $tree_item = Tree::getTreeByID($item[0]['id_razdel5']); 
	else if (!empty($item[0]['id_razdel4'])) $tree_item = Tree::getTreeByID($item[0]['id_razdel4']);
	else if (!empty($item[0]['id_razdel3'])) $tree_item = Tree::getTreeByID($item[0]['id_razdel3']);
	else if (!empty($item[0]['id_razdel2'])) $tree_item = Tree::getTreeByID($item[0]['id_razdel2']);
	else if (!empty($item[0]['id_razdel1'])) $tree_item = Tree::getTreeByID($item[0]['id_razdel1']);

	return $tree_item;
	
}

function create_prefix_form($id, $action, $id_catalog) {
	
$items = Prefix::getPrefixByIdCatalog($id);	

$catalog = Catalog::getCollectionByID($id_catalog);	

$prefix = Prefix::getPrefixByID(@$catalog['id_prefix']);
	
	$form_item = '<select name="id_prefix" id="prefix_select">';									
	$flag = FALSE;								
	$form_item .= '<option value="0">-- Выберите --</option>';
	foreach($items as $item) {
	
		if ($action == "edit") {
		
			if (@$catalog['id_prefix'] == $item['id']) {
				$tag = 'selected';
				$flag = TRUE;
			} else {
				$tag = '';
			}
		}
		
		if ($action == "add") $tag = ($item['baza']==1) ? 'selected' : '';
		
		$form_item .= '<option value="'.$item['id'].'" '.$tag.'>'.$item['name'].'</option>';

		
	}	
	if ((isset($prefix['id'])) and (isset($prefix['name']))) {
		if (($flag == FALSE) and ($action == "edit")) 
			$form_item .= '<option value="'.$prefix['id'].'" selected rel="biz">'.$prefix['name'].'</option>';
	}
	$form_item .= '</select>';
	return $form_item;				
						
}

function create_maker_form($post) {
	
$items = Catalog::getIdMakerByIdRazdel($post['id_razdel']);
	
if (@$post['id']) {
	$brand = Brand::getCollectionByID($post['id']);
	$id_maker = $brand['id_maker'];
}
	$form_item = '<select name="id_maker" id="prefix_select">';																
	$form_item .= '<option value="0">-- Выберите --</option>';
	foreach($items as $item) {
			
		$tag = ($item['id'] == @$id_maker) ? 'selected' : '';	
		$form_item .= '<option value="'.$item['id'].'" '.@$tag.'>'.$item['name'].'</option>';
	
	}	
	$form_item .= '</select>';
	return $form_item;				
						
}

function formcreatetosite($id_catalog,$id_char) {

$char_group = Characteristics_group::getCollections($id_char);
$charactrform = "";
			
foreach ($char_group as $item) {
	
$charactrform .= '<tr class="head"><td colspan="2">'.$item['name'].'</td></tr>';
				
	foreach (Characteristics::getCollections($item['id']) as $char_item) {
				
		if ($char_item['tip']==1) {
		
			$charvalue = Catalog_characteristics::getCollectionCNI($id_catalog, "text", $char_item['id']);
			
			$form_item = @$charvalue['value'].' '.@$char_item['sufix'];
						
		}
						
		if ($char_item['tip']==2) {
			
			$charvalue = Catalog_characteristics::getCollectionCNI($id_catalog, "radio", $char_item['id']);
			
			if (@$charvalue['value']==1) { $form_item = 'есть';	} else { $form_item = 'нет'; }
													
		}	
						
		if ($char_item['tip']==3) {

			$form_item = "";
			
			foreach (Characteristics_group_tip::getTipsByIdCharacter($char_item['id']) as $tip_item) {
				
				$charvalue = Catalog_characteristics::getCollectionCNI($id_catalog, "select", $char_item['id']);	
									
				if (@$charvalue['value']==$tip_item['id']) { $form_item .= $tip_item['name'].' '.@$char_item['sufix'];; } 
				
			}	
		
		}
						
		if ($char_item['tip']==4) {
						
			$form_item = "";
							
			foreach (Characteristics_group_tip::getTipsByIdCharacter($char_item['id']) as $tip_item) {
								
				$charvalue = Catalog_characteristics::getCollectionCNI($id_catalog, "checkbox", $tip_item['id']);	
				
				if (@$charvalue['value']=="on") { $form_item .= $tip_item['name'].', '; } 				
			
			}
			
			$form_item = substr($form_item,0,strlen($form_item)-2); 
						
		}	
					
		$charactrform .= '<tr>';
		$charactrform .= '<td>'.$char_item['name'].'</td><td>'.$form_item.'</td>';
		$charactrform .= '</tr>';
				
	}
		
}
		
return $charactrform;
	
}

function getValueFromSql($ses_item_id, $char_item) {

$form_item = "";

	if ($char_item['tip']==1) {
				
		$charvalue = Catalog_characteristics::getCollectionCNI($ses_item_id, "text", $char_item['id']);
					
		$form_item = @$charvalue['value'];
								
	}

	if ($char_item['tip']==2) {
			
		$charvalue = Catalog_characteristics::getCollectionCNI($ses_item_id, "radio", $char_item['id']);
			
		if (@$charvalue['value']==1) { $form_item = 'есть';	} else { $form_item = 'нет'; }
													
	}	

	if ($char_item['tip']==3) {

		$form_item = "";
			
		foreach (Characteristics_group_tip::getTipsByIdCharacter($char_item['id']) as $tip_item) {
				
			$charvalue = Catalog_characteristics::getCollectionCNI($ses_item_id, "select", $char_item['id']);	
									
			if (@$charvalue['value']==$tip_item['id']) { $form_item .= $tip_item['name']; } 
				
		}	
		
	}

	if ($char_item['tip']==4) {
						
		$form_item = "";
							
		foreach (Characteristics_group_tip::getTipsByIdCharacter($char_item['id']) as $tip_item) {
								
			$charvalue = Catalog_characteristics::getCollectionCNI($ses_item_id, "checkbox", $tip_item['id']);	
				
			if (@$charvalue['value']=="on") { $form_item .= $tip_item['name'].', '; } 				
			
		}
		
		$form_item = substr($form_item,0,strlen($form_item)-2);	
		
	}
	
	return $form_item;
}

function insert_image($id_catalog) 
{
	$image = "no_photo.png";
	
	$colors = getColorsByIdCatalog($id_catalog);
	if (!empty($colors)) {
		$images = Database::getRows(get_table('images'),'id','asc',false,"id_catalog=$id_catalog");
		if (!empty($images)) {
			foreach ($images as $img) {
				if ($img['showfirst'] == 1) {
					return $img['image'];	
				}
				$image = $img['image'];
			} 
		}		
		foreach($colors as $color) {
			$id_catalog = $color['id'];
			$images = Database::getRows(get_table('images'),'id','asc',false,"id_catalog=$id_catalog");
			if (!empty($images)) {
				foreach ($images as $img) {
					if ($img['showfirst'] == 1) {
						return $img['image'];	
					}
					$image = $img['image'];
				} 
			}			
		}
	} else {
		$images = Database::getRows(get_table('images'),'id','asc',false,"id_catalog=$id_catalog");
		if (!empty($images)) {
			foreach ($images as $img) {
				if ($img['showfirst'] == 1) {
					return $img['image'];	
				}
				$image = $img['image'];
			} 
		}			
	}
	return $image;
}

function getBasket($items,$imagepath) {
	$i = 1;
	$rt = "";
	$manager = 0;
	$manager = @$_SESSION['user']['manager'];
	if ($manager == 0) $href = '/cart/';
	else $href = '/cart/zakaz_manager/';
	if (@$items) {
		$rt .= '<div class="plist">';
		$rt .= '	<table class="b-fbo-products-list">';
		$rt .= '		<tbody>';
			foreach ($items as $item) {
			
				if(@$item['name']) {
				$total_cena = @$item['kolvo'] * transform_to_currency($item, FALSE);
				$summa = @$total_cena + @$summa;
				$rel = @$item['id_item'];

				$rt .= '<tr data-item-id="'.$rel.'">';
				$rt .= '<td>';
				$rt .= '	<a href="/product/'.@$item['path'].'">'.get_product_name($item).'</a>';
				$rt .= '</td>';
				$rt .= '<td>';
				$rt .= '	<a href="#" class="rm btn-remove" rel="'.$rel.'"></a>';
				$rt .= '</td>';
				$rt .= '</tr>';
				}
	
			}
		$rt .= '		</tbody>';
		$rt .= '	</table>';
		$rt .= '</div>';		
		$rt .= '<div class="total-amount">Сумма заказа: <span id="cm-total-amount">'.format_total_currency($summa).'</span>';
		$rt .= '</div>';
		$rt .= '<ul class="buttons">';
		$rt .= '	<li>';
		$rt .= '		<a href="'.$href.'" class="btn-checkout-sm">Оформить</a>';
		$rt .= '	</li><li>';
		$rt .= '		<a href="/cart/clearcart/" class="btn-reset">Очистить</a>';
		$rt .= '	</li>';
		$rt .= '</ul>';
					
	} else {
		$rt .= 	'<p class="empty-text">Нет товаров!</p>';
	}
	return $rt;
}

function getWishlist($items,$imagepath) {
	$i = 1;
	$rt = "";
	if (@$items) {
		$rt .= '<div class="plist">';
		$rt .= '	<table class="b-fbo-products-list">';
		$rt .= '		<tbody>';
			foreach($items as $key => $elems) {
				foreach($elems as $item) {
					$rel = $item['id_item'];

					$rt .= '<tr>';
					$rt .= '<td>';
					$rt .= '	<a href="/product/'.$item['path'].'">'.get_product_name($item).'</a>';
					$rt .= '</td>';
					$rt .= '<td>';
					$rt .= '	<a href="#" class="rm btn-remove-wish" data-id-char="'.$key.'" rel="'.$rel.'" ></a>';
					$rt .= '</td>';
					$rt .= '</tr>';
				}
	
			}
		$rt .= '</tbody>';
		$rt .= '</table>';
		$rt .= '</div>';
		$rt .= '<ul class="buttons">';
		$rt .= '	<li>';
		$rt .= '		<a href="/cart/wishlist/" class="btn-compare-sm">Просмотреть</a>';
		$rt .= '	</li><li>';
		$rt .= '		<a href="/cart/compare/" class="btn-reset">Сравнить</a>';
		$rt .= '	</li>';
		$rt .= '</ul>';
		$rt .= '<ul class="links">';
		$rt .= '	<li>';
		$rt .= '		<a href="/cart/linkstofriend/" id="linkstofriend">Посоветоваться с другом</a>';
		$rt .= '	</li>';		
		$rt .= '	<li>';
		$rt .= '		<a href="/cart/clearcompare/">Очистить</a>';
		$rt .= '	</li>';
		$rt .= '</ul>';							
	} else {
		$rt .=  '<p class="empty-text">Отложенных товаров нет!</p>';		
	}
	return $rt;
}


function transform_to_currency($item, $format = true, $skidka = true){

	$cur = @$_SESSION['currency'];
	
	$currency = Currency_tree::getTreeByID($item['id_currency']);	
	
	$cena = $item['cena'];
	$cena_blr = $item['cena_blr'];
	
	if ($item['tovar_nedeli']>0 and $skidka) {
		$cena = $item['pw_cena'];
		$cena_blr = $item['pw_cena_blr'];		
	}
	
	$result = $cena;
	
	if (isset($cur)) {
	
		if ($cur=="usd") {

			if ($format) $result = '<span class="usd">'.formatCena($result).'</span> $';

		} 
		
		if ($cur=="byr") {
	
			$result = @$currency['kurs'] * $cena;
			
			if ($format) $result = '<span class="byr">'.formatCena($result).'</span> Br';
	
			if ($cena_blr > 0) {
			
				$result = $cena_blr;

				if ($format) $result = '<span class="byr">'.formatCena($result).'</span> Br';			
			
			}
		
		} 		
	
	} else {

		if ($format) $result = '<span class="usd">'.formatCena($result).'</span> $';
		
	}

	return $result;
	
}


function transform_to_currency_old($item, $format = true, $skidka = true){

	$cur = @$_SESSION['currency'];
	
	$currency = Currency_tree::getTreeByID($item['id_currency']);	
	
	$cena = $item['cena_old'];
	$cena_blr = $item['cena_blr_old'];
	
	if ($item['tovar_nedeli']>0 and $skidka) {
		$cena = $item['pw_cena'];
		$cena_blr = $item['pw_cena_blr'];		
	}
	
	$result = $cena;
	
	if (isset($cur)) {
	
		if ($cur=="usd") {

			if ($format) $result = '<span class="usd">'.formatCena($result).'</span> $';

		} 
		
		if ($cur=="byr") {
	
			$result = @$currency['kurs'] * $cena;
			
			if ($format) $result = '<span class="byr">'.formatCena($result).'</span> Br';
	
			if ($cena_blr > 0) {
			
				$result = $cena_blr;

				if ($format) $result = '<span class="byr">'.formatCena($result).'</span> Br';			
			
			}
		
		} 		
	
	} else {

		if ($format) $result = '<span class="usd">'.formatCena($result).'</span> $';
		
	}

	return $result;
	
}

function transform_to_blr($item, $format = true){
	
	$id_cur = (@$item['id_currency']) ? $item['id_currency'] : 3;
	
	$currency = Currency_tree::getTreeByID($id_cur);
	
	$cena = $item['cena'];
	$cena_blr = $item['cena_blr'];
		
	if ($item['tovar_nedeli']>0) {
		$cena = $item['pw_cena'];
		$cena_blr = $item['pw_cena_blr'];
	}
	
	if (!empty($currency['kurs'])) $kurs = $currency['kurs']; else $kurs = 1;
	
	$result = $kurs * $cena;	
	
	if ($format) $result = '<span class="byr">'.formatCena($result).'</span> б.р.';

	if ($cena_blr > 0) {
	
		$result = $cena_blr;

		if ($format) $result = '<span class="byr">'.formatCena($cena_blr).'</span> б.р.';		
	
	}

	return $result;
	
}

function format_total_currency($total_cena) {
	
	$cur = @$_SESSION['currency'];
	$symbol = '$';
	if ($cur == "byr") $symbol = 'Br';	
	$result = '<span class="'.$cur.'">'.formatCena($total_cena).'</span> '.$symbol;	
	
	return $result;
}

function formatCena($cena) {

	if (@$cena) {
		$result = number_format($cena, 2 , ',', ' ');
	} else {
		$result = "";
	}
	
	return $result;

}
  
function filtr_create($id,$tree_id) {

$char_group = Characteristics_group::getCollections($id);
//$makers = Maker::getMakersForSite("active=1","name","ASC",Maker::getTotalMakers());
$makers = Catalog::getIdMakerByIdRazdel($tree_id);
$osn_char_form = "";
$cena_form = "";
$dop_char_form = "";
$i = 1;
$cena_inc = 0;
	

$osn_char_form .= '<tr>';
$osn_char_form .= '<td>';
$osn_char_form .= '<div class="check-select">';		
$osn_char_form .= '<div class="check-header"><span class="check-status">Производитель</span><span class="check-icon"></span></div>';
$osn_char_form .= '<div class="check-body">';

$items = @$_GET['maker'];	

foreach($makers as $maker) {
	if (isset($items)) $sel_m = (in_array($maker['id'],$items)) ? 'checked' : '';
	else $sel_m = (URL::getSegment(4)==$maker['path']) ? 'checked' : ''; 
	$osn_char_form .= 	'<div class="check-item">';
	$osn_char_form .= 	'<div class="w-checkbox filtr pad">';
	$osn_char_form .= 	'<input id="mk_'.$maker['id'].'" '.@$sel_m.' value="'.$maker['id'].'" type="checkbox" name="maker[]" />';
	$osn_char_form .= 	'</div>';
	$osn_char_form .= 	'<label class="w-checkbox-label" for="mk_'.$maker['id'].'">'.cut_str($maker['name'],26,".").'</label>';
	$osn_char_form .= 	'</div>';
}
$osn_char_form .= '</div>';
$osn_char_form .= '</div>';
$osn_char_form .= '</td>';
$osn_char_form .= '</tr>';	

$osn_char_form .= '
		<tr>
		<td>		
			<select name="row_sorted" class="sort-select">
				<option value="prioritet-DESC" >не сортировать</option>
				<option value="myprice-ASC" >цене начать с дешевых</option>
				<option value="myprice-DESC" >цене начать с дорогих</option>					
			</select>
		</td>
		</tr>';
	
$cena_form .= '<tr><td style="padding-bottom: 15px">';
$cena_form .= '<div class="cena-price-td">';
$cena_form .= '<div><b>Цена (бел.руб.)</b></div>';
$cena_form .= '<div>';
$cena_form .= '<span class="header_show_ot"><div>от</div></span>';
$cena_form .= '<span class="header_show_input"><div><input type="text" class="onlycena" value="'.@$_GET['price_ot'].'" id="amount" name="price_ot" /></div></span>';
$cena_form .= '</div>';
$cena_form .= '<div>';
$cena_form .= '<span class="header_show_do"><div>до</div></span>';
$cena_form .= '<span class="header_show_input"><div><input type="text" class="onlycena" value="'.@$_GET['price_do'].'" id="amount2" name="price_do" /></div></span>';				
$cena_form .= '</div>';
$cena_form .= '</div>';
$cena_form .= '</td></tr>';

$osn_char_form .= $cena_form;			


foreach ($char_group as $item) {
	
	$char_items = Characteristics::getCollectionsSite($item['id']);

	if (!empty($char_items)) {
	
	if ($item['name_vision'] == 1) {	
		$dop_char_form .= '<tr class="dop-form">';
		$dop_char_form .= '<td><div class="header">'.$item['name'].'</div></td>';
		$dop_char_form .= '</tr>';	
	}
	
	foreach ($char_items as $char_item) {
		
		// text
		if ($char_item['tip']==1) {

			$sufix = (!empty($char_item['sufix'])) ? '('.$char_item['sufix'].')' : '';
			if ($char_item['valcharacter']==1) {
			$form_item = '<span class="header_show"><div>'.$char_item['name'].$sufix.'</div></span>';
			$form_item .= '<span class="header_show_do"><div>до</div></span>';
			$form_item .= '<span class="header_show_input"><div><input type="text" class="onlydigit" value="'.@$_GET['text-'.$char_item['id'].'-do'].'" name="text-'.$char_item['id'].'-do" /></div></span>';			
			} else {
				
			$class = (mb_strlen($char_item['name'], "utf-8")<8) ? 'class="header_two"' : 'class="header"';
				
			$form_item = '<span '.$class.'><div>'.$char_item['name'].$sufix.'</div></span>';
			$form_item .= '<span class="header_show_ot"><div>от</div></span>';
			$form_item .= '<span class="header_show_input"><div><input type="text" class="onlydigit" value="'.@$_GET['text-'.$char_item['id'].'-ot'].'" name="text-'.$char_item['id'].'-ot" /></div></span>';
			$form_item .= '<span class="header_show_do"><div>до</div></span>';
			$form_item .= '<span class="header_show_input"><div><input type="text" class="onlydigit" value="'.@$_GET['text-'.$char_item['id'].'-do'].'" name="text-'.$char_item['id'].'-do" /></div></span>';			
			}
	
		}
					
		//radio			
		if ($char_item['tip']==2) {

			$ckd = (@$_GET['radio-'.$char_item['id']]=='on') ? 'checked' : '';
			$form_item = '<div class="w-checkbox filtr">';
			$form_item .= '<input id="rad_'.$char_item['id'].'" style="opacity: 0;" '.$ckd.' type="checkbox" name="radio-'.$char_item['id'].'" />';
			$form_item .= '</div>';			
			$form_item .= '<label class="w-checkbox-label" for="rad_'.$char_item['id'].'">'.$char_item['name'].'</label>';
											
		}	
					
		// checkbox
		/*if ($char_item['tip']==3) {
			$form_item = '<div class="f-select filtr">';			
			$form_item .= '<select name="select-'.$char_item['id'].'">';
			$form_item .= '<option value="0" selected>'.cut_str($char_item['name'],26,".").'</option>';
			foreach (Characteristics_group_tip::getTipsByIdCharacter($char_item['id']) as $tip_item) {	
				$sel = (@$_GET['select-'.$char_item['id']]==$tip_item['id']) ? 'selected' : '';
				$form_item .= 	'<option value="'.$tip_item['id'].'" '.$sel.'>'.cut_str($tip_item['name'],26,".").'</option>';
			}						
			$form_item .= '</select>';
			$form_item .= '</div>';		
		}	*/
		
		if ($char_item['tip']==3) {
			$form_item = '<div class="check-select">';		
			$form_item .= '<div class="check-header"><span class="check-status">'.cut_str($char_item['name'],26,".").'</span><span class="check-icon"></span></div>';
			$form_item .= '<div class="check-body">';
							
			if (isset($_GET['select-'.$char_item['id']])) $items = $_GET['select-'.$char_item['id']];		
			
			foreach (Characteristics_group_tip::getTipsByIdCharacter($char_item['id']) as $tip_item) {	
				if (isset($items)) $sel = (in_array($tip_item['id'],$items)) ? 'checked' : '';
				$form_item .= 	'<div class="check-item">';
				$form_item .= 	'<div class="w-checkbox filtr pad">';
				$form_item .= 	'<input id="chk_'.$tip_item['id'].'" '.@$sel.' value="'.$tip_item['id'].'" type="checkbox" name="select-'.$char_item['id'].'[]" />';
				$form_item .= 	'</div>';
				$form_item .= 	'<label class="w-checkbox-label" for="chk_'.$tip_item['id'].'">'.cut_str($tip_item['name'],26,".").'</label>';
				$form_item .= 	'</div>';
			}
			$form_item .= '</div>';
			$form_item .= '</div>';
		}
		
		// select
		if ($char_item['tip']==4) {
			$form_item = '<div class="check-select">';		
			$form_item .= '<div class="check-header"><span class="check-status">'.cut_str($char_item['name'],26,".").'</span><span class="check-icon"></span></div>';
			$form_item .= '<div class="check-body">';
			
			if (isset($_GET['checkbox-'.$char_item['id']])) $items = $_GET['checkbox-'.$char_item['id']];				
			
			foreach (Characteristics_group_tip::getTipsByIdCharacter($char_item['id']) as $tip_item) {	
				if (isset($items)) $sel = (in_array($tip_item['id'],@$items)) ? 'checked' : '';
				$form_item .= 	'<div class="check-item">';
				$form_item .= 	'<div class="w-checkbox filtr pad">';
				$form_item .= 	'<input id="ind_'.$tip_item['id'].'" '.@$sel.' value="'.$tip_item['id'].'" type="checkbox" name="checkbox-'.$char_item['id'].'[]" />';
				$form_item .= 	'</div>';
				$form_item .= 	'<label class="w-checkbox-label" for="ind_'.$tip_item['id'].'">'.cut_str($tip_item['name'],26,".").'</label>';
				$form_item .= 	'</div>';
			}

			$form_item .= '</div>';
			$form_item .= '</div>';
		}
		
		if ($char_item['tip_search']==1) {
			$osn_char_form .= '<tr>';
			$osn_char_form .= '<td>'.$form_item.'</td>';
			$osn_char_form .= '</tr>';
		}	
		
		if ($char_item['tip_search']==2) {
			$dop_char_form .= '<tr class="dop-form">';
			$dop_char_form .= '<td>'.$form_item.'</td>';
			$dop_char_form .= '</tr>';
		} 
		
			
	}
	
	}
			
}
	
	$show_hide = '<tr><td><div class="dp-text">Дополнительные параметры</div></td></tr>';	
	$show_hide .= '<tr><td><div class="dp-text"><a href="#" class="form-btn show">показать</a> <span class="arr bot"></span></div></td></tr>';	
		
	$result = $osn_char_form.$show_hide.$dop_char_form;	
		
return $result;
	
}
 
function get_sql_by_filtr() {

	$i = 2;
	$table = 'np_catalog_characteristics';
	$zaprosSQL = '';
	$result = '';
	$join = '';
	$zapros = '';	
	$join_tmp = '';
	$zapros_tmp = '';
	
	foreach($_GET as $index => $val) {

		$filtr = explode('-',$index);
		if ((isset($filtr[0])) and (isset($filtr[1]))) {
			
			if ((!empty($val)) and (!is_array($val))) {
				
				$join .= 'LEFT JOIN `np_catalog_characteristics` AS t'.$i.' ON t1.`id_catalog` = t'.$i.'.`id_catalog` ';
				 
				if ( $filtr[0]=="text" and isset($filtr[2])) {
					if ($filtr[2]=='ot') $znak = '>';
					if ($filtr[2]=='do') $znak = '<=';
				} else {
					$znak = '=';
				}
				if ($filtr[0]=="radio") $val = 1;				
				if ($filtr[0]=="checkbox") $val = '"on"';
				$zapros .= 'AND (t'.$i.'.`name` = "'.$filtr[0].'" AND t'.$i.'.`id_input` ='.$filtr[1].' AND t'.$i.'.`value` '.$znak.$val.')';
				$i++;

			}
			
			if (is_array($val)) {
				$zapros_tmp = '';
				$join .= 'LEFT JOIN `np_catalog_characteristics` AS t'.$i.' ON t1.`id_catalog` = t'.$i.'.`id_catalog` ';
				foreach($val as $elem) {	
					if ($filtr[0]=="select")
					$zapros_tmp .= 'or (t'.$i.'.`name` = "'.$filtr[0].'" AND t'.$i.'.`id_input` ='.$filtr[1].' AND t'.$i.'.`value` = '.$elem.')';
					else
					$zapros_tmp .= 'or (t'.$i.'.`name` = "'.$filtr[0].'" AND t'.$i.'.`id_input` ='.$elem.' AND t'.$i.'.`value` = "on")';
				}
				$zapros .= ' AND ('.substr_replace($zapros_tmp, null, 0, 3).')';
				$i++;
			}			
			
		}
		
	}
	
	if (!empty($zapros)) $result = 'SELECT DISTINCT t1.`id_catalog` FROM `'.$table.'` AS t1 '.$join.' WHERE (t1.`id_catalog_char` <> 0) '.$zapros;

	return $result;
} 
 
function filtr_create_to_site($item) {

//$tree_item = get_tree_item($id_catalog);
$id_catalog = $item['id'];
$id_char = $item['id_char'];
$char_group = Characteristics_group::getCollections($id_char);
$charactrform = "";
			
	foreach ($char_group as $item) {
			
		foreach (Characteristics::getCollectionsByShowCatalog($item['id']) as $char_item) {
					
			if ($char_item['tip']==1) {
			
				$charvalue = Catalog_characteristics::getCollectionCNI($id_catalog, "text", $char_item['id']);
				
				if (($char_item['name']!="Длина") and ($char_item['name']!="Ширина") and ($char_item['name']!="Высота")) {	
					$form_item = '<strong>'.$char_item['name'].":</strong> ".@$charvalue['value']." ".@$char_item['sufix'];	
				} else {
					if ($char_item['name']=="Длина") {
						$value_length = @$charvalue['value'].'x';
						$znak_length = "(Дх";
					}
					if ($char_item['name']=="Высота") {
						$value_height = @$charvalue['value'].'x'; 
						$znak_height = "Вх";
					}
					if ($char_item['name']=="Ширина") {
						$value_width = @$charvalue['value']." ".@$char_item['sufix'];
						$znak_width = "Ш)";
					}
					if ((!empty($value_length)) and (!empty($value_height)) and (!empty($value_width))) {
						$charactrform .= "<div><strong>Габаритные размеры (ДхВхШ): </strong>".@$value_length.@$value_height.@$value_width.@$znaki."</div>";
						$value_length = "";
						$value_height = "";
						$value_width = "";
					}
				}			
			}
							
			if ($char_item['tip']==2) {
				
				$charvalue = Catalog_characteristics::getCollectionCNI($id_catalog, "radio", $char_item['id']);
				
				$form_item = '<strong>'.$char_item['name'].':</strong> ';
				
				$form_item .= ((@$charvalue['value']==1) ? 'есть' : 'нет');
														
			}	
							
			if ($char_item['tip']==3) {

				foreach (Characteristics_group_tip::getTipsByIdCharacter($char_item['id']) as $tip_item) {
					
					$charvalue = Catalog_characteristics::getCollectionCNI($id_catalog, "select", $char_item['id']);	
										
					if (@$charvalue['value']==$tip_item['id']) { $form_item = '<strong>'.$char_item['name'].':</strong> '.$tip_item['name'].' '.@$char_item['sufix'];; } 
					
				}	
			
			}
							
			if ($char_item['tip']==4) {

				$form_item = '<strong>'.$char_item['name'].':</strong> ';
				
				foreach (Characteristics_group_tip::getTipsByIdCharacter($char_item['id']) as $tip_item) {
									
					$charvalue = Catalog_characteristics::getCollectionCNI($id_catalog, "checkbox", $tip_item['id']);	
					
					if (@$charvalue['value']=="on") { $form_item .= $tip_item['name'].', '; } 				
				
				}
				
				//$form_item = substr($form_item,0,strlen($form_item)-2); 
							
			}

			if (@$form_item != "") {
				$charactrform .= "<div>".$form_item."</div>";	
				$form_item = "";
			}	
		}
			
	}
		
return $charactrform;
		
}


function get_buttons_catalog($id) {

$form_item = '
<span class="corzina">
	<div class="child-corzina">
		<ul class="buttons">
			<li>
				<form class="form-product-buy" action="/cart/addtocart/" method="post">
					<input type="hidden" name="pid" value="'.$id.'">
					<input type="submit" value="Купить" class="btn">
				</form>								
			</li>									
			<li>
				<form class="form-product-wishlist" action="/cart/addtocompare/" method="post">
					<input type="hidden" name="pid" value="'.$id.'">
					<input type="submit" value="Отложить" class="btn">
				</form>
			</li>										
			<li>
				<form class="form-product-wishlist" action="/cart/addtocompare/" method="post">
					<input type="hidden" name="pid" value="'.$id.'">
					<input type="submit" value="К сравнению" class="btn">
				</form>
			</li>
		</ul>
	</div>								
</span>';

return $form_item;
}


function get_clouds($items) {

$form_item = '
<div class="tag-cloud block">
	<div class="b-page-title">
	<span>Облако брендов</span></div>
	<ul>';
	foreach($items as $item) {
		$form_item .= '<li><a href="/manufacturer/'.$item['path'].'">'.$item['name'].'</a>|</li>';
	}
$form_item .= '</ul>
</div>';

return $form_item;
}

function getPaginationAdmin($count,$limit,$page) {

	$data = array();
	
	// Рассчитаем сколько всего страниц займут данные в БД
	$total_currency = ( $count > 0 && $limit > 0) ? ceil($count/$limit) : 0;

	if ($page > $total_currency) $page=$total_currency;

	$start = $limit*$page - $limit;

	if($start <0) $start = 0;

	$data['page']       = $page;
	$data['total']      = $total_currency;
	$data['records']    = $count;
	$data['limit']    	= $start.', '.$limit;
		
	
	return $data;
	
}

function get_access_form($name) {

	$result = "";
	
	$result .= '<input type="checkbox" name="'.$name.'_add" id="val_'.$name.'_add"  />';
	$result .= '<label for="val_'.$name.'_add">Добавить</label>';
	$result .= '<input type="checkbox" name="'.$name.'_edit" id="val_'.$name.'_edit"  />';
	$result .= '<label for="val_'.$name.'_edit">Редактировать</label>';
	$result .= '<input type="checkbox" name="'.$name.'_del" id="val_'.$name.'_del"  />';
	$result .= '<label for="val_'.$name.'_del">Удалить</label>';
	$result .= '<input type="checkbox" name="'.$name.'_review" id="val_'.$name.'_review"  />';
	$result .= '<label for="val_'.$name.'_review">Просмотр</label>';
	
	return $result;
	
}

function get_array_access() {
	$result = array();
	
	if (isset($_SESSION['isLoggedIn']['id_access'])) {
		$table = get_table('adminaccess');
		$result = Database::getRow($table,$_SESSION['isLoggedIn']['id_access']);
		$id = $_SESSION['isLoggedIn']['id'];
		$adminusers = Database::getRow(get_table('adminusers'),$id);
		$result['id_courier'] = $adminusers['id_courier'];
		$result['id_kassa'] = $adminusers['id_kassa'];
		$result['id_kassa_tree'] = $adminusers['id_kassa_tree'];
	}
	
	return $result;
}

			
function outputCSV($path,$data) {

    $outstream = fopen($path, 'w');

    function __outputCSV(&$vals, $key, $filehandler) {
        fputcsv($filehandler, $vals, ';', '"');
    }

    array_walk($data, '__outputCSV', $outstream);
	
    fclose($outstream);
}

			
function save_category($id_catalog, $json) {

	Catalog_category::clearCategoriesById($id_catalog);
	$decarr = json_decode($json, true);

	foreach($decarr as $item) {
		Catalog_category::addCollection(array(
			'id_catalog' => $id_catalog,
			'id_razdel0' => ((isset($item['id_razdel0'])) ? $item['id_razdel0'] : 0),	
			'id_razdel1' => ((isset($item['id_razdel1'])) ? $item['id_razdel1'] : 0),
			'id_razdel2' => ((isset($item['id_razdel2'])) ? $item['id_razdel2'] : 0),
			'id_razdel3' => ((isset($item['id_razdel3'])) ? $item['id_razdel3'] : 0),
			'id_razdel4' => ((isset($item['id_razdel4'])) ? $item['id_razdel4'] : 0),
			'id_razdel5' => ((isset($item['id_razdel5'])) ? $item['id_razdel5'] : 0)
		));	
	}
				
}

	
function FormToSQLAdd($id_catalog, $post, $name) {
	
	foreach ($post as $index => $value) {
		Catalog_characteristics::addCharacteristics(array(
			'id_catalog' => $id_catalog,
			'name' => $name,
			'value' => htmlspecialchars($value),
			'id_input' => $index
		));	
	}
}	

function getColorsByIdCatalog($id_catalog)
{
	$product = Database::getRow(get_table('catalog'),$id_catalog);
	
	if ($product['vid_complect']==8) {
		$where = "id_catalog = ".$product['id']." and type_complect = 9";
		$complects = Database::getRows(get_table('catalog_complect'),'type_complect','asc',false,$where);
		$ids = '0';
		foreach($complects as $complect) {
			$ids .= ','.$complect['id_product'];
		}
		return Database::getRows(get_table('catalog'),'prioritet','desc',false,'id IN('.$ids.') and no_active_color = 0');
	}

}

function get_count_colors($id_catalog, $format = FALSE) {
	
	$colors = getColorsByIdCatalog($id_catalog);
	
	$count = count(@$colors);

	$razryad = $count;
	
	if ($count > 20) $razryad = $count % 10;
	$text = 'расцветок';
	if ( $razryad == 1 ) $text = "расцветка"; 
	if (( $razryad > 1 ) and ($razryad <= 4)) $text = "расцветки"; 

	$result = $count.' '.$text;
	
	if ($count == 0) $result = "";
	
	if ($format) {
		return $result;
	} else {
		echo $result; 
	}
}


function send_sms($text,$telefon) {

	$ch = curl_init(); 			//инициализируем cURL
	 
	$url = "http://cp.smsp.by"; 	// задается URL сервиса 
				
	$timeout = 15;  			// задаем таймаут ответа с секундах
	
	$func = 'msg_send'; 		//задаем имя вызываемой функции. В данном случае - msg_send_bulk
				 
		//формируем пакет сообщений
		$package = array(
			'recipients' => $telefon,
			'message' => $text,
			'sender' => 'BABYEXPERT',
			'urgent' => true,
			'r'=>'api/'.$func,         			//задаем вызываемую функцию
			'user'=>'office@babyexpert.by',   	//логин пользователя
			'apikey'=>'phlZBoIfgo'          	//API-key			
		); 
								
		//задаем параметры CURL
		curl_setopt_array($ch, array(
			CURLOPT_URL => $url,
			CURLOPT_FAILONERROR => 1,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_TIMEOUT => $timeout,
			CURLOPT_CONNECTTIMEOUT => 0,
			CURLOPT_POST => 1,  				//отправляем методом POST - только так можно отправить большой пакет
			CURLOPT_POSTFIELDS => $package   //массив сообщений
		));
		 
		//получаем ответ в переменную
		$result = curl_exec($ch);
			
		if($result) {
		
			//ответ приходит в виде строки формата JSON. Декодируем его в объектное представление PHP		
			$result = json_decode($result);	
			
		} else {
		
			$result = 'Ошибка доступа к сервису !';
		
		}
		
		curl_close($ch);
		
	return $result;
}

		
function rand_str($length =10, $chars = '1234567890abcdefghjklmnopqrstuvwzyxABCDEFGHJKLMNOPQRSTUVWXYZ1234567890') {
	// получаем длину строки символов
	$chars_length = (strlen($chars) - 1);

	// Итак, строчка начинается
	$string = $chars{rand(0, $chars_length)};
	
	// Генерируем
	for ($i = 1; $i < $length; $i = strlen($string))  {
		// Берем случайный элемент из набора символов
		$r = $chars{rand(0, $chars_length)};
		
		// Убеждаемся, что соседние символы не совпадают.
		if ($r != $string{$i - 1}) $string .=  $r;
	}
	
	// Вуаля!
	return $string;
}	


function save_price($action,$config) {
				
	$list = array();
	$params = array();
	$host = $_SERVER['HTTP_HOST'];
	$arr_nalichie = array(2,4,5);
	
	$params['tip_catalog'] = 1;
	$params['sort'] = 'id-asc';
	$count = Catalog::getPodbor($params, "", FALSE);
	$collections = Catalog::getPodbor($params, $count, TRUE);

	if ($action == "yandex" ) $id_tree = 6;	
	if ($action == "tcsv" ) $id_tree = 5;	
	if ($action == "k1" ) $id_tree = 4;
	if ($action == "migom" ) $id_tree = 3;
	if ($action == "pokupay" ) $id_tree = 2;
	if ($action == "onliner" ) $id_tree = 1;

	$portals = Database::getRows(get_table('connection'),'id','asc',false,$id_tree);
	
	if ($action == "k1") {
		array_push($list,array(
			xls_conv('Название'),
			xls_conv('Цена'),
			xls_conv('Ссылка на страницу товара'),
			xls_conv('Ссылка на картинку товара'),
			xls_conv('Категория товара'),
			xls_conv('Статус')
			));		

		foreach($collections as $item) {
			if (transform_to_blr($item,false)>0) {
				$name = xls_conv(get_product_name($item));	
				$name = name_convert($name,$portals,$item['id_maker']);
				
				$razdel = Tree::getTreeByID($item['id_razdel1']);	
				if (@$razdel['id']==255) {
					$razdel_tmp = Catalog_category::getCollections($item['id']);
					$razdel = Tree::getTreeByID($razdel_tmp[0]['id_razdel1']);
				} 			
				if (@$razdel['id']==20) {
					$razdel_tmp = Catalog_category::getCollections($item['id']);
					$razdel = Tree::getTreeByID($razdel_tmp[0]['id_razdel2']);
				} 
				$r_name = xls_conv(@$razdel['name']);
				
				$nalichie = xls_conv(get_status_to_1k(@$item['status']));
				if (!in_array($item['status'], $arr_nalichie)) {
					array_push($list,array(
							@$name,
							transform_to_blr($item,false),
							"http://".$host."/product/".@$item['path'],
							"http://".$host.$config.insert_image($item['id']),
							@$r_name,
							$nalichie
					));
				}		
			}		
		}
	}	
	
	if ($action == "migom") {
		array_push($list,array(
			xls_conv('Название'),
			xls_conv('Цена'),
			xls_conv('Ссылка на страницу товара'),
			xls_conv('Ссылка на картинку товара'),
			xls_conv('Примечание'),
			xls_conv('Категория товара'),
			xls_conv('Наличие'),
			));
		foreach($collections as $item) {
			if (transform_to_blr($item,false)>0) {
					
				$razdel = Tree::getTreeByID($item['id_razdel1']);		
				$r_name = xls_conv(@$razdel['name']);	
				$nalichie = xls_conv(get_status_to_onliner(@$item['status']));
				$colors = array();
				$colors = getColorsByIdCatalog($item['id']);
				
				$name = xls_conv(get_product_name($item, true));
				$name = name_convert($name,$portals,$item['id_maker']);
				if (!empty($colors)) {
					foreach($colors as $color) {
						
						$color_name = xls_conv(' '.$color['name']);
						if (!in_array($item['status'], $arr_nalichie)) {
							array_push($list,array(
									$name.@$color_name,
									transform_to_blr($item,false),
									"http://".$host."/product/".$item['path'],
									"http://".$host.$config.@$color['image'],
									'',
									$r_name,
									$nalichie
							));	
						}		
					
					}
				} else {
					if (!in_array($item['status'], $arr_nalichie)) {
						array_push($list,array(
								$name,
								transform_to_blr($item,false),
								"http://".$host."/product/".$item['path'],
								"http://".$host.$config.insert_image($item['id']),
								'',
								$r_name,
								$nalichie
						));
					}			
				}
			}
		}
	}	
	
	if ($action == "pokupay") {
		array_push($list,array(
			xls_conv('Описание'),		
			xls_conv('Название'),
			xls_conv('Цена'),
			xls_conv('Валюта'),		
			xls_conv('Наличие'),			
			xls_conv('Ссылка на страницу товара'),
			));

		foreach($collections as $item) {

			if (transform_to_blr($item,false)>0) {
				$razdel = Tree::getTreeByID($item['id_razdel1']);		
				$r_name = xls_conv(@$razdel['name']);	
				$nalichie = xls_conv(get_status_to_pokupay(@$item['status']));	

				$name = xls_conv(get_product_name($item));
				$name = name_convert($name,$portals,$item['id_maker']);
				if (!in_array($item['status'], $arr_nalichie)) {
					array_push($list,array(
							$r_name,			
							$name,
							$item['cena'],
							'y.e.',
							$nalichie,
							"http://".$host."/product/".$item['path']					
					));
				}
			}
		}
		
	}
		
	if ($action == "tcsv") {
		array_push($list,array(
			xls_conv('Бренд'),		
			xls_conv('Название'),
			xls_conv('Цена в у.е.'),
			xls_conv('Цена в бел.руб.'),		
			));

		foreach($collections as $item) {
			if (transform_to_blr($item,false)>0) {
				$maker = Maker::getMakerByID($item['id_maker']);		

				$maker_name =  xls_conv(@$maker['name']);
				$name = xls_conv(name_convert($item['name'],$portals,$item['id_maker']));
				array_push($list,array(
						$maker_name,			
						$name,
						$item['cena'],
						transform_to_blr($item,false)
						));
			}
		}
		
	}
	
	if ($action == "onliner") {

		foreach($collections as $item) {
			
			if (transform_to_blr($item,false)>0) {
			
				$maker = Maker::getMakerByID($item['id_maker']);
					
				$razdel = Tree::getTreeByID($item['id_razdel1']);	
				if (@$razdel['id']==255) {
					$razdel_tmp = Catalog_category::getCollections($item['id']);
					$razdel = Tree::getTreeByID($razdel_tmp[0]['id_razdel1']);
				} 			
				if (in_array(@$razdel['id'],array(20,17,8))) {
					$razdel_tmp = Catalog_category::getCollections($item['id']);
					$razdel = Tree::getTreeByID($razdel_tmp[0]['id_razdel2']);
				} 
				$r_name = razdel_convert(@$razdel['name']);
					
				$arr = array(
						'Детские велосипеды',
						'Детские коляски',
						'Детские кроватки',
						'Детские электромобили',
						'Автокресла',
						'Стульчики для кормления',
						'Радионяни и видеоняни',
						'Подогреватели и стерилизаторы',
						'Детские манежи',
						'Детские комоды',
						'Детские столы и парты'
				);	
					
				if (in_array($r_name,$arr)) {	
					$comment = xls_conv(get_comment_by_razdel($r_name));	
					$r_name = xls_conv($r_name);
					$nalichie = xls_conv(get_status_to_onliner(@$item['status']));	
					$name = xls_conv(name_convert($item['name'],$portals,$item['id_maker']));
					$maker_name = xls_conv(maker_convert(@$maker['name']));
					
					$manufacturer = Database::getRow(get_table('manufacturer'),@$maker['id_manufacturer']);
					$importer = Database::getRow(get_table('importer'),@$maker['id_importer']);
					
					if (!empty($item['importer'])) $importer_name = xls_conv(@$item['importer']);
					else $importer_name = xls_conv(@$importer['name']);
					
					if (!empty($item['serv_centr'])) $serv_centr = xls_conv($item['serv_centr']);
					else $serv_centr = xls_conv(@$importer['serv_centr']);
					
					if (!empty($item['short_description'])) $short_description = xls_conv($item['short_description']);
					else $short_description = xls_conv($razdel['description_app']);
					
					if ($item['cost_dostavka'] > 0) $cost_dostavka = $item['cost_dostavka'];
					else $cost_dostavka = $razdel['cost_dostavka'];			
				
					$colors = array();
					$colors = getColorsByIdCatalog($item['id']);						
					if (!empty($colors)) {
						foreach($colors as $color) {
							
							if (!in_array($color['status'], $arr_nalichie)) {
								$name = xls_conv(name_convert($color['name'], $portals, $color['id_maker']));
								$srok_city = (!empty($color['srok_city']) ? $color['srok_city'] : 2);
								$srok_country = (!empty($color['srok_country']) ? $color['srok_country'] : 5);
								array_push($list, array(
									$r_name,							/*	Название раздела 		*/
									@$maker_name,						/*	Производитель	  		*/
									$name,								/*  Модель    				*/
									'',									/*  Заводской артикул    	*/
									$color['id'],						/*  id предложения  		*/
									transform_to_blr($color,false),		/*  Цена 					*/
									'BYN',								/*	Валюта					*/	
									@$short_description,				/*	Комментарий продавца	*/
									xls_conv(@$manufacturer['name']),	/*	изготовитель			*/
									$importer_name,						/*	импортер				*/
									@$serv_centr,						/*	сервисные центры		*/
									'',									/*	Гарантия (месяцев)		*/
									$srok_city,							/*	доставка по городу, дней		*/
									0,									/*	доставка по городу, стоимость	*/
									$srok_country,						/*	доставка по стране, дней		*/
									@$cost_dostavka,					/*	доставка по стране, стоимость	*/
									'',									/*	Срок службы (месяцев)		*/
									xls_conv('нет'),					/*	Только для юрлиц(Да,нет)	*/
									xls_conv('нет'),					/*	Кредит (Да,нет)				*/
									'',									/*	Доставка					*/
									$nalichie,							/*	Наличие						*/							
								));
							}
						}
					} else {						
						if (!in_array($item['status'], $arr_nalichie)) {	
							array_push($list,array(
								$r_name,							/*	Название раздела 		*/
								@$maker_name,						/*	Производитель	  		*/
								$name,								/*  Модель    				*/
								'',									/*  Заводской артикул    	*/
								$item['id'],						/*  id предложения  		*/
								transform_to_blr($item,false),		/*  Цена 					*/
								'BYN',								/*	Валюта					*/	
								@$short_description,				/*	Комментарий продавца	*/
								xls_conv(@$manufacturer['name']),	/*	изготовитель			*/
								$importer_name,						/*	импортер				*/
								@$serv_centr,						/*	сервисные центры		*/
								'',									/*	Гарантия (месяцев)		*/
								$item['srok_city'],					/*	доставка по городу, дней		*/
								0,									/*	доставка по городу, стоимость	*/
								$item['srok_country'],				/*	доставка по стране, дней		*/
								@$cost_dostavka,					/*	доставка по стране, стоимость	*/
								'',									/*	Срок службы (месяцев)		*/
								xls_conv('нет'),					/*	Только для юрлиц(Да,нет)	*/
								xls_conv('нет'),					/*	Кредит (Да,нет)				*/
								'',									/*	Доставка					*/
								$nalichie,							/*	Наличие						*/							
							));		
						}
					}
				}
		
			}
	
		}
		
	}
	
	if ($action == "yandex") {

		foreach($collections as $item) {
			
			if (transform_to_blr($item,false)>0) {
			
				$maker = Maker::getMakerByID($item['id_maker']);
					
				$razdel = Tree::getTreeByID($item['id_razdel1']);	
				if (@$razdel['id']==255) {
					$razdel_tmp = Catalog_category::getCollections($item['id']);
					$razdel = Tree::getTreeByID($razdel_tmp[0]['id_razdel1']);
				} 			
				if (in_array(@$razdel['id'],array(20,17,8))) {
					$razdel_tmp = Catalog_category::getCollections($item['id']);
					$razdel = Tree::getTreeByID($razdel_tmp[0]['id_razdel2']);
				} 
				$r_name = razdel_convert(@$razdel['name']);

				$comment = xls_conv(get_comment_by_razdel($r_name));	
				$r_name = xls_conv($r_name);
				$nalichie = xls_conv(get_status_to_onliner(@$item['status']));	
				$name = xls_conv(name_convert($item['name'],$portals,$item['id_maker']));
				$maker_name = xls_conv(maker_convert(@$maker['name']));
				$country_maker = xls_conv(@$maker['country']);
				
				$manufacturer = Database::getRow(get_table('manufacturer'),@$maker['id_manufacturer']);
				$importer = Database::getRow(get_table('importer'),@$maker['id_importer']);
				
				if (!empty($item['importer'])) $importer_name = xls_conv(@$item['importer']);
				else $importer_name = xls_conv(@$importer['name']);
				
				if (!empty($item['serv_centr'])) $serv_centr = xls_conv($item['serv_centr']);
				else $serv_centr = xls_conv(@$importer['serv_centr']);
				
				if (!empty($item['full_description'])) $full_description = xls_conv($item['short_description']);
				else $full_description = xls_conv(@$razdel['description_app']);
				
				if ($item['cost_dostavka'] > 0) $cost_dostavka = @$item['cost_dostavka'];
				else $cost_dostavka = @$razdel['cost_dostavka'];			
			
				$colors = array();
				$colors = getColorsByIdCatalog($item['id']);						
				if (!empty($colors)) {
					foreach($colors as $color) {
						
						if (!in_array($color['status'], $arr_nalichie)) {
							$name = xls_conv(name_convert($color['name'], $portals, $color['id_maker']));
							$srok_city = (!empty($color['srok_city']) ? $color['srok_city'] : 2);
							$srok_country = (!empty($color['srok_country']) ? $color['srok_country'] : 5);
							array_push($list, array(
								$color['id'],									/*  id предложения  		*/
								$nalichie,										/*	Наличие					*/	
								xls_conv('Есть'),								/*	Доставка				*/
								0,												/*	Стоимость доставки		*/
								$srok_city,										/*	доставка по городу, дней*/
								xls_conv('Есть'),								/*	Самовывоз				*/
								xls_conv('Нельзя'),								/*	Купить в магазине без заказа*/
								"http://".$host."/product/" . $color['path'],
								@$maker_name,									/*	Производитель	  		*/
								$name,											/*  Название				*/
								$r_name,										/*  Категория    			*/
								transform_to_blr($color,false),					/*  Цена 					*/
								transform_to_blr($color,false),					/*  Цена без скидки			*/
								'BYN',											/*	Валюта					*/	
								"http://".$host.$config.insert_image($color['id']),
								@$full_description,								/*	Описание				*/
								"",												/*	Характеристики			*/
								"",												/*	Условия продажи			*/
								xls_conv('Есть'),								/*	Гарантия производителя	*/
								$country_maker,									/*	Страна происхождения	*/
							));
						}
					}
				} else {						
					if (!in_array($item['status'], $arr_nalichie)) {	
						array_push($list,array(
								$item['id'],									/*  id предложения  		*/
								$nalichie,										/*	Наличие					*/	
								xls_conv('Есть'),								/*	Доставка				*/
								0,												/*	Стоимость доставки		*/
								$srok_city,										/*	доставка по городу, дней*/
								xls_conv('Есть'),								/*	Самовывоз				*/
								xls_conv('Нельзя'),								/*	Купить в магазине без заказа*/
								"http://".$host."/product/" . $item['path'],
								@$maker_name,									/*	Производитель	  		*/
								$name,											/*  Название				*/
								$r_name,										/*  Категория    			*/
								transform_to_blr($item,false),					/*  Цена 					*/
								transform_to_blr($item,false),					/*  Цена без скидки			*/
								'BYN',											/*	Валюта					*/	
								"http://".$host.$config.insert_image($item['id']),
								@$full_description,								/*	Описание				*/
								"",												/*	Характеристики			*/
								"",												/*	Условия продажи			*/
								xls_conv('Есть'),								/*	Гарантия производителя	*/
								$country_maker,									/*	Страна происхождения	*/				
						));		
					}
				}

			}
	
		}
		
	}
	
	outputCSV('assets/files2/'.$action.'.csv',$list);
	$url = "http://".$host."/assets/files2/".$action.".csv";
	echo json_encode('Прайс '.$action.' обновлен! <br/><a href="'.$url.'">Ссылка</a> для скачивания. <br/>'.$url);					
}


function copy_table_to_catalog($id_catalog,$color_name = '') {

	$tbl_catalog = get_table('catalog');
	$tbl_catalog_lng = get_table_lng('catalog');

	// Таблица np_catalog
	$catalog_item = Database::getRow($tbl_catalog,$id_catalog);

	if (!empty($color_name)) {
		$name_product = $catalog_item['name'].' '.$color_name;
		$path_product = translit_path($name_product);
	} else {
		$name_product = $catalog_item['name'];
		$path_product = '';
	}	
	
		$data = array(
			'title' => $catalog_item['title'],
			'keywords' => $catalog_item['keywords'],
			'description' => $catalog_item['description'],		  
			'name' => $name_product,			  
			'cena' => $catalog_item['cena'],		  
			'id_currency' => $catalog_item['id_currency'],		  
			'cena_blr' => $catalog_item['cena_blr'],
			'pw_cena' => $catalog_item['pw_cena'],
			'pw_cena_blr' => $catalog_item['pw_cena_blr'],			
			'new' => $catalog_item['new'],
			'hit' => $catalog_item['hit'],
			'spec_pred' => $catalog_item['spec_pred'], 		  		  
			'active' => 0,
			'tovar_nedeli' => $catalog_item['tovar_nedeli'],
			'expert' => $catalog_item['expert'],
			'raffle' => $catalog_item['raffle'],
			'prioritet' => $catalog_item['prioritet'],
			'short_description' => $catalog_item['short_description'],
			'full_description' => $catalog_item['full_description'],
			'path' => $path_product,
			'id_maker' => $catalog_item['id_maker'],			
			'instructions' => $catalog_item['instructions'],
			'pohozhie' => $catalog_item['pohozhie'],
			'soput' => $catalog_item['soput'],
			'podarok' => $catalog_item['podarok'],
			'id_prefix' => $catalog_item['id_prefix'],
			'id_char' => $catalog_item['id_char'],
			'vid_complect' => 8,
			'status' => 6,
			'video_url' => $catalog_item['video_url']
		);	
		
		Database::insert($tbl_catalog,$data);
			
	$id_last_catalog = Catalog::getLastId();
		
	// Таблица np_catalog_language
	$catalog_lng_item = Database::getRow($tbl_catalog_lng,$id_catalog,'id_catalog_lng');	

	if (!empty($catalog_lng_item)) {
	
		$data_lng = array(
			'id_catalog_lng' => $id_last_catalog,
			'id_language' => 2,
			'title_lng' => $catalog_lng_item['title_lng'],
			'keywords_lng' => $catalog_lng_item['keywords_lng'],
			'description_lng' => $catalog_lng_item['description_lng'],		  
			'name_lng' => "",			  
			'short_description_lng' => $catalog_lng_item['short_description_lng'],
			'full_description_lng' => $catalog_lng_item['full_description_lng'],			
			'instructions_lng' => $catalog_lng_item['instructions_lng']
		);
		
		Database::insert($tbl_catalog_lng,$data_lng);
		
	}
	// Таблица np_catalog_category
	$catalog_category_items = Catalog_category::getCollections($id_catalog);	
		
		foreach($catalog_category_items as $item) {
			Catalog_category::addCollection(array(
				'id_catalog' => $id_last_catalog,
				'id_razdel0' => $item['id_razdel0'],	
				'id_razdel1' => $item['id_razdel1'],
				'id_razdel2' => $item['id_razdel2'],
				'id_razdel3' => $item['id_razdel3'],
				'id_razdel4' => $item['id_razdel4'],
				'id_razdel5' => $item['id_razdel5']
			));		
		}	
				
	// Таблица np_catalog_images
	/*$catalog_images_items = Images::getImagesByIdCatalog($id_catalog);		
		
		foreach($catalog_images_items as $item) {
			Images::addImages(array(
				'id_catalog' => $id_last_catalog,
				'image' => $item['image'],
				'description' => $item['description'],
				'showfirst' => $item['showfirst']
			));
			$image = Images_language::getImagesItem($item['id']);
			$last_id = Images::getLastId();
			Images_language::addImages(array(
				'id_catalog_lng' => $last_id,
				'id_language' => 2,
				'description_lng' => $image['description_lng']	
			));			
		
		}	*/		
	
	$catalog_char_items = Catalog_characteristics::getCollectionsByIdCatalog($id_catalog);
	
		foreach($catalog_char_items as $item) {
			Catalog_characteristics::addCharacteristics(array(
				'id_catalog' => $id_last_catalog,
				'name' => $item['name'],
				'value' => $item['value'],
				'id_input' => $item['id_input']
			));			
		}
		
	return $id_last_catalog;	
	
}


function get_zakaz_product_table($items, $skidka) {

	$host = $_SERVER['HTTP_HOST'];
	$kolvo = 0; $sum_usd = 0; $sum_blr = 0;
	
	$html = '<table class="table-tabs-content">';
			
	foreach($items as $item) {
		
		if (isset($item['name'])) {
			$kolvo += $item['kolvo'];
			$sum_usd += $item['kolvo'] * $item['cena'];	
			$sum_blr += $item['kolvo'] * $item['cena_blr'];			
		}
	
	}
	$html .= '<tr>';
	$html .= '<td style="width: 338px;">Итого</td>';
	$html .= '<td style="width: 32px; text-align: center;">'.$kolvo.'</td>';
	$html .= '<td style="width: 54px; text-align: center;">'.formatCena($sum_usd).'</td>';
	$html .= '<td style="width: 70px; text-align: center;">'.formatCena($sum_blr).'</td>';
	$html .= '<td>&nbsp;</td>';
	$html .= '<td>&nbsp;</td>';	
	$html .= '</tr>';	
	
	$curs = Currency_tree::getTreeByID(3);
	
	if (isset($skidka['skidka_procent']) and $skidka['skidka_procent']!=0) {
		$sum_usd -= $sum_usd / 100 * $skidka['skidka_procent'];
		$sum_blr -= $sum_blr / 100 * $skidka['skidka_procent'];
	}
	
	if (isset($skidka['skidka_cena']) and $skidka['skidka_cena']!=0) {
		$sum_usd -= $skidka['skidka_cena']; 
		
		$sum_blr -= $skidka['skidka_cena'] * $curs['kurs']; 
	}	
	
	if (isset($skidka['doplata']) and $skidka['doplata']!=0) {
		$sum_usd += $skidka['doplata'];
		$sum_blr += $skidka['doplata'] * $curs['kurs'];
	}		
	
	if (isset($skidka['cena_dostavka']) and $skidka['cena_dostavka']!=0) {
		$sum_usd += $skidka['cena_dostavka'];
		$sum_blr += $skidka['cena_dostavka'] * $curs['kurs'];
	}		

	$html .= '<tr>';
	$html .= '<td style="width: 338px;">Итого со скидкой</td>';
	$html .= '<td style="width: 32px; text-align: center;">'.$kolvo.'</td>';
	$html .= '<td style="width: 54px; text-align: center;">'.formatCena($sum_usd).'</td>';
	$html .= '<td style="width: 70px; text-align: center;">'.formatCena($sum_blr).'</td>';
	$html .= '<td>&nbsp;</td>';
	$html .= '<td>&nbsp;</td>';	
	$html .= '</tr>';
	
	$html .= '</table>';
	
	return $html;

}

function raffle_create($id_catalog) {

	$raffles = Raffle::getRaffle('id','desc',Raffle::getTotalRaffle());
	$html = '<table class="table-tabs-catalog">';
	$html .= '<tbody>';
	$html .= '<tr>';
	$html .= '<td>';
	$html .= '<input type="radio" name="raffle" id="g_0" checked value="0">';
	$html .= '<label class="cur" for="g_0">Нет</label>';
	$html .= '</td>';
	$html .= '</tr>';	
	$catalog = Catalog::getCollectionByID($id_catalog);
	
	foreach($raffles as $item) {
	
		$checked = (@$catalog['raffle']==$item['id']) ? 'checked' : '';
		$html .= '<tr>';
		$html .= '<td>';
		$html .= '<input type="radio" name="raffle" id="g_'.$item['id'].'" '.$checked.' value="'.$item['id'].'">';
		$html .= '<label class="cur" for="g_'.$item['id'].'">'.$item['name'].'</label>';
		$html .= '</td>';
		$html .= '</tr>';
	
	}
	$html .= '</tbody>';
	$html .= '</table>';
	return $html;
}

function create_clients_win_table($raffle,$variant) {

	if ($variant==1) {
		$date_ot = $raffle['timestamp'];
		$date_do = $raffle['timestampend'];
	}
			
	if ($variant==2) {
		$date_ot = $raffle['timestamp2'];
		$date_do = $raffle['timestampend2'];
	}
			
	if ($variant==3) {
		$date_ot = $raffle['timestamp3'];
		$date_do = $raffle['timestampend3'];
	}
			
	if ($variant==4) {
		$date_ot = $raffle['timestamp4'];
		$date_do = $raffle['timestampend4'];
	}		
	
	if ($variant==5) {
		$date_ot = $raffle['timestamp'];
		$date_do = $raffle['timestampend4'];
	}
		
	$zakazs = Zakaz::getZakazsByDate($raffle['id'],$date_ot,$date_do);
		
	$html = '';
	$html .= '<table class="table-tabs-content raffle" cellspacing=0>';
	$html .= '	<tbody>';
	$html .= '		<tr>';
	$html .= '			<td><input type="text" class="input_winer ib-90" name="input_winer" /></td>';	
	$html .= '			<td colspan=3>';
	$html .= '				<h3><a href="#" class="click_random">Выбрать победителя</a></h3>';
	$html .= '			</td>';
	$html .= '		</tr>';
	$i = 1;
	if (@$zakazs) {
		foreach($zakazs as $item) {
			$fl = true;
			if (($variant==5) and ($item['winner']!=0)) {
				$fl = false;
			}
			if ($fl) {
				if ($item['winner']==0) {
					$act = 'noactive';
				} else {
					$act = 'active';
					$nomer = $i;
					$id = $item['id'];
				} 
				$client = Zakaz_client::getClientByID($item['id_client']);
				$phones = explode(",", $client['phone']);
				$phone = $phones[0];
				$html .= '<tr>';
				$html .= '  <td style="width: 10%; text-align: center;">'.$i.'</td>';
				$html .= '	<td style="width: 20%">';
				$html .= '		<div>'.$client['firstname'].'</div>';				
				$html .= '	</td>';			
				$html .= '	<td style="width: 40%">';
				$html .= '		<div>тел: '.$phone.'</div>';				
				$html .= '	</td>';			
				$html .= '	<td style="width: 30%">';
				$html .= '		<div><a href="#" data-tur="'.$variant.'" class="raffle_code '.$act.'" rel="'.$phone.'" id="'.$item['id'].'">Оповестить по смс</a></div>';				
				$html .= '	</td>';
				$html .= '</tr>';
				$i++;
			}
		}
	}
	$html .= '	</tbody>';
	$html .= '</table>';
	//$html .= '<input type="hidden" id="nomer" value="'.@$nomer.'" />';
	//$html .= '<input type="hidden" id="val_winner" value="'.@$id.'" />';
	return $html;
}


function get_pred_search($items,$imagepath) {
	
	$html = '';
	
	foreach($items as $item) {
		$cena = transform_to_valute($item,'usd');
		$cena_blr = transform_to_valute($item,'byr');
		$text = '';
		
		if ($item['podarok']==1) $text = '+ Подарок';
		elseif ($item['raffle']==1) $text = 'Участвует в розыгрыше';

		$html .= '<div class="res-item">';
		$html .= '	<div class="res-left">';
		$html .= '		<div class="res-image">';
		$html .= '			<a href="/product/'.$item['path'].'">';
		$html .= '			<img src="'.$imagepath['small']['path'].insert_image($item['id']).'" />';	
		$html .= '			</a>';
		$html .= '		</div>';	
		$html .= '	</div>';
		$html .= '	<div class="res-right">';
		$html .= '		<div class="res-top">';
		$html .= '			<a href="/product/'.$item['path'].'" class="res-name">'.$item['name_maker'].'</a>';	
		$html .= 			get_star_by_product($item, true);	
		$html .= '			<div class="res-rascvetki"><a href="/product/'.$item['path'].'#pit-tab-3">'.get_count_colors($item['id'],true).'</a></div>';
		$html .= '			<a href="/product/'.$item['path'].'" class="res-podrobnee">Подробнее</a>';
		$html .= '					</div>';
		$html .= '		<div class="res-middle">';
		$html .= '			<div class="res-price">';
		$html .= '				Цена: ';
		$html .= '				<div class="blr">('.$cena_blr.')</div>';
		$html .= '			</div>';	
		$html .= '			<div class="res-price gift">'.$text.'</div>';
		$html .= '		</div>';
		$html .= '		<div class="res-bottom">';
		$html .= '			<a href="/cart/addtocompare/" pid="'.$item['id'].'" id="btn-wish-delegate">К сравнению</a>';		
		$html .= '			<a href="/product/'.$item['path'].'#pit-tab-6" class="">Оставить отзыв</a>';
		$html .= '			<a href="/product/'.$item['path'].'#pit-tab-1" class="">Характеристики</a>';
		$html .= '			<span class="res-nalichie"><div class="b-product-status r'.$item['status'].'">'.get_status($item['status'], true).'</div></span>';
		$html .= '		</div>';
		$html .= '	</div>';		
		$html .= '</div>';		
	}
	$html .= '<div class="show_all_result_search">';
	$html .= '<a href="#" id="show_all_result_search">Показать все результаты поиска</a>';
	$html .= '</div>';
	return $html;
}

function get_star_by_product($product, $result = FALSE) {
	
	$star = 0;
	$html = '';
	
	$reviews = Reviews::getReviewsForSite($product['id']);
	
	foreach($reviews as $item) {
		$star += $item['rank'];
	}
	$count = count($reviews);
	if ($count > 0) $star = $star / count($reviews);
	$star = ceil($star);
	if ($star !=0) {
		$html .= '<div class="b-product-rating star-'.$star.'">'.$star.'</div>';
	} else {
		$html .= '<div class="b-product-rating star-'.$star.'">Нет голосов</div>';
	}
	if ($result == TRUE) {
		return $html;
	} else {
		echo $html;
	}
	
}

function get_week($date_t) {
	
	$date = date('w',$date_t);
	
	switch ($date) {
		case 0: $result = "Воскресенье"; break;
		case 1: $result = "Понедельник"; break;
		case 2: $result = "Вторник"; break;
		case 3: $result = "Среда"; break;
		case 4: $result = "Четверг"; break;
		case 5: $result = "Пятница"; break;
		case 6: $result = "Суббота"; break;
		case 7: $result = "Воскресенье"; break;	
	}
	
	return $result;
}

function get_select_courier() {
	$trees  = Database::getRows(get_table('kontragenty_tip'),'id','asc',false,'couriers_show = 1');
	$html = '<select name="t" id="select-html">';
	$html .= '<option value="0">Нет</option>';
	foreach ($trees as $tree) {
		$bykva = mb_substr(@$tree['name'],0,1,'UTF-8');
		$items = Database::getRows(get_table('kontragenty'),'id','asc',false,'id_tip = '.$tree['id']);
		foreach($items as $item) {
			$html .= '<option value="'.$item['id'].'">'.$bykva.' '.$item['name'].'</option>';
		}			
	}	
	$html .= '</select>';
	echo $html;
}

function get_select_html($method) {
	$html = '';
	if ($method=='manager')	{
		$items = Database::getRows(get_table('kontragenty'),'id','asc',false,'id_tip = 4');
		$html .= '<option value="0">Менеджер</option>';
	}
	if ($method=='diler') {
		$items = Database::getRows(get_table('registration'),'id','asc',false,'diler > 0');
		$html .= '<option value="0">Дилер</option>';
	}
	if ($method=='registration') {
		$items = Database::getRows(get_table('registration'),'id','asc',false,'manager = 1');
		$html .= '<option value="0">Менеджер</option>';		
	}
	$html .= '<option value="0">Нет</option>';
	foreach($items as $item) {
		$html .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
	}			
	echo $html;
}

function get_print_form_to_date($date) {
$clients = Zakaz_client::getClientsByDate($date);
$date_t = strtotime($date);
$alfavit =  Array (
    'а', 'б', 'в', 'г', 'д', 'е',
    'ё', 'ж', 'з', 'и', 'й', 'к',
    'л', 'м', 'н', 'о', 'п', 'р',
    'с', 'т', 'у', 'ф', 'х', 'ц',
    'ч', 'ш', 'щ', 'ъ', 'ы', 'ь',
    'э', 'ю', 'я');
$curs = Currency_tree::getTreeByID(3);
$html = '';
$html .= '<tr>';
$html .= '	<td colspan=3>'.date('Y-m-d',$date_t).'</td>';
$html .= '	<td colspan=3>'.get_week($date_t).'</td>';
$html .= '	<td>курс</td>';
$html .= '	<td style="width:60px;">'.formatCena($curs['kurs']).'</td>';
$html .= '	<td colspan=4>Ирина: 375291408683&nbsp;&nbsp;&nbsp;&nbsp;Снежана: 375291657585</td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '	<td>№</td>';
$html .= '	<td>з№</td>';
$html .= '	<td>&nbsp;</td>';
$html .= '	<td>М</td>';
$html .= '	<td>К</td>';
$html .= '	<td style="width:320px;">Наименование товара</td>';
$html .= '	<td style="width:35px;">USD</td>';
$html .= '	<td style="width:60px;">BYR</td>';
$html .= '	<td style="width:160px;">Адрес</td>';
$html .= '	<td>Контакты</td>';
$html .= '	<td style="width: 160px;">Примечание</td>';
$html .= '	<td style="width: 40px;">Талон</td>';
$html .= '</tr>';
	$i = 0;
	foreach($clients as $client) {

		$where = "id_client = ".$client['id']." and rezerv = 0 and predzakaz = 0";
		$zakazs = Database::getRows(get_table('zakaz'),'id','asc',false,$where);		
		$count = count($zakazs);
		$kolvo = 0;
		$summa_usd = 0;
		$summa_blr = 0;
		$skidka = 0;
		$skidka_procent = 0;
		$dostavka = 0;		
		$doplata = 0;		
		$i++;
			foreach($zakazs as $zakaz) {
				
				$product = Database::getRow(get_table('catalog'),$zakaz['id_catalog']);
				
				if (isset($product['name'])) {
				
					$name = get_product_name($product,true,$zakaz['name_tovar']);
					
					if($zakaz['id_gift']!=0) {
						$gift = Database::getRow(get_table('catalog'),$zakaz['id_gift']);
						$name_gift = $gift['name']; 
						$name = $name.'<div>+ Подарок: '.$name_gift.'</div>';
					}
					
					if($zakaz['kolvo']>1) $name .= ' <span style="float:right; font-weight: bold;">'.$zakaz['kolvo'].' шт.</span>';
					
					$adres = get_adres_client($client);						

					$table_adminusers = get_table('adminusers');
					$adminuser = Database::getRow($table_adminusers,$client['id_adminuser']);
	
					$phones = get_phone($client['phone']);
					
					$manager = mb_substr(@$adminuser['fio'],0,1,'UTF-8');
		
					$cena = with_skidka($zakaz);
					$cena_blr = with_skidka($zakaz,'bur');

					$no = $zakaz['nomer_zakaza'];
					
					$client_comment = $client['comment'].' <b>'.$client['comment_m'].'</b>';
					
					$btn_garant = '<div" rel="'.$zakaz['id'].'" class="button print garantdata"></div>';
					
					if ($count == 1) {
						$html .= '<tr>';
						$html .= '	<td>'.$i.'</td>';
						$html .= '	<td>'.$no.'</td>';
						$html .= '	<td>'.$alfavit[$kolvo].'</td>';
						$html .= '	<td>'.@$manager.'</td>';
						$html .= '	<td>&nbsp;</td>';
						$html .= '	<td>'.$name.'</td>';
						$html .= '	<td>'.$cena.'</td>';
						$html .= '	<td>'.formatCena($cena_blr).'</td>';
						$html .= '	<td>'.$adres.'</td>';
						$html .= '	<td>'.$phones.'</td>';
						$html .= '	<td>'.$client_comment.'</td>';
						$html .= '	<td>'.$btn_garant.'</td>';
						$html .= '</tr>';
					}
					if ($count > 1) {
						$count_tmp = $count + 1;
						if ($kolvo == 0) {
							$html .= '<tr>';
							$html .= '	<td rowspan='.$count_tmp.'>'.$i.'</td>';
							$html .= '	<td rowspan='.$count_tmp.'>'.$no.'</td>';
							$html .= '	<td>'.$alfavit[$kolvo].'</td>';
							$html .= '	<td>'.@$manager.'</td>';
							$html .= '	<td>&nbsp;</td>';
							$html .= '	<td>'.$name.'</td>';
							$html .= '	<td>'.$cena.'</td>';
							$html .= '	<td>'.formatCena($cena_blr).'</td>';
							$html .= '	<td rowspan='.$count_tmp.'>'.$adres.'</td>';
							$html .= '	<td rowspan='.$count_tmp.'>'.$phones.'</td>';
							$html .= '	<td rowspan='.$count_tmp.'>'.$client_comment.'</td>';
							$html .= '	<td>'.$btn_garant.'</td>';							
							$html .= '</tr>';	
						} else {
							$html .= '<tr>';
							$html .= '	<td>'.$alfavit[$kolvo].'</td>';
							$html .= '	<td>'.@$manager.'</td>';
							$html .= '	<td>&nbsp;</td>';
							$html .= '	<td>'.$name.'</td>';
							$html .= '	<td>'.$cena.'</td>';
							$html .= '	<td>'.formatCena($cena_blr).'</td>';
							$html .= '	<td>'.$btn_garant.'</td>';
							$html .= '</tr>';						
						}
						$summa_usd += $cena;
						$summa_blr += $cena_blr;
		
						$skidka += $zakaz['skidka'];
						$skidka_procent += $zakaz['skidka_procent'];
						$dostavka += $zakaz['dostavka'];
						$doplata += $zakaz['doplata'];
						
						$kolvo++;
						$text = ''; 						
						if ($kolvo == $count) {
		
							if ($skidka != 0) {
								$text .= ' скидк. -'.$skidka.'$ ';
							} 							
							if ($skidka_procent != 0) {
								$text .= ' скидк. -'.$skidka_procent.'% ';
							} 						
							if ($dostavka != 0) {
								$text .= ' дост. +'.$dostavka.'$ ';								
							} 						
							if ($doplata != 0) {
								$text .= ' допл. +'.$doplata.'$ ';								
							}							
							$html .= '<tr>';
							$html .= '	<td colspan=4><b>ИТОГО'.$text.'</b></td>';
							$html .= '	<td><b>'.formatCena($summa_usd).'</b></td>';
							$html .= '	<td><b>'.formatCena($summa_blr).'</b></td>';
							$html .= '	<td>&nbsp;</td>';
							$html .= '</tr>';					
						}
						
					}
				}
			}
	}
	
return $html;	
}

function get_otchet_by_date($id_couriers, $adopted, $date_ot, $date_do) {

$limit = Couriers::getTotalCouriersByDate($id_couriers, $adopted, $date_ot, $date_do);
$couriers = Couriers::getCouriersByDate($id_couriers, $adopted, $date_ot, $date_do, 't1.`id`', 'desc', $limit);
				
$summ_ye = 0;
$summ_blr = 0;

$total_usd = 0;
$total_blr = 0;

$zp = 0;
				
$html = '';
$html .= '<tr>';
$html .= '	<td>Итог $</td>';
$html .= '	<td>Итог byr</td>';
$html .= '</tr>';
foreach($couriers as $courier) {

	$client = Zakaz_client::getClientByID($courier['id_client']);
	
	if (!in_array($client['sposob_dostavki'], array('vozim','rostik','other'))) {
	
		$total_usd += $courier['total']; 
		$total_blr += $courier['total_blr']; 
		
		$zp += $courier['zp'];
	
	}
}
	
	$html .= '<tr>';
	$html .= '	<td>'.$total_usd.'</td>';
	$html .= '	<td>'.$total_blr.'</td>';
	$html .= '</tr>';
	
$i = 0;

$result = array();
$result['html'] = $html;
$result['usd'] = $total_usd;
$result['blr'] = $total_blr;
$result['zp'] = $zp;
return $result;	
}

function transform_to_valute($item, $valute, $format = true) {

	$cur = $valute;
	
	$currency = Currency_tree::getTreeByID($item['id_currency']);	

	$cena = $item['cena'];
	
	if ($cur=="usd") {
	
		$result = $cena;

		if ($format) $result = '<span class="usd">'.formatCena($result).'</span> $';
		
	} 
		
	if ($cur=="byr") {

		$result = $cena * $currency['kurs'];
		
		if ($item['cena_blr'] > 0) $result = $item['cena_blr'];
		
		if ($format) {
			$result = '<span class="byr">'.formatCena($result).'</span> б.р.';
			if ($item['cena_blr'] > 0) $result = '<span class="byr">'.formatCena($item['cena_blr']).'</span> б.р.';	
		}
	
		
	} 	


	return $result;
	
}

function get_product_name($item, $prefix_show = false, $new_name = '') {
	if (isset($item['id_maker'])) $maker = Maker::getMakerById($item['id_maker']);
	if (isset($item['id_prefix'])) $prefix = Prefix::getPrefixByID($item['id_prefix']);
	$name = '';

	if ($prefix_show) $prefix_name = @$prefix['name'].' ';
	$maker_name = @$maker['name'].' ';
	
	$new_name = (!empty($new_name)) ? $new_name : @$item['name'];
	
	$name = @$prefix_name.@$maker_name.$new_name;
	return $name;
}


function add_new_product($name, $cena) {

	$path = translit_path($name);
		
	Catalog::addCollection(array(
		'title' => $name,
		'keywords' => $name,
		'description' => $name,		  
		'name' => $name,			  
		'cena' => $cena,		  
		'id_currency' => 3,		  
		'cena_blr' => '',
		'new' => 0,
		'hit' => 0,
		'spec_pred' => 0, 		  		  
		'active' => 0,
		'tovar_nedeli' => 0,
		'expert' => 0,
		'raffle' => 0,
		'prioritet' => 0,
		'short_description' => $name,
		'full_description' => $name,
		'path' => $path,
		'id_maker' => 0,			
		'instructions' => '',
		'pohozhie' => '',
		'soput' => '',
		'podarok' => '',
		'id_prefix' => 0,
		'id_char' => 0,
		'video_url' => '',
		'status' => 0
	));	
	
	$id_catalog = Catalog::getLastId();	
	
	Catalog_category::addCollection(array(
		'id_catalog' => $id_catalog, 
		'id_razdel0' => 1, 
		'id_razdel1' => 278, 
		'id_razdel2' => 0, 
		'id_razdel3' => 0, 
		'id_razdel4' => 0, 
		'id_razdel5' => 0
	));
	
	Catalog_language::addCollection(array(
		'id_catalog_lng' => $id_catalog,
		'id_language' => 2,
		'title_lng' => $name,
		'keywords_lng' => $name,
		'description_lng' => $name,		  
		'name_lng' => $name,			  
		'short_description_lng' => $name,
		'full_description_lng' => $name,			
		'instructions_lng' => ''
	));		
	Adminusers_stats::addStats(array(
		'id_catalog' => $id_catalog,
		'id_adminusers' => $_SESSION['isLoggedIn']['id'],
		'action' => 'add',
		'created' => time()
	));	
	
	return $id_catalog;
}



function get_raffle_form_by_date($date_ot, $date_do, $tag) {

	$date_now = date("Y-m-d");

	$interval = date_diff(date_create($date_now), date_create($date_do));
	$chas = $interval->days * 24;

	$fl = '';

	if ($date_do < $date_now) {
		$fl = '<span class="blue">Завершен</span>';
		return $fl;
	}
	if (($date_ot > $date_now) and ($date_now < $date_ot)) {
		$fl = '<span class="green">В ожидании</span>';
		return $fl;
	}	
	if (empty($fl)) {
		$fl .= '<script type="text/javascript">';
		$fl .= '	setInterval("timer('.$chas.', 0, 0, '.$tag.')",1000)';
		$fl .= '</script>';
		$fl .= '<span id="chas'.$tag.'" class="h-block">00</span>:<span id="min'.$tag.'" class="t-block">00</span>:<span id="sec'.$tag.'" class="t-block">00</span>';
	} 				
							
	return $fl;
}

function update_raffle_in_catalog($id,$str) {

	$raffles = explode(',',$str);

	Catalog::clearCollectionRaffle($id);
	
	foreach($raffles as $raffle) {
		Catalog::updateCollectionRaffle(array(
			'id' => $raffle,
			'raffle' => $id
			));
	}
	
}


function transform_norm_date($date) {

	$date_t = strtotime($date);
	
	$result = date('d.m.Y',$date_t);
	
	return $result;
}


function get_courier_name($name) {

	$result = '';

	$result = mb_substr(@$name,0,6,'UTF-8');
	
	return $result;
}


function get_cur_valute($passed) {
	
	$result = "";

	switch ($passed) {
		case 0: $result = "Нет"; break;
		case 1: $result = "usd"; break;
		case 2: $result = "byr"; break;		
	}
	
	return $result; 

}

function with_skidka($item, $valute = 'usd') {

	$curs = Database::getRow(get_table('currency_tree'),3);
	
	$result = $item['cena'] * $item['kolvo'];
		
	if ($item['kolvo']<1) $kolvo = 1;
	else $kolvo = $item['kolvo'];
		
	$skidka = $item['skidka'] / $kolvo;
	$skidka_procent = $item['skidka_procent'];
	$doplata = $item['doplata'] / $kolvo;
	$dostavka = $item['dostavka'] / $kolvo;
	
	if ($valute == 'bur') {
		$skidka *= $curs['kurs'];	
		$doplata *= $curs['kurs'];
		$dostavka *= $curs['kurs'];
		$result = $item['cena_blr'] * $item['kolvo'];
	}

	$skidka_procent = ($result * $skidka_procent) / 100;
		
	if ($doplata != 0) $result += $doplata;
	if ($skidka_procent != 0) $result -= $skidka_procent;
	if ($skidka != 0) $result -= $skidka;
	if ($dostavka != 0) $result += $dostavka;
	
	return round($result,2);
}

function get_summa_by_client($client) {
	
	$zakazs = Database::getRows(get_table('zakaz'),'id','asc',false,'id_client = '.$client['id']);
	$sum_usd = $client['doplata_usd'];
	$sum_blr = $client['doplata_blr'];
	foreach($zakazs as $zakaz) {
		$sum_usd += with_skidka($zakaz);
		$sum_blr += with_skidka($zakaz,'bur');
	}
	return array('usd' => $sum_usd, 'bur' => $sum_blr);
}

function get_courier_zp($client) {
	$result = 0;
	
	if (($client['samovivoz']!=1) and ($client['samovivoz_ofice']!=1)) {
	
		$result = 5;
	
	}
	
	return $result;
}

function get_summa_zakupka($item) {
	if (empty($item['id_item'])) return 0;
	$sklad_tovars = Database::getRows(get_table('sklad_tovar'),'id','asc',false,'id_item='.$item['id_item'].' and status=2');
	$cena_ye = 0;
	$kolvo = 0;
	$sum_kolvo = 0;
	
	$sql = 'SELECT sum(`kolvo`) as `sum` 
		FROM '.get_table('zakaz').' 
		WHERE `id_item` = '.$item['id_item'].' 
			and `id` <= '.$item['id'].'
			and `was` = 1
			and `delivered` = 1'; 
	$res = Database::getSQL($sql);
	$kolvo = $res[0]['sum'];

	foreach($sklad_tovars as $st) {

		$sum_kolvo += $st['kolvo_hold'];
		if ($sum_kolvo >= $kolvo) {
			$d_tmc = Database::getRow(get_table('delivery_tmc'),$st['id_delivery_tmc']);
			if (isset($d_tmc) and !empty($d_tmc['id'])) {
				$valute = $d_tmc['id_valute'];
				$kurs = $d_tmc['kurs'];
				$cena_ye = transform_to_kurs($st['cena'],$valute,$kurs) * $item['kolvo'];
				return $cena_ye;
			}
						
		}
	}

}

function get_table($name) {
	return Config::getParam('modules->'.$name.'->table');
}

function get_table_lng($name) {
	return Config::getParam('modules->'.$name.'->table_lng');
}

function get_url($count) {

	$count++;
	$url = '';

	for($i=1;$i<$count;$i++) {
		$url .= '/'.URL::getSegment($i);
	}
	return $url;

}
  
function get_select_menu_by_id_tree($id) {

	$html = '<option value="0"> -- Выберите -- </option>';

	$makers = Catalog::getIdMakerByIdRazdel($id);

	if (@$makers) {
	
		foreach($makers as $maker) {
			$html .= '<option value="'.$maker['id'].'">';
			$html .= $maker['name'];
			$html .= '</option>';
		}

	}
 
    return $html;
}   


function get_select_menu_by_id_maker($id_tree,$id_maker) {

	$params = array();

	$html = '<option value="0"> -- Выберите -- </option>';
	
	$table_catalog = get_table('catalog');
	$table_categories = get_table('catalog_categories');
	
	if ($id_maker !=0) $where = "t1.id_maker = $id_maker and ";
	else $where = "";
	$where .= "t2.id_razdel1 = $id_tree";
	$table = "$table_catalog as t1 JOIN $table_categories as t2 ON t1.id = t2.id_catalog";
	
	$items = Database::getRows($table, 'name', 'asc', Database::getCount($table,$where), $where, 't1.id');

	if (@$items) {
	
		foreach($items as $item) {
			$html .= '<option value="'.$item['id'].'">';
			$html .= $item['name'];
			$html .= '</option>';
		}

	}
 
    return $html;
} 

function transform_to_kurs($sum_opt,$valute,$kurs) {

	$result = 0;

	if ($kurs == 0) $kurs = 1;

	if ($valute == 1) {
		//rur
		$result = $sum_opt / $kurs;
	}
	
	if ($valute == 2) {
		//blr
		$result = $sum_opt / $kurs;		
	}
	
	if ($valute == 6) {
		//eur
		$result = $sum_opt / $kurs;	
	}	
	
	if ($valute == 7) {
		//eur
		$result = $sum_opt / $kurs;	
	}

	return round($result,2);

}

function get_valute_tmc($valute) {

	$result = "";

	switch ($valute) {
		case 0: $result = "Нет"; break;
		case 1: $result = "rur"; break;
		case 2: $result = "byr"; break;		
		case 6: $result = "eur"; break;		
		case 7: $result = "usd"; break;		
	}
	
	return $result; 

}

function get_status_tmc($status) {

	$result = "";

	switch ($status) {
		case 0: $result = "Нет"; break;
		case 1: $result = "Предварительная накладная"; break;
		case 2: $result = "Поступление ТМЦ"; break;				
	}
	
	return $result; 

}

function get_adres_client($client) {

	$adres = '';

	$city = (!empty($client['city'])) ? 'г. '.$client['city'].', ' : ''; 
	$poselok = (!empty($client['poselok'])) ? 'п. '.$client['poselok'].', ' : ''; 	
	$street = (!empty($client['street'])) ? 'ул. '.$client['street'].', ' : ''; 
	$house = (!empty($client['house'])) ? 'д. '.$client['house'] : '';
	$building = (!empty($client['building'])) ? '/'.$client['building'] : ''; 
	$apartment = (!empty($client['apartment'])) ? ', кв. '.$client['apartment'].', ' : '';

	/*
	$adres = $city.$street.$house.$building.$apartment;
	
	if (!empty($client['code_zayavka'])) $adres = $client['code_zayavka'];
	if (@$client['samovivoz']==1) $adres = 'Самовывоз (склад)';
	if (@$client['samovivoz_ofice']==1) $adres = 'Самовывоз (офис)';*/

	$adres = $city.$poselok.$street.$house.$building.$apartment;	

	if (($client['sposob_dostavki']=="vozim") and (!empty($client['code_zayavka']))) $adres = $client['code_zayavka'];
	if ($client['sposob_dostavki']=="rostik") $adres = "Ростик";
	if ($client['sposob_dostavki']=="other") $adres = "Другой";
	
	if ($client['samovivoz']==1) $adres = 'Самовывоз (склад)';
	if ($client['samovivoz_ofice']==1) $adres = 'Самовывоз (офис)';
				
	return $adres;
	
	
	
}

function save_price_catalog_sklad() {
	$host = $_SERVER['HTTP_HOST'];
	$list = array();
	//$params['tip_catalog'] = $_GET['id_tree'];
	//$params['id_maker'] = $_GET['id_maker'];
	//$params['id_tovar'] = @$_GET['id_tovar'];
	$params['sort'] = 'id-asc';
	
	$count = Catalog::getPodborAdmin($params, "", FALSE);

	$items = Catalog::getPodborAdmin($params, $count, TRUE);
	
	array_push($list,array(
		xls_conv('Префикс'),
		xls_conv('Производитель'),
		xls_conv('Наименование'),
		xls_conv('Цвет'),
		xls_conv('СО'),
		xls_conv('РО'),
		xls_conv('РОС'),
		xls_conv('Р'),
		xls_conv('П'),
		xls_conv('ОП'),
		xls_conv('Возвр. на склад'),
		));
	
	$table_pfx = get_table('prefix');
	$table_maker = get_table('maker');
	foreach($items as $item) {
		$id_item = $item['id'];
		$prefix = Database::getRow($table_pfx,$item['id_prefix']);
		$maker = Database::getRow($table_maker,$item['id_maker']);
		array_push($list,array(
				xls_conv(@$prefix['name']),
				xls_conv(@$maker['name']),
				xls_conv($item['name']),
				xls_conv($item['name_color']),
				get_free_ostatok($id_item),
				get_real_ostatok($id_item),
				get_real_ostatok_sklad($id_item),
				get_rezerv($id_item),
				get_predzakaz($id_item),
				get_ozhidaemiy_prihod($id_item),
				get_vozvrat_na_sklad($id_item)));		
		
	}
	
	outputCSV('assets/files/sklad_tovar.csv',$list);
	$url = "http://".$host."/assets/files/sklad_tovar.csv";
	echo json_encode('Прайс обновлен! <br/><a href="'.$url.'">Ссылка</a> для скачивания. <br/>'.$url);				
}

function update_kolvo_tovar($zakaz) {
	$kolvo = 0;
	$table = get_table('sklad_tovar');
	if (isset($zakaz['id_item'])) {
		$where = 'id_item = "'.$zakaz['id_item'].'" and kolvo > 0 and status = 2';
		$sklad_tovars = Database::getRows($table,'id','asc',false,$where);
		if (!empty($sklad_tovars)) {
			foreach($sklad_tovars as $s_tovar) {
				if ($s_tovar['kolvo'] >= $zakaz['kolvo']) {
					$kolvo = $s_tovar['kolvo'] - $zakaz['kolvo'];
				} else {	
					$raznica = $s_tovar['kolvo'] - $zakaz['kolvo'];
					$kolvo = 0;
					$zakaz['kolvo'] = $raznica * (-1);
				}
				$data = array('kolvo' => $kolvo);
				Database::update($table,$data,'id = '.$s_tovar['id']);			
			}
		}
	}
}

function send_kladovshik_sms($post,$date_dostavka,$zakazs) {
	if (empty($date_dostavka)) $date_dostavka = date('Y-m-d');
	$timestamp = strtotime($date_dostavka);
	$date_now = date('d.m.Y',$timestamp);
	$number_day = date('N',$timestamp);
	$day = array("Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Bc");
	$name_tovar = "";
	$i = 1;
	$sum_usd = 0;
	$sum_bur = 0;
	
	$skidka_procent = 0;
	$skidka = 0;
	$doplata = 0;
	foreach($zakazs as $zakaz) {
	
		$sum_usd += with_skidka($zakaz);
		$cena_blr = with_skidka($zakaz,'bur');
		$sum_bur += $cena_blr;
		
		if ($zakaz['skidka_procent']!=0) $skidka_procent += $zakaz['skidka_procent'];
		if ($zakaz['skidka']!=0) $skidka += $zakaz['skidka']; 				
		if ($zakaz['doplata']!=0) $doplata += $zakaz['doplata'];		
		
		$shtuk = '';
		if ($zakaz['kolvo'] > 1) $shtuk = " ".$zakaz['kolvo']."шт.";
		
		$product = Database::getRow(get_table('catalog'),$zakaz['id_catalog']);
		$name = get_product_name($product,true,$zakaz['name_tovar']);
		$name_tovar .= "$i $name $shtuk - $cena_blr б.р.\n";

		$i++;
	}				
	$skidka = "";
	if ($skidka_procent != 0) $skidka .= "\nСкидка $skidka_procent%";
	if ($skidka != 0) $skidka .= "\nСкидка $skidka$"; 				
	if ($doplata != 0) $skidka .= "\nДоплата $doplata$";
					
	$text = "Самовывоз: $date_now $day[$number_day]\n$name_tovar $sum_usd$ $sum_bur б.р.$skidka\n".$post['comment_m'];
	$result = send_sms($text,'375293179238'); // Артур
	//$result = send_sms($text,'375291444111'); // Егор
	//$result = send_sms($text,'375296727959');
}

function access_to_controller() {
		
	$access = get_array_access();
	$con = URL::getSegment(2);
	$con3 = URL::getSegment(3);
	if (@$access['id']==1) return;
	$urls = array("adminpanel","adminaccess","catalog_colors","catalog_3d","catalog_images","catalog_prefix");
	if (!empty($con) and (!in_array($con,$urls))) { 
		if (@$access[$con.'_review']!=1) header('Location: /adminpanel');
	} 
	
}

function get_sum_zakaz($id,$post = array()) {
	$data = array();
	
	$table_zakaz = get_table('zakaz');
	$table_client = get_table('zakaz_client');
	
	$zakazs = Database::getRows($table_zakaz,'id','asc',false,"id_client=$id");
	$zakaz_client = Database::getRow($table_client,$id);		
		
	if (!empty($post)) { $zakaz_client = $post; }

	$total_sum_usd = 0;
	$total_sum_blr = 0;		
	$sum_usd = 0;
	$sum_blr = 0;
	foreach($zakazs as $zakaz) {
		$total_sum_usd += $zakaz['cena']*$zakaz['kolvo'];
		$total_sum_blr += $zakaz['cena_blr']*$zakaz['kolvo'];
		if (($zakaz['rezerv']==1) or ($zakaz['predzakaz']==1)) {
			$sum_usd += $zakaz['cena']*$zakaz['kolvo'];
			$sum_blr += $zakaz['cena_blr']*$zakaz['kolvo'];			
		}
	}	
	$total_sum = sum_with_skidka($zakaz_client,$total_sum_usd,$total_sum_blr);
	$sum = sum_with_skidka($zakaz_client,$sum_usd,$sum_blr);

	$data['total_sum_usd'] = $total_sum['usd'];
	$data['total_sum_blr'] = $total_sum['blr'];		
	$data['sum_usd'] = $sum['usd'];
	$data['sum_blr'] = $sum['blr'];
	
	return $data;
}


function get_status_zakaz($zakaz) {
	$status = '';
	if ($zakaz['nosell']==1) $status = 'Не продан';
	if ($zakaz['shipped']==1) $status = 'Отгружен';
	if ($zakaz['predzakaz']==1) $status = 'Предзаказ';
	if ($zakaz['rezerv']==1) $status = 'Резерв';								
	if ($zakaz['delivered']==1) $status = 'Доставлен';
	if (($zakaz['was']==1) and ($zakaz['delivered']==0)) $status = 'Не доставлен';
	if (($zakaz['was']==1) and ($zakaz['delivered']==0) and ($zakaz['vozvrat']==1)) $status = 'Возвращен';
	
	return $status;
}

function get_childs($id) {
    $html = '';
	
	$trees = Database::getRows(get_table('kassa_tree'),'id','asc',false,"pid = $id");
	$access = get_array_access();
    foreach ($trees as $row) {
		$arr = explode(',',$access['id_kassa']);
		if (in_array($row['id'],$arr) or $access['id']==1) $html .= $row['id'].','.get_childs($row['id']);
    }

    return $html;	
}

function get_string_id_tree($id_tree) {
	
	$html = $id_tree.','.get_childs($id_tree);
	return substr($html, 0, -1);
	
}

function get_where_by_params($get) {
	
	$access = get_array_access();
	$id_tree = (@$get['id_tree']) ? $get['id_tree'] : $access['id_kassa'];
	$id_tip_operation = @$get['id_tip_operation'];
	$operation = @$get['operation'];
	$valute = @$get['valute'];
	
	$beznal = (isset($get['beznal'])) ? 1 : 0;
	
	$date_ot = @$get['date_ot'];
	$date_do = @$get['date_do'];
	$tip_kassa = @$get['tip_kassa'];
	$adminusers = @$get['adminusers'];
	
	$where = "1";
	
	if (!empty($id_tree)) {
		if (is_array($id_tree)) $where .= ' and id_tree IN ('.implode(",", $id_tree).')';
		elseif (is_string($id_tree)) $where .= ' and id_tree IN ('.get_string_id_tree($id_tree).')';
		elseif (is_int($id_tree)) $where .= ' and id_tree = IN ('.get_string_id_tree($id_tree).')';
	}

	if (!empty($id_tip_operation)) $where .= ' and id_tip_operation IN ('.implode(",", $id_tip_operation).')';
	if (!empty($operation)) $where .= ' and operation IN ('.implode(",", $operation).')';
	if (!empty($adminusers)) $where .= ' and id_adminusers IN ('.implode(",", $adminusers).')';
	
	if (!empty($date_ot)) $where .= ' and date_create >= "'.$date_ot.'"';
	if (!empty($date_do)) $where .= ' and date_create <= "'.$date_do.'"';

	if (!empty($beznal)) $where .= ' and beznal = 1';
	
	if (!empty($valute)) {
		$w_tmp = '';
		foreach($valute as $item) {		
			if ($item == 1) $w_tmp .= ' cena_blr > 0 or';			
			if ($item == 2) $w_tmp .= ' cena_usd > 0 or';
			if ($item == 3) $w_tmp .= ' cena_eur > 0 or';			
			if ($item == 4) $w_tmp .= ' cena_rur > 0 or';			
		}
		$w_tmp = substr($w_tmp, 0, strlen($w_tmp) - 2); 
		$where .= ' and ('.$w_tmp.')';
	}
	
	if (!empty($tip_kassa)) {
		$str = '';
		$couriers_arr = Database::getRows(get_table('kassa_tree'),'id','asc',false,'id_kontragenty IN ('.implode(",", $tip_kassa).')');
		if (!empty($couriers_arr)) {
			foreach($couriers_arr as $item) {
				$str .= $item['id'].',';
			}
			$str .= substr($str, 0, strlen($str) - 1); 
			$where .= ' and id_tree IN ('.$str.')';	
		}		
	}
	
	return $where;
}



function insert_kassa_by_kontragent($post) {
	$data = array();
	$table_tree = get_table('kassa_tree');
	$table = get_table('kassa');
	$kassa_tree = Database::getRow($table_tree,$post['id_couriers'],'id_kontragenty');
	
	if (!empty($kassa_tree['id'])) {
		
		if (isset($post['id_kassa_tree']) and !empty($post['id_kassa_tree'])) {
			$id_tree = $post['id_kassa_tree'];
			$post['id_tree_end'] = $kassa_tree['id'];		
		} else {
			$id_tree = $kassa_tree['id'];
		}
	
		$nomer = 0;
		$nomer = Database::getLastId($table);
		$nomer++;		
		$data_tmp = array(
			'active' => 1,
			'id_tree' => $id_tree,	
			'id_tree_end' => @$post['id_tree_end'],				
			'cena_usd' => @$post['cena_usd'],			  
			'cena_blr' => @$post['cena_blr'],			  			  			  
			'cena_eur' => @$post['cena_eur'],			  			  			  
			'cena_rur' => @$post['cena_rur'],			  			  
			'operation' => $post['operation'],			  			  		  			  
			'date_create' => date("Y-m-d"),
			'time_create' => date("G:i:s"),
			'id_adminusers' => $_SESSION['isLoggedIn']['id'],		
			'id_couriers' => @$post['id'],		
			'beznal' => @$post['beznal'],		
		);
		$kassa = array();
		if (isset($post['id'])) $kassa = Database::getRow($table,$post['id'],'id_couriers');
		if (empty($kassa)) {		
			$data_tmp['nomer'] = $nomer;
			Database::insert($table,$data_tmp);
		} elseif (@$post['adopted']==1) {
			$data_tmp['nomer'] = $kassa['nomer'];
			Database::insert($table,$data_tmp);
		} else {
			$data_tmp['nomer'] = $kassa['nomer'];
			Database::update($table,$data_tmp,'id='.$kassa['id']);			
		}
		$data['succes'] = true;
	} else {
		$data['succes'] = false;
		$data['message'] = "Кассы с таким контрагентом не существует! Данные не сохранились!";
	}
	return $data;
}

function insert_zp_by_manager($courier) {
	$data = array();

	$client = Database::getRow(get_table('zakaz_client'),$courier['id_client']);
	$adminuser = Database::getRow(get_table('adminusers'),$client['id_adminuser']);

	if (!empty($adminuser['id'])) {

		$total_usd = ($courier['total_blr'] / $courier['kurs']) + $courier['total'];
		$zp = 0;
		
		$items = Database::getRows(get_table('zpmanager'));
		foreach($items as $item) {
			if ($item['r_delta'] < $total_usd) {
				$zp = $total_usd / 100 * $item['zp_procent'];
				break;
			}	
		}
		
		if ($zp == 0) {
			$where = "cena_ot <= $total_usd and cena_do >= $total_usd";
			$items = Database::getRows(get_table('zpmanager'),'id','asc',false,$where);
			if (!empty($items)) $zp = $items[0]['zp'];
		}
		
		$arr = array(
			'id_manager' => $adminuser['id_manager'],
			'id_adminuser' => $client['id_adminuser'],
			'id_client' => $courier['id_client'],
			'zp' => $zp,
			'date_sell' => $client['date_dostavka'],		
		);
		Database::insert(get_table('managers'),$arr);
		$data['succes'] = true;
	} else {
		$data['succes'] = false;
		$data['message'] = "Данные не сохранились!";
	}
	return $data;
}

function get_logs_by_table($post) {
	$data = array();
	
	$items = Database::getRows(get_table('logs'),'date_create','desc',false,'table_name = "'.$post['table_name'].'" and id_table = '.$post['id']);
	
	$product = Database::getRow(get_table('catalog'),$post['id_catalog']);
	
	$html = '<input type="hidden" id="id_export" value="'.$post['id'].'" />';
	$html .= '<input type="hidden" id="table_name" value="'.$post['table_name'].'" />';
	$html .= '<table class="table-tabs-content logs" cellspacing="0">';	
	$html .= '<tr><td colspan=6><b>'.get_product_name($product,true).'</b></td></tr>';
	$html .= '<tr>
		<td>Цена $</td>
		<td>Цена Br</td>
		<td>Цена закуп</td>
		<td>Дельта</td>
		<td>Дата</td>
		<td>Время</td>
	</tr>';	
	foreach($items as $item) {
		$html .= '<tr>
			<td>'.$item['cena'].'</td>
			<td>'.$item['cena_blr'].'</td>
			<td>'.$item['cena_zakup'].'</td>
			<td>'.$item['delta'].'</td>
			<td>'.$item['date_create'].'</td>
			<td>'.$item['time_create'].'</td>
		</tr>';
	}
	$html .= '</table>';
	
	$data['msg'] = $html;
	
	return $data;
}


function save_logs($post) {
				
	$list = array();
	$params = array();
	$host = $_SERVER['HTTP_HOST'];

	
	$items = Database::getRows(get_table('logs'),'date_create','desc',false,'table_name = "'.$post['table_name'].'" and id_table = '.$post['id']);
	
	$product = Database::getRow(get_table('catalog'),$post['id_catalog']);
	
	array_push($list,array(
		xls_conv(get_product_name($product,true))
		));		
		
	array_push($list,array(
		xls_conv('Цена $'),
		xls_conv('Цена Br'),
		xls_conv('Цена закупочная'),
		xls_conv('Дельта'),
		xls_conv('Дата'),
		xls_conv('Время')
		));			

	foreach($items as $item) {
		array_push($list,array(
			$item['cena'],
			$item['cena_blr'],
			$item['cena_zakup'],
			$item['delta'],
			$item['date_create'],
			$item['time_create'],
			));					
	}
	
	outputCSV('assets/files/logs.csv',$list);
	$url = "http://".$host."/assets/files/logs.csv";
	echo json_encode('Фаил logs обновлен! <br/><a href="'.$url.'">Ссылка</a> для скачивания. <br/>'.$url);					
}

function get_phone($phone) {
	$result = '';
	$items = explode(",", $phone);
	foreach($items as $item) {
		$result .= '<div>'.$item.'</div>';
	}
	return $result;
}

function get_kurs() {
	return Database::getField(get_table('currency_tree'),3,'id','kurs');
}


function get_table_sklad_tovar($id_item, $full = true) {

	$s = get_sklad_real_ostatok($id_item,1);
	$o = get_sklad_real_ostatok($id_item,2);
	
	$fo = get_free_ostatok($id_item);
	$ro = get_real_ostatok($id_item);
	$r = get_rezerv($id_item);
	$r1 = get_vozvrat_na_sklad($id_item);
	$d = get_dostavka_tovara($id_item);	
	$p = get_predzakaz($id_item);
	$op = get_ozhidaemiy_prihod($id_item);
	$color = '';
	if ($fo == 0) $color = 'red';
	if ($fo > 0) $color = 'green';
	if (($fo == 0) and (($r > 0) or ($op > 0) or ($r1 > 0))) $color = 'blue';
										
	$style = 'style="background-color:'.$color.'; color: #fff;"';
	$html = "<table class=\"table-tabs-content tip\">
		<tr>";
		if ($full) {
	$html .= "<td>С</td>
			<td>О</td>";
		}
	$html .= "<td $style>СО</td>
			<td>РО</td>
			<td>Р</td>
			<td>Р1</td>	
			<td>Д</td>			
			<td>П</td>
			<td>ОП</td>		
		</tr>
		<tr>";
		if ($full) {		
	$html .= "<td>$s</td>
			<td>$o</td>";
		}
	$html .= "<td $style>$fo</td>
			<td>$ro</td>
			<td>$r</td>
			<td>$r1</td>				
			<td>$d</td>				
			<td>$p</td>
			<td>$op</td>								
		</tr>
	</table>";
	return $html;
}

function get_day_month($date) {
	$month = Array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
	$timestamp = strtotime($date);
	$m = $month[date('n', $timestamp)-1];
	$d = date('d');
	return '<b>'.$d.'</b> '.$m;
}

function get_spros_list() {
	
	$html = '<table class="table-spros">';

	$items = array();	
	/* В работе */
	$items = Database::getRows(get_table('spros'),'time_spros','asc',false,"active=0 and status=4");

	if (!empty($items)) {
		
		$html .= '<tr><td colspan=4><strong>В работе</strong></td></tr>';
		
		foreach($items as $item) {
	
			$name_tovar = get_name_by_iditem($item['id_item']);

			$res_now = (date('G') * 60) + date("i");
			$date_now = date('Y-m-d');
						
			$time_arr = explode(':',$item['time_update']);
			$res_spros = (($time_arr[0] + $item['chas']) * 60) + ($time_arr[1] + $item['min']);

			$min = $res_spros - $res_now;

			if ($min > 0 and $date_now == $item['date_spros']) $min = 'Осталось: '.$min.' мин';
			else $min = 'Время вышло';
			$manager = Database::getField(get_table('registration'),$item['id_manager'],'id','firstname');
			
			$html .= '<tr>';
			$html .= '<td>'.$manager.'</td>';
			$html .= '<td>'.$name_tovar.'</td>';
			$html .= '<td>'.$item['time_spros'].'</td>';
			$html .= '<td>'.$min.'</td>';
			$html .= '</tr>';
			
		}
	
	}
	
	$items = array();
	/* Думают */
	$items = Database::getRows(get_table('spros'),'time_spros','asc',false,"active=0 and status=3");

	if (!empty($items)) {
		
		$html .= '<tr><td colspan=4><strong>Думают</strong></td></tr>';
		
		foreach($items as $item) {
	
			$name_tovar = get_name_by_iditem($item['id_item']);
			$manager = Database::getField(get_table('registration'),$item['id_manager'],'id','firstname');
			
			$date_now = date('Y-m-d');
			if ($date_now != $item['date_spros']) $min = '<a href="" class="get-time-hide" rel="'.$item['id'].'">Открыть</a>';
			else $min = '';
			
			$html .= '<tr>';
			$html .= '<td>'.$manager.'</td>';
			$html .= '<td>'.$name_tovar.'</td>';
			$html .= '<td>'.$item['time_spros'].'</td>';
			$html .= '<td>'.$min.'</td>';
			$html .= '</tr>';
			
		}
		
	}

	$html .= '</table>';
	
	return $html;
		
}

function get_name_by_iditem($id_item, $href = true) {
	
	$id_items = explode('-',$id_item);
	
	$id_catalog = (isset($id_items[0])) ? $id_items[0] : $id_item;
	$id_color = (isset($id_items[1])) ? $id_items[1] : 0;
	
	$product = Database::getRow(get_table('catalog'),$id_catalog);
	$colors = Database::getRow(get_table('colors'),$id_color);
	
	$name_tovar = get_product_name($product,true).' '.@$colors['name'];	
	
	if ($href) $name_tovar = '<a href="/product/'.$product['path'].'" target="_ablank">'.$name_tovar.'</a>';
				
	
	return $name_tovar;
	
}

function norm_date($date) {
	return date('d.m.y',strtotime($date));
}

function get_url_canonical() {
	$html = 'http://'.$_SERVER["SERVER_NAME"].'/';
	$html_end = '';
	
	for($i=1;$i<5;$i++) 
		if (URL::getSegment($i)!='' and $i!=2 and URL::getSegment($i)!='home') 
			$html .= URL::getSegment($i).'/';
	
	if (isset($_GET['page'])) 
		$html_end .= '?page='.$_GET['page'];
	
	return $html.$html_end;
}

function get_last_razdel($item) {

	if (!empty($item['id_razdel5'])) $id_razdel = $item['id_razdel5']; 
		elseif (!empty($item['id_razdel4'])) $id_razdel = $item['id_razdel4'];
			elseif (!empty($item['id_razdel3'])) $id_razdel = $item['id_razdel3'];
				elseif (!empty($item['id_razdel2'])) $id_razdel = $item['id_razdel2'];
					elseif (!empty($item['id_razdel1'])) $id_razdel = $item['id_razdel1'];
					
	return $id_razdel;	
}

function get_parents_table($table,$pid) {
    $html = '';
	
	$items = Database::getRows($table,'id','asc',false,"id = $pid and active = 1");
    foreach ($items as $item) {
		$html .= $item['id'].','.get_parents_table($table,$item['pid']);
    }

    return $html;	
}

function get_crumbs_category($id_tree) {
	$string_tree = get_parents_table(get_table('catalog_tree'),$id_tree);
	$array_id = array_reverse(array_filter(explode(',',$string_tree)));
	$last_id = array_pop($array_id);
	$html = '<li><a href="/">Главная</a>&nbsp;/&nbsp;</li>';
	$html .= '<li><a href="/category/">Каталог</a></li>';
	foreach($array_id as $id) {
		$tree = Database::getRow(get_table('catalog_tree'),$id);
		$html .= '<li>&nbsp;/&nbsp;<a href="/category/'.$tree['path'].'">'.$tree['name'].'</a></li>';
	}
	$tree = Database::getRow(get_table('catalog_tree'),$last_id);
	if (isset($tree['name'])) {
		if (URL::getSegment(1)=='category')	$html .= '<li>&nbsp;/&nbsp;'.$tree['name'].'</li>';
		else $html .= '<li>&nbsp;/&nbsp;<a href="/category/'.$tree['path'].'">'.$tree['name'].'</a></li>';
	}
	return $html;
}

function list_product_html($collections,$imagepath,$getLimitas,$pagination,$rows_on_page,$anchor,$razdel0,$h1item,$totals) {
	$html = '';
	$html .= '<div class="b-products">';
	$html .= '<h1 class="b-page-title"><span>'.$h1item.'</span></h1>';		
	if (@$collections) {
	$html .= '<div class="toolbar top">';

	if ((URL::getSegment(3))!='') 
	$html .= '<a href="/category/" title="Каталог" class="crumbs">Каталог</a><b class="slash">/</b>';
			
	if (@$razdel0) 
	$html .= '<a href="/category/'.$razdel0['path'].'/" class="crumbs">'.$razdel0['name'].'</a><b class="slash">/</b>';
			
	if (@$h1item) 
	$html .= '<span class="h1">'.$h1item.'</span>';

	$html .= '<span class="items-count">
				Показано '.$getLimitas.' (всего '.@$totals.' позиций)
			</span>			
			<span class="pagination">'.@$pagination.'</span>
		</div>';
		
	if (@$vmode=="list") {
	$html .= '<ul class="list">';
	foreach($collections as $item) {
	$html .= '<li>
				<div class="pict">
				<a href="/product/'.$item['path'].'/" class="name">'.get_product_name($item).'</a>
				<a href="/product/'.$item['path'].'/" class="image">
					<img src="'.$imagepath['small']['path'].insert_image($item['id']).'" alt="'.$item['name'].'">
				</a>
				<div class="buttons">
					<a href="/product/'.$item['path'].'/#pit-tab-3" class="kol-colors">'.get_count_colors($item['id'],true).'</a>
					<a href="#" class="status r'.$item['status'].'">'.get_status($item['status'],true).'</a>
					<a href="/product/'.$item['path'].'/" class="btn-details">Подробнее</a>';
					$html .= get_buttons_catalog($item['id']);
					if ($item['status'] != 4) {
						if ($item['cena_old']>0 or $item['cena_blr_old']>0) {
							$html .= '<span class="price price-old">
								<div class="cena-old">'.transform_to_currency_old($item,true,true).'</div>
								Цена: '.transform_to_currency($item).'
							</span>';
						} else {
							$html .= '<span class="price">Цена: '.transform_to_currency($item).'</span>';
						}
					} else {
						$html .= '<a href="#" class="status nvn">Снят с производства</a>';
					}	
	$html .= '</div>';					
		if ($item['new']==1) $html .= '<i class="icon new"></i>';
		elseif ($item['hit']==1) $html .= '<i class="icon best"></i>';
	$html .= '</div>
				<div class="details">
					<div class="desc editor">'.$item['short_description'].'</div>
					<ul class="buttons">';
					$html .= '<li>
							<form class="form-product-wishlist" action="/cart/addtocompare/" method="post">
								<input type="hidden" name="pid" value="'.$item['id'].'">
								<input type="submit" value="Отложить" class="btn wish">
							</form>
						</li>
						<li>
							<form class="form-product-buy" action="/cart/addtocart/" method="post">
								<input type="hidden" name="pid" value="'.$item['id'].'">
								<input type="submit" value="Купить" class="btn buy">
							</form>								
						</li>
					</ul>
				</div>					
			</li>';
	}
	$html .= '</ul>';			
	} else {
	$html .= '<ul class="grid">';
	foreach($collections as $item) {
	$html .= '<li>
				<div class="pict">
					<a href="/product/'.$item['path'].'/" class="name">'.get_product_name($item).'</a>
					<a href="/product/'.$item['path'].'/" class="image">
						<img src="'.$imagepath['small']['path'].insert_image($item['id']).'" alt="'.$item['name'].'">
					</a>
					<div class="buttons">
						<a href="/product/'.$item['path'].'/#pit-tab-3" class="kol-colors">'.get_count_colors($item['id'],true).'</a>
						<a href="#" class="status r'.$item['status'].'">'.get_status($item['status'],true).'</a>
						<a href="/product/'.$item['path'].'/" class="btn-details">Подробнее</a>';
						$html .= get_buttons_catalog($item['id']);
						if ($item['status'] != 4) {
							if ($item['cena_old']>0 or $item['cena_blr_old']>0) {
								$html .= '<span class="price price-old">
									<div class="cena-old">'.transform_to_currency_old($item,true,true).'</div>
									Цена: '.transform_to_currency($item).'
								</span>';
							} else {
								$html .= '<span class="price">Цена: '.transform_to_currency($item).'</span>';
							}
						} else{
							$html .= '<a href="#" class="status nvn">Снят с производства</a>';
						}
						
							
	$html .= '</div>';
		if ($item['new']==1) $html .= '<i class="icon new"></i>';
		elseif ($item['hit']==1)$html .= '<i class="icon best"></i>';
	$html .= '</div>
			</li>';
	}
	$html .= '</ul>';						
	}
	$html .= '<div class="toolbar bottom">
			<span class="pagination">'.@$pagination.'</span>';
	if (isset($_SESSION['rows_on_page'])) {
	$html .= '<span class="display">
				Показывать по: 
				<span>';
	for($rop=9;$rop<=27;$rop+=9) {
		if($rows_on_page==$rop) $cls = 'class="active"'; else $cls = '';
		$html .= '<a href="?rows_on_page='.$rop.$anchor.'" '.$cls.'>'.$rop.'</a>';
	}
	$html .= '</span>
			</span>';
	}
	$html .= '<span class="items-count">
				Показано '.$getLimitas.' (всего '.@$totals.' позиций)
			</span>
			<span class="b-back-top">
				<a id="back-top" href="#pinfo-top">Вверх</a>
			</span>				
		</div>';
	} else {
		$html .= '<div class="notfound">Товаров соответсвующих вышим параметрам не найдено. Попробуйте расширить параметры подбора или указать иные.</div>';
	}
	$html .= '</div>';
	return $html;
}


function get_print_ost() {
	$items = Database::getRows(get_table('catalog'));
	if (empty($items)) return;
		
	$host = $_SERVER['HTTP_HOST'];
	$html = '';

	foreach($items as $item) {
	
		$ros = get_real_ostatok_sklad($item['id']);

		if ($ros > 0) {
			$html .= '<tr>';
			$html .= '<td>'.get_product_name($item,true).'</td>';
			$html .= '<td>'.$ros.'</td>';
			$html .= '</tr>';				
		}
		
				
	}
	
	return $html;				
}

 
function get_char_description($id_catalog) {

//$tree_item = get_tree_item($id_catalog);
$product = Database::getRow(get_table('catalog'),$id_catalog);
$id_char = $product['id_char'];
$char_group = Characteristics_group::getCollections($id_char);
$charactrform = "";
			
	foreach ($char_group as $item) {
			
		foreach (Characteristics::getCollectionsByShowCatalog($item['id']) as $char_item) {
					
			if ($char_item['tip']==1) {
			
				$charvalue = Catalog_characteristics::getCollectionCNI($id_catalog, "text", $char_item['id']);
				
				if (($char_item['name']!="Длина") and ($char_item['name']!="Ширина") and ($char_item['name']!="Высота")) {	
					$form_item = $char_item['name'].": ".@$charvalue['value']." ".@$char_item['sufix'];	
				} else {
					if ($char_item['name']=="Длина") {
						$value_length = @$charvalue['value'].'x';
						$znak_length = "(Дх";
					}
					if ($char_item['name']=="Высота") {
						$value_height = @$charvalue['value'].'x'; 
						$znak_height = "Вх";
					}
					if ($char_item['name']=="Ширина") {
						$value_width = @$charvalue['value']." ".@$char_item['sufix'];
						$znak_width = "Ш)";
					}
					if ((!empty($value_length)) and (!empty($value_height)) and (!empty($value_width))) {
						$charactrform .= "<div class='g-char'><span>-</span> <span class='g-span'>Габаритные размеры (ДхВхШ): ".@$value_length.@$value_height.@$value_width.@$znaki."</span></div>";
						$value_length = "";
						$value_height = "";
						$value_width = "";
					}
				}			
			}
							
			if ($char_item['tip']==2) {
				
				$charvalue = Catalog_characteristics::getCollectionCNI($id_catalog, "radio", $char_item['id']);
				
				if (@$charvalue['value']==1) { $form_item = $char_item['name'].": ".'есть';	} else { $form_item = $char_item['name'].": ".'нет'; }
														
			}	
							
			if ($char_item['tip']==3) {

				foreach (Characteristics_group_tip::getTipsByIdCharacter($char_item['id']) as $tip_item) {
					
					$charvalue = Catalog_characteristics::getCollectionCNI($id_catalog, "select", $char_item['id']);	
										
					if (@$charvalue['value']==$tip_item['id']) { $form_item = $char_item['name'].": ".$tip_item['name'].' '.@$char_item['sufix'];; } 
					
				}	
			
			}
							
			if ($char_item['tip']==4) {

				$form_item = $char_item['name'].": ";
				
				foreach (Characteristics_group_tip::getTipsByIdCharacter($char_item['id']) as $tip_item) {
									
					$charvalue = Catalog_characteristics::getCollectionCNI($id_catalog, "checkbox", $tip_item['id']);	
					
					if (@$charvalue['value']=="on") { $form_item .= $tip_item['name'].', '; } 				
				
				}
				
				$form_item = substr($form_item,0,strlen($form_item)-2); 
							
			}

			if (@$form_item != "") {
				$charactrform .= $form_item.'; ';	
				$form_item = '';
			}	
		}
			
	}
		
return $charactrform;
		
}

	
function refresh_ostatki() {
	Database::getSQL("UPDATE ".get_table('catalog')." SET `kolvo`=0 WHERE 1");
	$sklad_tovars = Database::getRows(get_table('sklad_tovar'),'id','asc',false,'status=2');
	foreach($sklad_tovars as $item) {
		$delivery_tmc = Database::getRow(get_table('delivery_tmc'),$item['id_delivery_tmc']);

			if ($delivery_tmc['return_tmc'] == 1 or $delivery_tmc['block_edit'] == 0) continue;	
		
			$product = Database::getRow(get_table('catalog'),$item['id_item']);
			if (empty($product)) continue;
			$new_kolvo = $product['kolvo'] + $item['kolvo_hold'];	
			Database::update(get_table('catalog'),array('kolvo'=>$new_kolvo),'id='.$product['id']);
		
	}
	
	$items = Database::getRows(get_table('zakaz'));
	foreach($items as $item) {
		$new_kolvo = 0;
		$product = Database::getRow(get_table('catalog'),$item['id_item']);
		if (empty($product)) continue;		
		if ($item['was']==1 and $item['delivered']==1) {
			if ($item['vozvrat']==1) {
				$new_kolvo = $product['kolvo'];
			} else {
				$new_kolvo = $product['kolvo'] - $item['kolvo'];
			}
			
			Database::update(get_table('catalog'),array('kolvo'=>$new_kolvo),'id='.$product['id']);
		}
	}
	$message = "Остатки обновлены!";
	return json_encode($message);
}


function get_total_price($id_item) {
	$sklad_tovars = Database::getRows(get_table('sklad_tovar'),'id','desc',false,'id_item='.$id_item.' and status=2');
	$cena_ye = 0;
	$kolvo = get_real_ostatok_sklad($id_item);

	foreach($sklad_tovars as $st) {

		if ($st['kolvo_hold'] >= $kolvo) {	
			$d_tmc = Database::getRow(get_table('delivery_tmc'),$st['id_delivery_tmc']);
			if (isset($d_tmc) and !empty($d_tmc['id'])) {
				$valute = $d_tmc['id_valute'];
				$kurs = $d_tmc['kurs'];
				$cena_ye += transform_to_kurs($st['cena'],$valute,$kurs) * $kolvo;
				return $cena_ye;
			}			
		} else {
			$d_tmc = Database::getRow(get_table('delivery_tmc'),$st['id_delivery_tmc']);
			if (isset($d_tmc) and !empty($d_tmc['id'])) {
				$valute = $d_tmc['id_valute'];
				$kurs = $d_tmc['kurs'];
				$cena_ye += transform_to_kurs($st['cena'],$valute,$kurs) * $st['kolvo_hold'];
				$kolvo -= $st['kolvo_hold'];
			}			
		}

	}
	
	return $cena_ye;

}

function get_print_postavki($id) {
	$items = Database::getRows(get_table('sklad_tovar'),'id','asc',false,'id_item='.$id);
	if (empty($items)) return;
		
	$html = '';
	$html .= '<tr>';
	$html .= '<td>Дата поступления</td>';
	$html .= '<td>№ накладной</td>';
	$html .= '<td>Поставщик</td>';
	$html .= '<td>Количество</td>';
	$html .= '</tr>';
	
	$summa_kolvo = 0;
	
	foreach($items as $item) {
		
		$id_delivery_tmc = $item['id_delivery_tmc'];
		$delivery_tmc = Database::getRow(get_table('delivery_tmc'),@$id_delivery_tmc);
		if (empty($delivery_tmc) or $delivery_tmc['block_edit'] == 0) continue;	
		
		$postavshik = Database::getField(get_table('kontragenty'),$delivery_tmc['id_suppliers']);
		
		$summa_kolvo += $item['kolvo_hold'];
		
		$html .= '<tr>';
		$html .= '<td>'.transform_norm_date($delivery_tmc['date_delivery']).'</td>';
		$html .= '<td>'.$delivery_tmc['nomer_nakladnoy'].'</td>';
		$html .= '<td>'.$postavshik.'</td>';
		$html .= '<td>'.$item['kolvo_hold'].'</td>';
		$html .= '</tr>';
		
	}
	
	$html .= '<tr>';
	$html .= '<td colspan=3></td>';
	$html .= '<td>'.$summa_kolvo.'</td>';
	$html .= '</tr>';
	
	return $html;				
}
