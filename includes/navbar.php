<?php

   if(isset($_SESSION['is_logged_in'])&& $_SESSION['is_logged_in']== true){

    $loggedIn = true;
  }else {
    $loggedIn = false;

  }

  if ($_SERVER["REQUEST_METHOD"]=="POST") {
    if (isset($_POST['logout'])) {
      $logout = $_POST['logout'];
        header("Location:users/logout.php");
        exit;
    }
  }


 ?>

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #344f1d;">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="admin_dashboard.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">About</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            More
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
      </ul>
      <form class="d-flex" action="logout.php">
        <input type="submit" class="btn btn-outline-danger mx-2" name="logout" value="Logout">
      </form>
    </div>
  </div>
</nav>


