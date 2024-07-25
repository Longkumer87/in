<?php
require "classes/Database.php";
require "includes/function.php";

$userAdmin = isAdmin();

$db = new Database();
$conn = $db->getDb();

?>

<?php $title = "manage_users";
include 'includes/header.php' ?>

<?php include 'includes/navbar.php' ?>


<?php include 'includes/script.php' ?>
<?php include 'includes/footer.php' ?>