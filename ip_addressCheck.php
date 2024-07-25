<?php
require 'classes/Database.php';
require 'classes/Pagination.php';
require 'includes/function.php';

$admin = isAdmin();
$db = new Database();
$conn = $db->getDb();
$slno = 1;


$sqlIp = "SELECT `items`.`ipAddress`, `items`.`particularsTo`
          FROM `items` 
          ORDER BY INET_ATON(`items`.`ipAddress`) ASC";
          
$stmtIp = $conn->prepare($sqlIp);
$stmtIp->execute();
$ipAddresses =$stmtIp->fetchAll(PDO::FETCH_ASSOC);

?>

<?php $title = "ipAddress";
include 'includes/header.php' ?>

<?php if ($ipAddresses): ?>
  <?php include 'includes/navbar.php' ?>
  <div class="container mt-2">

    <table class="table table-bordered border-success">
      <thead class="text-center table-info">
        <tr>
          <th scope="col">slNo</th>
          <th scope="col">IP ADDRESS</th>
          <th scope="col">USER NAME</th>
        </tr>
      </thead>
      <tbody class="table">
        <?php foreach ($ipAddresses as $ipAdd): ?>
          <?php if(!empty($ipAdd['ipAddress'])):?>
          <tr>
            <th scope="row"><?= $slno++ ?></th>
            <td><?= $ipAdd['ipAddress']; ?></td>
            <td><?= $ipAdd['particularsTo']; ?></td>
          </tr>
          <?php endif;?>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<nav aria-label="Page navigation example">
  <ul class="pagination">
    <li class="page-item"><a class="page-link" href="#">Previous</a></li>
    <li class="page-item"><a class="page-link" href="#">1</a></li>
    <li class="page-item"><a class="page-link" href="#">Next</a></li>
  </ul>
</nav>

<?php include 'includes/script.php' ?>
<?php include 'includes/footer.php' ?>