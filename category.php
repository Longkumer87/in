<?php
require "classes/Database.php";
require "classes/Category.php";
require "includes/function.php";

$userAdmin = isAdmin();
$db = new Database();
$conn = $db->getDb();

$error_message = [];
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
  $page = (int) $_GET['page'];
} else {
  $page = 1;
}
$limit = 10;
$offset = ($page - 1) * $limit;

//class getting Category total
$totalCatPages = Category::getCatTotal($conn, $limit);

$slno = $offset + 1;

//Adding Categories from form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $submit = $_POST['submit'];
  $cat_name = htmlspecialchars(strtoupper($_POST['cat_name']));
  $cat_brand = htmlspecialchars(strtoupper($_POST['cat_brand']));
  if (isset($submit)) {
    if (empty($cat_name)) {
      $error_message[] = "Please Add product name";
    }
    if (empty($cat_brand)) {
      $cat_brand = "NA";
    }
  }
  if (empty($error_message)) {
    $sqlExist = "SELECT * 
                  FROM `categories`
                  WHERE `cat_name`=:cat_name 
                  AND `cat_brand`= :cat_brand";
    $stmtExist = $conn->prepare($sqlExist);
    $stmtExist->bindParam(':cat_name', $cat_name, PDO::PARAM_STR);
    $stmtExist->bindParam(':cat_brand', $cat_brand, PDO::PARAM_STR);
    $stmtExist->execute();
    $catExist = $stmtExist->fetchAll(PDO::FETCH_ASSOC);
    if (count($catExist) > 0) {
      $error_message[] = "Category Already exist";
    } else {
      $sqlInsert = "INSERT INTO  `categories`(`cat_name`, `cat_brand`)
      Value (:cat_name, :cat_brand)";
      $stmtInsert = $conn->prepare($sqlInsert);
      $stmtInsert->bindParam(':cat_name', $cat_name, PDO::PARAM_STR);
      $stmtInsert->bindParam(':cat_brand', $cat_brand, PDO::PARAM_STR);
      if ($stmtInsert->execute()) {
        header('Location: ' . $_SERVER['PHP_SELF']);
      }
    }
  }
}

//to display or fetch all the added categories 
$categories = Category::getCategory($conn, $limit, $offset);


?>

<?php
$title = "categorylist";
include 'includes/header.php';
?>

<?php include 'includes/navbar.php'; ?>

<span class="text-center fw-bold fs-1 mt-3 text-danger"> CATEGORIES </span>

<?php if ($error_message): ?>
  <ul>
    <?php foreach ($error_message as $err): ?>
      <li><?= $err; ?></li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>


<div class="container mt-4 border border-dark p-3" style="width: 50%;">
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <div class="mb-3">
      <label for="cat_name" class="form-label fw-bold">Add Category</label>
      <input type="text" class="form-control" id="cat_name" name="cat_name">
    </div>

    <div class="mb-3">
      <label for="cat_brand" class="form-label fw-bold">Manufacture</label>
      <input type="text" class="form-control" id="cat_brand" name="cat_brand">
    </div>

    <div class="mb-3">
      <label for="cat_id" class="visually-hidden">cat_id</label>
      <input type="hidden" class="form-control" id="cat_id" name="cat_id">
    </div>

    <input type="submit" name="submit" class="btn btn-primary float-end" value="submit">
  </form>
</div>


<?php if ($categories): ?>
  <div class="container mt-3 float-center">
    <table class="table-responsive">
      <table class="table table-border border-dark shadow-lg">
        <thead>
          <tr class="table-danger">
            <th scope="col">SlNo</th>
            <th scope="col">CATEGORY</th>
            <th scope="col">MANUFACTURE</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($categories as $category): ?>
            <tr>
              <th scope="row"><?= $slno++; ?> </th>
              <td><?= $category['cat_name']; ?></td>
              <td><?= $category['cat_brand']; ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </table>
  </div>
<?php else: ?>
  <p> </p>
<?php endif; ?>

<nav aria-label="Page navigation">
  <ul class="pagination justify-content-center mt-4">
    <?php if ($page > 1): ?>
      <li class="page-item"><a class="page-link" href="?page=<?= $page - 1; ?>">Previous</a></li>
    <?php endif; ?>
    <?php for ($i = 1; $i < $totalCatPages; $i++): ?>
      <li class="page-item<?= $i == $page ? 'active' : ''; ?>">
        <a class="page-link" href="?page=<?=$i; ?>"><?=$i; ?></a>
      </li>
    <?php endfor; ?>

    <?php if ($page < $totalCatPages): ?>
      <li class="page-item"><a class="page-link" href="?page=<?= $page + 1; ?>">Next</a></li>
    <?php endif; ?>
  </ul>
</nav>

<?php include 'includes/script.php' ?>
<?php include 'includes/footer.php' ?>