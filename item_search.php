<?php
require 'classes/Database.php';
require 'classes/Category.php';
require 'classes/Item.php';
require 'includes/function.php';

$admin = isAdmin();
$db = new Database();
$conn = $db->getDb();

$error_message = [];
$slno = 1;
$submit = '';
$items = '';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $submit = $_POST['submit'];
    $search = $_POST['search'];
    if (isset($submit)) {
        if (empty($search)) {
            $error_message[] = "Please Insert Value";
        } else {
            try {
                $sqlSearch = "SELECT items.*, categories.cat_name, categories.cat_brand 
                              FROM items 
                              INNER JOIN categories ON categories.cat_id = items.category_id
                              WHERE cat_name LIKE :search 
                              OR cat_brand LIKE :search 
                              OR serialNumber LIKE :search 
                              OR particularsTo LIKE :search";

                $stmtSearch = $conn->prepare($sqlSearch);
                $searchParam = "%" . $search . "%";
                $stmtSearch->bindParam(':search', $searchParam, PDO::PARAM_STR);
                $stmtSearch->execute();
                $items = $stmtSearch->fetchAll(PDO::FETCH_ASSOC);
                if (!$items) {
                    $error_message[] = "No items found";
                }
            } catch (Exception $e) {
                $error_message[] = "Unable to find the Item: " . $e->getMessage();
            }
        }
    }
}
?>
<?php $title = "search"; ?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="row-4 d-flex align-items-center mt-2">
    <div class="col-4 ">
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
            <div class="row-4 d-flex mt-2">
                <div class="col col-sm-12 col-md-6 ">
                    <input class="form-control" placeholder="Search" id="search" name="search">
                </div>
                <div class="col col-md-3 text-center">
                    <input class="btn btn-success" type="submit" id="submit" name="submit">
                </div>
                <div class="col col-md-3 ">
                    <a class="btn btn-primary" href="items_add.php" role="button">Add More</a>
                </div>
            </div>
        </form>
    </div>
    <div class="col-4 text-center">
        <span class="fs-2 fw-bold ">ITEM LIST</span>
    </div>
    <div class="col-4">
    <div class="col">
      <div class="container text-center">
      <a class="btn btn-sm btn-outline-danger" href="export/csv_data.php" role="button">csv</a>
      <a class="btn btn-outline-secondary btn-sm " href="export/excel_data.php" role="button">excel</a>
      </div>
    </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered border-dark mt-2">
        <thead>
            <tr>
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
        <?php if ($items): ?>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <th scope="row"><?= $slno++; ?></th>
                        <td><?= htmlspecialchars($item['cat_name']) . ' - ' . htmlspecialchars($item['cat_brand']); ?></td>
                        <td><?= htmlspecialchars($item['serialNumber']); ?></td>
                        <td><?= htmlspecialchars($item['invoice']); ?></td>
                        <td><?= htmlspecialchars($item['receiveFrom']); ?></td>
                        <td><?= htmlspecialchars($item['receiveDate']); ?></td>
                        <td><?= htmlspecialchars($item['particularsTo']); ?></td>
                        <td><?= htmlspecialchars($item['section']); ?></td>
                        <td><?= htmlspecialchars($item['ipAddress']); ?></td>
                        <td><?= htmlspecialchars($item['installDate']); ?></td>
                        <td><?= htmlspecialchars($item['remarks']); ?></td>
                        <td>
                            <div class="d-flex">
                                <div class="col">
                                    <a class="btn btn-outline-info" href="item_edit.php?id=<?= $item['id'] ?>"
                                        role="button">Edit</a>
                                </div>
                                <div class="col">
                                    <a class="btn btn-outline-danger" href="item_delete.php?id=<?= $item['id'] ?>"
                                        role="Delete">Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        <?php else: ?>
            <p> </p>
        <?php endif; ?>
    </table>
</div>
<?php if ($error_message): ?>
    <?php foreach ($error_message as $err): ?>
        <p><?= $err; ?></p>
    <?php endforeach; ?>
<?php endif; ?>

<?php include 'includes/script.php'; ?>
<?php include 'includes/footer.php'; ?>