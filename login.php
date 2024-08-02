<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: products.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header text-center">
                        <h2>Login</h2>
                    </div>
                    <div class="card-body">
                        <?php
                        if (isset($_POST["login"])) {
                            $name = htmlspecialchars($_POST["name"]);
                            $password = $_POST["password"];
                            require_once "database.php";
                            $sql = "SELECT * FROM users WHERE name = ?";
                            $stmt = mysqli_stmt_init($conn);
                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                die("SQL error");
                            }
                            mysqli_stmt_bind_param($stmt, "s", $name);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
                            if ($user) {
                                if (password_verify($password, $user["password"])) {
                                    $_SESSION["user"] = "yes";
                                    header("Location: products.php");
                                    exit();
                                } else {
                                    echo "<div class='alert alert-danger'>Password does not match</div>";
                                }
                            } else {
                                echo "<div class='alert alert-danger'>Name does not match</div>";
                            }
                        }
                        ?>
                        <form action="login.php" method="post">
                            <div class="form-group mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" name="login" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                        <div class="text-center mt-3">
                            <p>Not registered yet? <a href="index.php">Register Here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
