<?php
require 'classes/Database.php';
require 'classes/Ipaddress.php';
require 'includes/function.php';

$admin = isAdmin();
$db = new Database();
$conn = $db->getDb();

if (isset($_GET['page']) && is_numeric($_GET['page'])) {
  $page = (int) $_GET['page'];
} else {
  $page = 1;
}
$limit = 8;
$offset = ($page - 1) * $limit;

//class to get and display IP-Address
$ipAddresses = Ipaddress::getIp($conn, $limit, $offset);

//class to get total number of ip address
$totalIpPage = Ipaddress::getTotalIp($conn, $limit);

$slno = $offset + 1;

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
          <?php if (!empty($ipAdd['ipAddress'])): ?>
            <tr>
              <th scope="row"><?= $slno++ ?></th>
              <td><?= $ipAdd['ipAddress']; ?></td>
              <td><?= $ipAdd['particularsTo']; ?></td>
            </tr>
          <?php endif; ?>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<nav aria-label="Page navigation example">
  <ul class="pagination justify-content-center mt-3">
    <?php if ($page > 1): ?>
      <li class="page-item">
        <a class="page-link" href="?page=<?=$page - 1; ?>">Previous</a>
      </li>
    <?php endif; ?>

     <?php for($i=1; $i<$page; $i++):?> 
    <li class="page-item<?=$i==$page?'active':''?>">
    <a class="page-link" href="?page=<?=$i;?>"><?=$i;?></a>
    </li>
    <?php endfor;?>

    <?php if ($page < $totalIpPage): ?>
      <li class="page-item"><a class="page-link" href="?page=<?= $page + 1; ?>">Next</a></li>
    <?php endif; ?>
  </ul>
</nav>

<?php include 'includes/script.php' ?>
<?php include 'includes/footer.php' ?>