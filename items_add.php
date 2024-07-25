<?php
require 'classes/Database.php';
require 'classes/Category.php';
require 'includes/function.php';

$admin = isAdmin();
$db = new Database();
$conn = $db->getDb();

// Variables
$error_message = [];
$id = '';
$category_id = '';
$serialNumber = '';
$invoice = '';
$receiveFrom = '';
$receiveDate = '';
$particularsTo = '';
$section = '';
$ipAddress = '';
$installDate = '';
$remarks = '';

// Adding data from form
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

    if (isset($submit)) {
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
            $error_message[] = "Vendors Details required";
        }
        if (empty($receiveDate)) {
            $error_message[] = "Please enter the date.";
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
        if (empty($installDate)) {
            $installDate = null;
        }
        
        if (empty($remarks)) {
            $remarks = "";
        }

        if (empty($error_message)) {
            $sqlExist = "SELECT * 
                         FROM `items` 
                         WHERE `serialNumber` = :serialNumber 
                         OR `ipAddress` = :ipAddress";
            $stmtExist = $conn->prepare($sqlExist);
            $stmtExist->bindParam(':serialNumber', $serialNumber, PDO::PARAM_STR);
            $stmtExist->bindParam(':ipAddress', $ipAddress, PDO::PARAM_STR);
            $stmtExist->execute();
            $itemsExist = $stmtExist->fetchAll(PDO::FETCH_ASSOC);
            if (count($itemsExist) > 0) {
                $error_message[] = "Serial Number exists / IP Address already exists";
            } else {
                try {
                    $sqlInsert = "INSERT INTO `items`(`category_id`, `serialNumber`, `invoice`, `receiveFrom`, 
                    `receiveDate`, `particularsTo`, `section`, `ipAddress`, `installDate`, `remarks`)
                    VALUES (:category_id, :serialNumber, :invoice, :receiveFrom, :receiveDate, :particularsTo, :section, :ipAddress, :installDate, :remarks)";
                    $stmtInsert = $conn->prepare($sqlInsert);
                    $stmtInsert->bindParam(':category_id', $category_id, PDO::PARAM_STR);
                    $stmtInsert->bindParam(':serialNumber', $serialNumber, PDO::PARAM_STR);
                    $stmtInsert->bindParam(':invoice', $invoice, PDO::PARAM_STR);
                    $stmtInsert->bindParam(':receiveFrom', $receiveFrom, PDO::PARAM_STR);
                    $stmtInsert->bindParam(':receiveDate', $receiveDate, PDO::PARAM_STR);
                    $stmtInsert->bindParam(':particularsTo', $particularsTo, PDO::PARAM_STR);
                    $stmtInsert->bindParam(':section', $section, PDO::PARAM_STR);
                    $stmtInsert->bindParam(':ipAddress', $ipAddress, PDO::PARAM_STR);
                    $stmtInsert->bindParam(':installDate', $installDate, PDO::PARAM_STR);
                    $stmtInsert->bindParam(':remarks', $remarks, PDO::PARAM_STR);

                    if ($stmtInsert->execute()) {
                       header("Location:items_list.php");
                       exit;
                    } else {
                        $error_message[] = "Unable to insert Data";
                    }
                } catch (Exception $e) {
                    echo "Fail to execute: " . $e->getMessage();
                }
            }
        }
    }
}

// Fetch categories
$categories = Category::getCategories($conn);
?>

<?php $title = "Add Items";
include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<span class="fs-1 fw-bold text-center mt-4 text-primary">ADD ITEMS</span>

<?php if ($error_message): ?>
    <ul class="text-danger fw-bold">
        <?php foreach ($error_message as $err): ?>
            <li><?= htmlspecialchars($err); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<div class="container" style="width: 50%;">
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <div class="mb-3">
            <label for="category_id" class="form-label fw-bold"><span class="text-danger fw-bold">*</span> CATEGORY</label>
            <select class="form-select" id="category_id" name="category_id">
                <option value=""></option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['cat_id']); ?>"><?= htmlspecialchars($category['cat_name']); ?> - <?= htmlspecialchars($category['cat_brand']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="serialNumber" class="form-label fw-bold"><span class="text-danger fw-bold">*</span> SERIAL NUMBER</label>
            <input type="text" class="form-control" id="serialNumber" name="serialNumber" value="<?= htmlspecialchars($serialNumber); ?>">
        </div>

        <div class="mb-3">
            <label for="invoice" class="form-label fw-bold">INVOICE NUMBER</label>
            <input type="text" class="form-control" id="invoice" name="invoice" value="<?= htmlspecialchars($invoice); ?>">
        </div>

        <div class="mb-3">
            <label for="receiveFrom" class="form-label fw-bold"><span class="text-danger fw-bold">*</span> RECEIVE FROM</label>
            <input type="text" class="form-control" id="receiveFrom" name="receiveFrom" value="<?= htmlspecialchars($receiveFrom); ?>">
        </div>

        <div class="mb-3">
            <label for="receiveDate" class="form-label fw-bold"><span class="text-danger fw-bold">*</span> RECEIVED DATE</label>
            <input type="date" class="form-control" id="receiveDate" name="receiveDate" value="<?= htmlspecialchars($receiveDate); ?>">
        </div>

        <div class="mb-3">
            <label for="particularsTo" class="form-label fw-bold">PARTICULARS TO</label>
            <input type="text" class="form-control" id="particularsTo" name="particularsTo" value="<?= htmlspecialchars($particularsTo); ?>">
        </div>

        <div class="mb-3">
            <label for="section" class="form-label fw-bold">SECTION</label>
            <input type="text" class="form-control" id="section" name="section" value="<?= htmlspecialchars($section); ?>">
        </div>

        <div class="mb-3">
            <label for="ipAddress" class="form-label fw-bold">IP ADDRESS</label>
            <input type="text" class="form-control" id="ipAddress" name="ipAddress" minlength="7" maxlength="15" pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$" value="<?= htmlspecialchars($ipAddress); ?>">
        </div>

        <div class="mb-3">
            <label for="installDate" class="form-label fw-bold">INSTALLATION DATE</label>
            <input type="date" class="form-control" id="installDate" name="installDate" value="<?= htmlspecialchars($installDate); ?>">
        </div>

        <div class="mb-3">
            <label for="remarks" class="form-label fw-bold">REMARKS</label>
            <textarea class="form-control" id="remarks" name="remarks"><?= htmlspecialchars($remarks); ?></textarea>
        </div>

        <div class="mb-3">
            <input type="hidden" class="form-control" id="id" name="id" value="<?= htmlspecialchars($id); ?>">
        </div>

        <input type="submit" name="submit" id="submit" class="btn btn-outline-primary float-end" value="Submit">
    </form>
</div>

<?php include 'includes/script.php'; ?>
<?php include 'includes/footer.php'; ?>
