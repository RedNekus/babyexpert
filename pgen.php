<?php
set_time_limit(0);

// Config
$config = array(
	'db_host'          => 'localhost',                                     	// Изменить - Хост БД
	'db_username'      => 'babyexpert_user',                                // Изменить - Пользователь БД
	'db_password'      => 'Hei1feib',                                      	// Изменить - Пароль БД
	'db_database'      => 'babyexpert_newbd',                            		// Изменить - Назваине БД
	'export_file_path' => realpath(dirname(__FILE__)), // Должен указывать на DOCUMENT ROOT для домена teplichnoe.by
	'export_file_name' => md5('babyexpert.by') . '.csv',
);


// Database connection
try {
    $dbh = new PDO(
        'mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_database'],
        $config['db_username'],
        $config['db_password'],
		array(
			PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8' COLLATE utf8_general_ci",
		)
    );
} catch (PDOException $e) {
    echo "Failed to connect to the database (" . $e->getMessage() . ")" . PHP_EOL;
    die();
}


// Retrieve products
$stmt = $dbh->query("
    SELECT
        p.id, p.cena, p.status, p.active, IF(p.cena_blr, p.cena_blr, 0), IF(c.kurs, c.kurs, 0)
    FROM
        np_catalog AS p
            LEFT JOIN np_currency_tree AS c ON c.id = p.id_currency
");

if ($stmt === false) {
	echo "Failed to retrieve products" . PHP_EOL;
	die();
}

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();


// Write products to file
$filePath = $config['export_file_path'] . DIRECTORY_SEPARATOR . $config['export_file_name'];
$file     = fopen($filePath, 'w');

if ($file === false) {
	echo "Failed to open file " . $filePath . PHP_EOL;
	die();
}

foreach ($products as $product) {
	fputcsv($file, $product);
}

fclose($file);


// Compress file
$file = gzopen($filePath . '.gz', 'w9');
gzwrite($file, file_get_contents($filePath));
gzclose($file);
unlink($filePath);
