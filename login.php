<?php
require "classes/Database.php";

session_start();
$db = new Database();
$conn = $db->getDb();

$error_message = [];

// CSRF protection token generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $submit = $_POST["submit"];
    $userName = $_POST["userName"];
    $password = $_POST["password"];

    if (isset($submit)) {

        if (empty($userName)) {
            $error_message[] = "Name is required";
        }
        if (empty($password)) {
            $error_message[] = "Enter your password";
        }

        if (empty($error_message)) {
            // Query to check if username exists
            $sql = "SELECT * 
                FROM `users` 
                WHERE `username` = :username";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $userName, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {

                // Verify password
                if (password_verify($password, $user['password'])) {
                    // Password correct, set session variables
                    session_regenerate_id(true);
                    $_SESSION['is_logged_in'] = true;
                    $_SESSION['userName'] = $user['userName'];
                    $_SESSION['role'] = $user['role'];

                    if ($user['role'] == 1) {
                        $_SESSION['userName'] = $userName;
                        header("Location: admin_dashboard.php?loggedIn=true");
                        exit;
                    } else {
                        header("Location:../inventory/regular_users/regularUser_dashboard.php");
                        exit;
                    }
                } else {
                    $error_message[] = "Incorrect Password";
                }
            } else {
                $error_message[] = "Invalid Credentials";
            }
        }
    }
}


?>


<?php $title = "login";
require 'includes/header.php'; ?>

<?php require 'designs/carousel.php' ?>

<?php if (isset($_GET['signup'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>signup Successful!</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php foreach ($error_message as $err): ?>
    <div class="container text-danger"><?= $err; ?><br></div>
<?php endforeach ?>

<div class="container">
    <div class="row align-items-center mt-4">
        <div class="col-m">
            <div class="card rounded shadow mx-auto w-50" style="background:#EEEEEE">
                <div class="card-body">
                    <p class="card-title fw-bold fs-2 text text-center">Login</p>

                    <form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <div class="mb-3">
                            <label for="userName" class="form-label fw-bolder">User Name</label>
                            <input type="text" class="form-control" id="userName" name="userName"
                                placeholder="User Name">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-bolder">Password</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password">
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <input type="submit" id="submit" name="submit" class="btn btn-outline-success"
                                value="Login">
                            <input type="reset" class="btn btn-outline-danger" value="Cancel">
                        </div>
                    </form>

                    <a href="signup.php" class="card-link text-success mx-auto">Signup</a>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require 'includes/script.php' ?>
<?php require 'includes/footer.php' ?>