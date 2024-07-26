<?php
require "classes/Database.php";
require "includes/function.php";

$userAdmin = isAdmin();
$db = new Database();
$conn = $db->getDb();

?>

<?php $title = "admin";
include 'includes/header.php' ?>
<?php include 'includes/navbar.php' ?>

<div>
  <h2 class="text-center fs-1 fw-bold mt-3" style="color:#6D214F;"> ADMIN </h2>
</div>

<div class="container mt-3">
  <div class="row">
    <div class="col-12 col-md-3">
      <div class="card shadow border mb-3">
        <div class="card-header text-center text-light bg-success fw-bold">MANAGE USER</div>
        <a class="text-decoration-none list-group-item-action list-group-item-success" href="manage_user.php">
          <div class="card-body text-secondary text-center">
            <span>Add, Edit and Delete Users</span>
          </div>
        </a>
      </div>
    </div>
    <div class="col-12 col-md-3">
      <div class="card  shadow mb-3">
        <div class="card-header text-center text-light bg-primary fw-bold">ADD ITEMS</div>
        <a class="text-decoration-none list-group-item-action list-group-item-primary" href="items_add.php">
          <div class="card-body text-secondary text-center">
            <span>Add Products</span>
          </div>
        </a>
      </div>
    </div>

    <div class="col-12 col-md-3">
      <div class="card shadow mb-3">
        <div class="card-header text-center text-light bg-dark fw-bold">ITEMS LIST</div>
        <a class="text-decoration-none list-group-item-action list-group-item-dark" href="items_list.php">
          <div class="card-body text-secondary text-center">
            <span>Check & Edit items list</span>
          </div>
        </a>
      </div>
    </div>

    <div class="co-12 col-md-3">
      <div class="card shadow mb-3">
        <div class="card-header text-center text-light bg-danger fw-bold">CATEGORIES</div>
        <a class="text-decoration-none list-group-item-action list-group-item-danger" href="category.php">
          <div class="card-body text-secondary text-center">
            <span>CATEGORY ITEMS</span>
          </div>
        </a>
      </div>
    </div>

    <div class="co-12 col-md-3">
      <div class="card shadow mb-3">
        <div class="card-header text-center text-dark bg-warning fw-bold">CHECK CATEGORY DETAILS</div>
        <a class="text-decoration-none list-group-item-action list-group-item-warning" href="category_details.php">
          <div class="card-body text-secondary text-center">
            <span>check stock details</span>
          </div>
        </a>
      </div>
    </div>

    <div class="co-12 col-md-3">
      <div class="card shadow mb-3">
        <div class="card-header text-center text-dark bg-info fw-bold">CHECK IP-ADDRESS</div>
        <a class="text-decoration-none list-group-item-action list-group-item-info" href="ip_addressCheck.php">
          <div class="card-body text-secondary text-center">
            <span>check IP-ADDRESS</span>
          </div>
        </a>
      </div>
    </div>
    
  </div>
</div>

<?php include 'includes/script.php' ?>
<?php include 'includes/footer.php' ?>