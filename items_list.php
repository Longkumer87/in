<?php
require 'classes/Database.php';
require 'classes/Category.php';
require 'classes/Item.php';
require 'includes/function.php';

$admin = isAdmin();
$db = new Database();
$conn = $db->getDb();

if (isset($_GET['page']) && is_numeric($_GET['page'])) {
  $page = (int) $_GET['page'];
} else {
  $page = 1;
}

$limit = 10;
$offset = ($page - 1) * $limit;

//class to get items inside the list
$items = Item::getItems($conn, $limit, $offset);

//class to get total number of items
$totalPages = Item::getItemsTotal($conn, $limit);

$slno = $offset + 1;

?>
<?php $title = "items_list";
include 'includes/header.php' ?>

<?php if ($items): ?>
  <?php include 'includes/navbar.php' ?>
  <div class="row-4 mt-2 d-flex align-items-center">
    <div class="col">
      <form action="item_search.php" method="post">
        <div class="row d-flex">
          <div class="col-md-6">
            <input class="form-control" placeholder="Search" id="search" name="search">
          </div>
          <div class="col-md-2">
            <input class="btn btn-outline-success" type="submit" id="submit" name="submit">
          </div>
          <div class="col-md-4 ">
            <a class="btn btn-outline-primary" href="items_add.php" role="button">Add More</a>
          </div>
        </div>
      </form>
    </div>
    <div class="col ">
      <div class="container text-center">
        <span class="fs-2 fw-bold">ITEM LIST</span>
      </div>
    </div>
    <div class="col">
      <div class="container text-center">
      <a class="btn btn-sm btn-outline-danger" href="export/csv_data.php" role="button">csv</a>
      <a class="btn btn-outline-secondary btn-sm " href="export/excel_data.php" role="button">excel</a>
      </div>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-hover table-bordered border-dark mt-3">
      <thead>
        <tr class="table-dark">
          <th scope="col">SlNo</th>
          <th scope="col">CATEGORY</th>
          <th scope="col">SERIAL NUMBER</th>
          <th scope="col">INVOICE</th>
          <th scope="col">RECEIVE FROM</th>
          <th scope="col">RECEIVE DATE</th>
          <th scope="col">TO</th>
          <th scope="col">SECTION</th>
          <th scope="col">IP ADDRESS</th>
          <th scope="col">INSTALL DATE</th>
          <th scope="col">REMARKS</th>
          <th scope="col">Mode</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
          <tr>
            <th scope="row"><?= $slno++; ?></th>
            <td><?= htmlspecialchars($item['cat_name']) . ' - ' . htmlspecialchars($item['cat_brand']); ?></td>
            <td><?= htmlspecialchars($item['serialNumber']); ?></td>
            <td><?= htmlspecialchars($item['invoice']); ?></td>
            <td><?= htmlspecialchars($item['receiveFrom']); ?></td>
            <td><?= (new DateTime($item['receiveDate']))->format('d-m-Y'); ?></td>
            <td><?= htmlspecialchars($item['particularsTo']); ?></td>
            <td><?= htmlspecialchars($item['section']); ?></td>
            <td><?= htmlspecialchars($item['ipAddress']); ?></td>
            <td><?= htmlspecialchars($item['installDate']); ?></td>
            <td><?= htmlspecialchars($item['remarks']); ?></td>
            <td>
              <div class="d-flex">
                <div class="col">
                  <a class="btn btn-outline-info" href="item_edit.php?id=<?= $item['id'] ?>" role="button">Edit</a>
                </div>
                <div class="col">
                  <a class="btn btn-outline-danger" href="item_delete.php?id=<?= $item['id'] ?>" role="Delete">Delete</a>
                </div>
              </div>

            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

<?php else: ?>
  <p> No items found</p>
<?php endif; ?>


<nav aria-label="Page navigation">
  <ul class="pagination justify-content-center mt-5">
  <!--------- Previous   --------->
  <?php if ($page > 1): ?>
      <li class="page-item">
        <a class="page-link" href="?page=<?= $page - 1; ?>">Previous</a>
      </li>
    <?php endif; ?>
    <!----------- Page number ------------->
    <?php for ($i = 1; $i < $page; $i++): ?>
      <li class="page-item<?=$i==$page? 'active' : ''; ?>">
        <a class="page-link" href="?page=<?=$i;?>"><?=$i;?></a>
      </li>
    <?php endfor; ?>
    <!--------------- Next --------------->
    <?php if($page <= $totalPages):?>
    <li class="page-item">
      <a class="page-link" href="?page=<?=$page+1;?>">Next</a>
    </li>
    <?php endif;?>
  </ul>
</nav>

<?php include 'includes/script.php' ?>
<?php include 'includes/footer.php' ?>