<?php
require 'classes/Database.php';
require 'classes/Category.php';
require 'classes/Item.php';
require 'includes/function.php';

$admin = isAdmin();
$db = new Database();
$conn = $db->getDb();

$slno = 1;

//class Categories to fatch all the given values 
$invntCategories = Category::getCatDetails($conn);

//count for stock
$sqlCount = "SELECT `categories`.`cat_name`, `categories`.`cat_brand`, 
    COUNT(CASE WHEN DAY(`items`.`installDate`) != '00' THEN 1 END) AS in_use_count, 
    COUNT(CASE WHEN (DAY(`items`.`installDate`) = '00' OR `items`.`installDate` IS NULL) 
          AND (`items`.`receiveDate` IS NOT NULL OR `items`.`receiveFrom` IS NOT NULL) 
          THEN 1 END) AS in_stock_count, 
     COALESCE(COUNT(CASE WHEN DAY(`items`.`installDate`) != '00' THEN 1 END) 
          + COUNT(CASE WHEN (DAY(`items`.`installDate`) = '00' OR `items`.`installDate` IS NULL) 
          AND (`items`.`receiveDate` IS NOT NULL OR `items`.`receiveFrom` IS NOT NULL) THEN 1 END), 0) AS total_count  
FROM `categories` 
LEFT JOIN `items` 
ON `categories`.`cat_id` = `items`.`category_id` 
GROUP BY `categories`.`cat_name`, `categories`.`cat_brand`
ORDER BY `cat_id`";
try {
  $stmtCount = $conn->prepare($sqlCount);
  $stmtCount->execute();
  $inventCounts = $stmtCount->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
  echo 'Error' . $e->getMessage();
}

?>
<?php $title = "category_details"; ?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<?php if ($inventCounts): ?>
  <div class="container mt-3" style="width: 50%;">
    <table class="table table-bordered border-dark">
      <thead>
        <tr class="table table-warning">
          <th scope="col">SlNo</th>
          <th scope="col">CATEGORY</th>
          <th scope="col">MANUFACTURE</th>
          <th scope="col">IN USE</th>
          <th scope="col">IN STOCK</th>
          <th scope="col">TOTAL PRODUCTS</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($inventCounts as $inventCount): ?>
          <tr class="text-center">
            <th scope="row"><?= $slno++; ?></th>
            <td><?= htmlspecialchars($inventCount['cat_name']) ?></td>
            <td><?= htmlspecialchars($inventCount['cat_brand']) ?></td>
            <td class="fw-bold" style="color:darkgreen"><?= htmlspecialchars($inventCount['in_use_count']) ?></td>
            <td><?= htmlspecialchars($inventCount['in_stock_count']) ?></td>
            <td><?= htmlspecialchars($inventCount['total_count']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php else: ?>
  <span> No items Added</span>
<?php endif; ?>

<?php include 'includes/script.php'; ?>
<?php include 'includes/footer.php'; ?>