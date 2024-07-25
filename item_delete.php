<?php
require "classes/Database.php";
require "classes/Category.php";
require "classes/Item.php";
require "includes/function.php";

$userAdmin = isAdmin();
$db = new Database();
$conn = $db->getDb();

$id = $_GET['id'];

// gettin the data from the add item
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $item= Item::getItem($conn);

} else {
  $item = null;
  header("Location:404.php");
  exit;
}

//deleting data from the list

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'];
  $delete = $_POST['delete'];
  if (isset($delete)) {
      $sqlDelete = "UPDATE  `items`
                    SET `deleted` = 1 
                    WHERE `id`= :id";

      $stmtDelete = $conn->prepare($sqlDelete);
      $stmtDelete->bindParam(':id', $id, PDO::PARAM_INT);
      if ($stmtDelete->execute()) {
        header("Location:items_list.php");
        exit;
      }
    
  }
}

?>

<?php $title = "item Delete";
require 'includes/header.php' ?>
<?php require 'includes/navbar.php' ?>

<!-- card to display item data -->
<?php if ($item): ?>

  <span class="text-center fs-2 fw-bold text-danger mt-3"> Are you sure you want to Delete? </span>

  <div class="container mt-3">
    <div class="d-flex justify-content-center">
      <div class="card" style="width:500px;">
        <div class="card-body">
          <p class="card-title text-center fw-bold fs-3"><?= htmlspecialchars($item['particularsTo']); ?></p>
          <ul class="list-group list-group-flush">
            <li class="list-group-item"><span>IP Address:</span>
              <?= htmlspecialchars($item['ipAddress']); ?></li>
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

          <div class="container mt-3">
          <form action="<?= $_SERVER['PHP_SELF'].'?id='.$id; ?>" method="post">
              <div class="row">
                <div class="col bg-success">
                  <a class="btn btn-warning fs-5 border border-dark" href="items_list.php" role="button">Cancle</a>
                </div>
                <div class="col bg-primary text-end">
                <button type="submit" id="delete" name="delete" class="btn btn-danger fs-5 border border-dark">Delete</button>
                </div>
                <div class="mb-3">
                <input type="hidden" class="form-control" id="id" name="id" value="<?= htmlspecialchars($item['id']); ?>">
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php else: ?>
  <span> No item found </span>
<?php endif; ?>

<?php require 'includes/script.php' ?>
<?php require 'includes/footer.php' ?>