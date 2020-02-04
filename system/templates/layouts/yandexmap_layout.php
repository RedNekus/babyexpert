<?php 
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="<?php echo date('Y-m-d H:i'); ?>">

<shop>

<name>babyexpert.by</name>

<company>Интернет-магазин babyexpert.by</company>

<url>http://babyexpert.by</url>

<currencies>

<currency id="BYR" rate="1"/>

</currencies>

<categories>

<?php 
foreach($trees as $tree) {
	if($tree['pid']>0) $parentId = 'parentId="'.$tree['pid'].'"'; else $parentId = '';
	echo '<category id="'.$tree['id'].'" '.$parentId.'>'.htmlspecialchars($tree['name']).'</category>';
} 
?>

</categories>


<offers>
<?php foreach($products as $product): ?>

<offer id="<?php echo $product['id']; ?>" available="true" type="vendor.model">

<url>http://babyexpert.by/product/<?php echo $product['path']; ?>/</url>

<price><?php echo transform_to_currency($product,false); ?></price>

<currencyId>BYR</currencyId>

<?php 
if (!empty($product['id_razdel5'])) $cat_id = $product['id_razdel5']; 
	else if (!empty($product['id_razdel4'])) $cat_id = $product['id_razdel4'];
		else if (!empty($product['id_razdel3'])) $cat_id = $product['id_razdel3'];
			else if (!empty($product['id_razdel2'])) $cat_id = $product['id_razdel2'];
				else if (!empty($product['id_razdel1'])) $cat_id = $product['id_razdel1'];
				
?>

<categoryId><?php echo @$cat_id; ?></categoryId>

<delivery>true</delivery>

<vendor>
<?php 
	$maker = Database::getRow(get_table('maker'),$product['id_maker']);
	echo htmlspecialchars(@$maker['name']); 
?>
</vendor>

<model><?php echo htmlspecialchars($product['name']); ?></model>

<description><?php echo htmlspecialchars($product['description']); ?></description>

<manufacturer_warranty>true</manufacturer_warranty>

<country_of_origin><?php echo htmlspecialchars(@$maker['country']); ?></country_of_origin>

</offer>

<?php endforeach; ?>

</offers>

</shop>

</yml_catalog>