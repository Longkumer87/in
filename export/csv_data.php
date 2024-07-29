<?php
require '../classes/Database.php';
require '../classes/Item.php';
require '../includes/function.php';

$admin = isAdmin();
$db = new Database();
$conn = $db->getDb();

header('Content-Type: text/csv');
header('Content-Disposition:attachment;filename=item_Csv.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['SlNo', 'CATEGORY', 'SERIAL NUMBER', 'INVOICE', 'RECEIVE FROM', 'RECEIVE DATE', 'TO', 'SECTION', 'IP ADDRESS', 'INSTALL DATE', 'REMARKS']);

$sql = "SELECT items.*, categories.cat_name , categories.cat_brand
FROM items 
INNER JOIN categories 
ON items.category_id = categories.cat_id 
WHERE `items`.`deleted`= 0";

$stmt = $conn->prepare($sql);
$stmt->execute();
$items= $stmt->fetchAll(PDO::FETCH_ASSOC);

$slno=1;

foreach($items as $item){
    fputcsv($output,[
        $slno++,
        $item['cat_name'] . ' - ' . $item['cat_brand'],
        $item['serialNumber'],
        $item['invoice'],
        $item['receiveFrom'],
        (new DateTime($item['receiveDate']))->format('d-m-Y'),
        $item['particularsTo'],
        $item['section'],
        $item['ipAddress'],
        $item['installDate'],
        $item['remarks']
    ]);
}

fclose($output);


