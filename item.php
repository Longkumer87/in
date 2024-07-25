<?php
require "classes/Database.php";
require "classes/Category.php";
require "classes/Item.php";
require "includes/function.php";

$userAdmin = isAdmin();
$db = new Database();
$conn = $db->getDb();

// gettin the data from the add item
if (isset($_GET['id']) && is_numeric($_GET['id'])) {

  $item = Item::getItem($conn);
} else {
  $item = null;
  header("Location:404.php");
  exit();
}

?>

<?php $title = "item";
require 'includes/header.php' ?>
<?php require 'includes/navbar.php' ?>

<!-- card to display item data -->
<?php if ($item): ?>
  <div class="container mt-3" style="background-color:blueviolet">
    <div class="d-flex justify-content-center">
      <div class="card" style="width:500px;">
        <div class="card-body">
          <p class="card-title text-center fw-bold fs-3"><?= htmlspecialchars($item['particularsTo']); ?></p>
          <ul class="list-group list-group-flush">
          <li class="list-group-item"><span>IP Address:</span>
          <?= htmlspecialchars($item['serialNumber']); ?></li>
            <li class="list-group-item"><span>ITEMS TYPE:</span> <?= htmlspecialchars($item['cat_name']); ?> -
              <?= htmlspecialchars($item['cat_brand']); ?>
            </li>
            <li class="list-group-item"><span>SERIAL NUMBER:</span>
              <?= htmlspecialchars($item['serialNumber']); ?></li>
            <li class="list-group-item"><span>INVOICE:</span> <?= htmlspecialchars($item['invoice']); ?></li>
            <li class="list-group-item"><span>RECEIVE FROM:</span>
              <?= htmlspecialchars($item['receiveFrom']); ?></li>
            <li class="list-group-item"><span>RECEIVE DATE:</span>
              <?= htmlspecialchars($item['receiveDate']); ?></li>
            <li class="list-group-item"><span>INSTALL DATE:</span>
              <?= htmlspecialchars($item['installDate']); ?></li>
            <li class="list-group-item"><span>SECTION:</span> <?= htmlspecialchars($item['section']); ?></li>
            <li class="list-group-item"><span>REMARKS:</span> <?= htmlspecialchars($item['remarks']); ?></li>
          </ul>
        </div>
      </div>
    </div>
  </div>

<?php else: ?>
  <span> No item found </span>
<?php endif; ?>

<?php require 'includes/script.php' ?>
<?php require 'includes/footer.php' ?>