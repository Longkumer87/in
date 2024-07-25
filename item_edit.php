<?php
require 'classes/Database.php';
require 'classes/Category.php';
require 'classes/Item.php';
require 'includes/function.php';

$admin = isAdmin();
$db = new Database();
$conn = $db->getDb();

$error_message = [];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $item = Item::getItem($conn); 
    $categories = Category::getCategories($conn);

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $submit = $_POST['submit'];
        $id = $_POST['id'];
        $category_id = htmlspecialchars($_POST['category_id']);
        $serialNumber = htmlspecialchars(strtoupper($_POST['serialNumber']));
        $invoice = htmlspecialchars(strtoupper($_POST['invoice']));
        $receiveFrom = htmlspecialchars(strtoupper($_POST['receiveFrom']));
        $receiveDate = $_POST['receiveDate'];
        $particularsTo = htmlspecialchars(strtoupper($_POST['particularsTo']));
        $section = htmlspecialchars(strtoupper($_POST['section']));
        $ipAddress = htmlspecialchars($_POST['ipAddress']);
        $installDate = $_POST['installDate'];
        $remarks = htmlspecialchars($_POST['remarks']);

        // Validation
        if (empty($category_id)) {
            $error_message[] = "Select a category";
        }
        if (empty($serialNumber)) {
            $error_message[] = "Add Product Serial Number";
        }
        if (empty($invoice)) {
            $invoice = "NA";
        }
        if (empty($receiveFrom)) {
            $error_message[] = "Vendor Details required";
        }
        if (empty($receiveDate)) {
            $error_message[] = "Please Enter the Date";
        }
        if (empty($particularsTo)) {
            $particularsTo = "NA";
        }
        if (empty($section)) {
            $section = "NA";
        }
        if (empty($ipAddress)) {
            $ipAddress = null;
        }
        if(empty($installDate)){
            $installDate = null;
        }
        if (empty($remarks)) {
            $remarks = "";
        }

        if (empty($error_message)) {
            // Check if serial number or IP address already exists
            $sqlExist = "SELECT * 
                         FROM `items` 
                         WHERE (`serialNumber` = :serialNumber OR `ipAddress` = :ipAddress) 
                         AND `id` != :id";
            $stmtExist = $conn->prepare($sqlExist);
            $stmtExist->bindParam(':serialNumber', $serialNumber, PDO::PARAM_STR);
            $stmtExist->bindParam(':ipAddress', $ipAddress, PDO::PARAM_STR);
            $stmtExist->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtExist->execute();
            $itemsExist = $stmtExist->fetchAll(PDO::FETCH_ASSOC);

            if (count($itemsExist) > 0) {
                $error_message[] = "Serial Number or IP Address already exists";
            } else {
                try {
                    // Update item in the database
                    $sqlUpdate = "UPDATE `items` 
                                 SET `category_id` = :category_id, `serialNumber` = :serialNumber, `invoice` = :invoice, `receiveFrom` = :receiveFrom, `receiveDate` = :receiveDate, `particularsTo` = :particularsTo, `section` = :section, `ipAddress` = :ipAddress, `installDate` = :installDate, `remarks` = :remarks 
                                 WHERE `items`.`id` = :id";
                    $stmtUpdate = $conn->prepare($sqlUpdate);
                    $stmtUpdate->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmtUpdate->bindParam(':category_id', $category_id, PDO::PARAM_INT);
                    $stmtUpdate->bindParam(':serialNumber', $serialNumber, PDO::PARAM_STR);
                    $stmtUpdate->bindParam(':invoice', $invoice, PDO::PARAM_STR);
                    $stmtUpdate->bindParam(':receiveFrom', $receiveFrom, PDO::PARAM_STR);
                    $stmtUpdate->bindParam(':receiveDate', $receiveDate, PDO::PARAM_STR);
                    $stmtUpdate->bindParam(':particularsTo', $particularsTo, PDO::PARAM_STR);
                    $stmtUpdate->bindParam(':section', $section, PDO::PARAM_STR);
                    $stmtUpdate->bindParam(':ipAddress', $ipAddress, PDO::PARAM_STR);
                    $stmtUpdate->bindParam(':installDate', $installDate, PDO::PARAM_STR);
                    $stmtUpdate->bindParam(':remarks', $remarks, PDO::PARAM_STR);

                    if ($stmtUpdate->execute()) {
                        header("Location:items_list.php");
                    } else {
                        $error_message[] = "Unable to update";
                    }
                } catch (Exception $e) {
                    $error_message[] = "Error: " . $e->getMessage();
                }
            }
        }
    }
} else {
    $item = null;
}
?>

