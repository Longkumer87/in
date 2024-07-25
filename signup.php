<?php
require "classes/Database.php";

$db = new Database();
$conn = $db->getDb();

$error_message = [];
$exist = false;
$username = '';

// CSRF protection token generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $submit = $_POST['submit'];
    $username = strtoupper($_POST["username"]);
    $password = strip_tags($_POST["password"]);
    $cPassword = strip_tags($_POST["cPassword"]);

    if (isset($submit)) {

        if (isset($_POST["role"])) {
            $role = intval($_POST["role"]);
        } else {
            $role = 0;
        }

        if (empty($username)) {
            $error_message[] = "Name is required";
        }
        if (empty($password) || strlen($password) < 3) {
            $error_message[] = "Password should be at least 3 characters long";
        }
        if (empty($cPassword)) {
            $error_message[] = "Please confirm your password";
        }

        // Checking if username already exists
        if (empty($error_message)) {
            $sqlExist = "SELECT * 
                    FROM `users` 
                    WHERE `username` = :username";

            $stmtExist = $conn->prepare($sqlExist);
            $stmtExist->bindParam(':username', $username, PDO::PARAM_STR_CHAR);
            $stmtExist->execute();
            $numExist = $stmtExist->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($numExist)) {
                $error_message[] = "Username already exists";
            } else {
                // Validating password match and inserting user into database
                if ($password === $cPassword) {
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    $sqlInsert = "INSERT INTO `users` (`username`, `password`, `cpassword`, `role`) 
                              VALUES (:username, :password, :cpassword, :role)";
                    $stmtInsert = $conn->prepare($sqlInsert);
                    $stmtInsert->bindParam(':username', $username, PDO::PARAM_STR);
                    $stmtInsert->bindParam(':password', $password_hash);
                    $stmtInsert->bindParam(':cpassword', $cPassword);
                    $stmtInsert->bindParam(':role', $role, PDO::PARAM_INT);

                    if ($stmtInsert->execute()) {
                        // Redirect to login page on successful signup
                        header("Location: login.php?signup=true");
                        exit;
                    } else {
                        $error_message[] = "Error inserting user";
                    }
                } else {
                    $error_message[] = "Passwords do not match";
                }
            }
        }
    }
}

?>

<?php $title = "signup";
require 'includes/header.php'; ?>

<?php require 'designs/carousel.php' ?>

<?php if (!empty($error_message)): ?>

    <?php foreach ($error_message as $err): ?>
        <ul class="text-danger">
            <li>
                <?= $err; ?>
            </li>
        </ul>
    <?php endforeach; ?>
<?php endif; ?>

<div class="container">
    <div class="row align-items-center mt-3">
        <div class="col-m">
            <div class="card rounded shadow mx-auto w-50" style=" background:#EEEEEE">
                <div class="card-body">
                    <i class="fa-solid fa-arrow-left-long"></i>
                    <p class="card-title fw-bold fs-2 text-dark text-center">Signup</p>

                    <form enctype="multipart/form-data" action="<?= $_SERVER['PHP_SELF']; ?>" method="post">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="username" id="username"
                                placeholder="User Name" value="<?= htmlspecialchars($username); ?>">
                            <label for="username">UserName</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password">
                            <label for="Password">Password</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="cPassword" name="cPassword"
                                placeholder="confirmPassword">
                            <label for="cPassword">Confirm Password</label>
                        </div>

                        <div class="d-flex justify-content-between">
                            <input type="submit" id="submit" name="submit" class="btn btn-outline-success"
                                value="SignUp">
                            <input type="reset" class="btn btn-outline-danger" value="Cancel">
                        </div>
                    </form>
                    <p class="text-center">already signup?<a href="login.php"> login</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'includes/script.php' ?>
<?php require 'includes/footer.php' ?>