<?php $title = "item_edit"; ?>
<?php include 'includes/header.php'; ?>

<?php if ($item): ?>
    <?php include 'includes/navbar.php'; ?>

    <span class="fs-1 fw-bold text-center mt-4">EDIT ITEMS</span>
    <div class="container">
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($error_message as $message): ?>
                        <li><?= htmlspecialchars($message); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="mb-3">
                <label for="category" class="form-label">CATEGORY</label>
                <select class="form-select" id="category_id" name="category_id">
                    <option value="<?= htmlspecialchars($item['category_id']); ?>" selected>
                        <?= htmlspecialchars($item['cat_name']); ?> - <?= htmlspecialchars($item['cat_brand']); ?>
                    </option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category['cat_id']); ?>">
                            <?= htmlspecialchars($category['cat_name']); ?> - <?= htmlspecialchars($category['cat_brand']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="serialNumber" class="form-label"><span class="text-danger fw-bold">*</span> SERIAL NUMBER</label>
                <input type="text" class="form-control" id="serialNumber" name="serialNumber" value="<?= htmlspecialchars($item['serialNumber']); ?>">
            </div>

            <div class="mb-3">
                <label for="invoice" class="form-label">INVOICE NUMBER</label>
                <input type="text" class="form-control" id="invoice" name="invoice" value="<?= htmlspecialchars($item['invoice']); ?>">
            </div>

            <div class="mb-3">
                <label for="receiveFrom" class="form-label"><span class="text-danger fw-bold">*</span> RECEIVE FROM</label>
                <input type="text" class="form-control" id="receiveFrom" name="receiveFrom" value="<?= htmlspecialchars($item['receiveFrom']); ?>">
            </div>

            <div class="mb-3">
                <label for="receiveDate" class="form-label"><span class="text-danger fw-bold">*</span> RECEIVED DATE</label>
                <input type="date" class="form-control" id="receiveDate" name="receiveDate" value="<?= htmlspecialchars($item['receiveDate']); ?>">
            </div>

            <div class="mb-3">
                <label for="particularsTo" class="form-label">PARTICULARS TO</label>
                <input type="text" class="form-control" id="particularsTo" name="particularsTo" value="<?= htmlspecialchars($item['particularsTo']); ?>">
            </div>

            <div class="mb-3">
                <label for="installDate" class="form-label">INSTALLATION DATE</label>
                <input type="date" class="form-control" id="installDate" name="installDate" value="<?= htmlspecialchars(date('Y-m-d', strtotime($item['installDate']))); ?>">
            </div>

            <div class="mb-3">
                <label for="section" class="form-label">SECTION</label>
                <input type="text" class="form-control" id="section" name="section" value="<?= htmlspecialchars($item['section']); ?>">
            </div>

            <div class="mb-3">
                <label for="ipAddress" class="form-label">IP ADDRESS</label>
                <input type="text" class="form-control" id="ipAddress" name="ipAddress" minlength="7" maxlength="15" pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$" value="<?= htmlspecialchars($item['ipAddress']); ?>">
            </div>

            <div class="mb-3">
                <label for="remarks" class="form-label">REMARKS</label>
                <textarea class="form-control" id="remarks" name="remarks"><?= htmlspecialchars($item['remarks']); ?></textarea>
            </div>

            <div class="mb-3">
                <input type="hidden" class="form-control" id="id" name="id" value="<?= htmlspecialchars($item['id']); ?>">
            </div>

            <input type="submit" name="submit" id="submit" class="btn btn-primary float-end" value="submit">
        </form>
    </div>
<?php else: ?>
    <span>No item found</span>
<?php endif; ?>

<?php include 'includes/script.php'; ?>
<?php include 'includes/footer.php'; ?>
