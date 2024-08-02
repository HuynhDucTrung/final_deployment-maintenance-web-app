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
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script>
        function validateForm() {
            var name = document.forms["registrationForm"]["name"].value;
            var email = document.forms["registrationForm"]["email"].value;
            var password = document.forms["registrationForm"]["password"].value;
            var repeatPassword = document.forms["registrationForm"]["repeat_password"].value;
            var errors = [];

            // Kiểm tra tên
            var namePattern = /^[a-zA-Z\s]{2,16}$/;
            if (!name.match(namePattern)) {
                errors.push("Name must be between 2 and 16 characters and contain only letters and spaces.");
            }

            if (!email || !password || !repeatPassword) {
                errors.push("All fields are required");
            }

            var emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
            if (!email.match(emailPattern)) {
                errors.push("Email is not valid");
            }

            if (password.length > 8) {
                errors.push("Password must not exceed 8 characters");
            }
            if (!/[A-Z]/.test(password)) {
                errors.push("Password must have at least one uppercase letter");
            }
            if (!/[a-z]/.test(password)) {
                errors.push("Password must have at least one lowercase letter");
            }
            if (!/[\W_]/.test(password)) {
                errors.push("Password must have at least one special character");
            }
            if (password !== repeatPassword) {
                errors.push("Password does not match");
            }

            if (errors.length > 0) {
                var errorText = errors.join("\n");
                alert(errorText);
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header text-center">
                        <h2>Register</h2>
                    </div>
                    <div class="card-body">
                        <?php
                        if (isset($_POST["submit"])) {
                            $name = htmlspecialchars($_POST["name"]);
                            $email = htmlspecialchars($_POST["email"]);
                            $password = $_POST["password"];
                            $passwordRepeat = $_POST["repeat_password"];

                            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                            $errors = array();

                            // Kiểm tra tên
                            if (!preg_match("/^[a-zA-Z\s]{2,16}$/", $name)) {
                                array_push($errors, "Name must be between 2 and 16 characters and contain only letters and spaces.");
                            }

                            if (empty($name) OR empty($email) OR empty($password) OR empty($passwordRepeat)) {
                                array_push($errors, "All fields are required");
                            }
                            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                array_push($errors, "Email is not valid");
                            }
                            
                            // Kiểm tra mật khẩu
                            if (strlen($password) > 8) {
                                array_push($errors, "Password must not exceed 8 characters");
                            }
                            if (!preg_match('/[A-Z]/', $password)) {
                                array_push($errors, "Password must have at least one uppercase letter");
                            }
                            if (!preg_match('/[a-z]/', $password)) {
                                array_push($errors, "Password must have at least one lowercase letter");
                            }
                            if (!preg_match('/[\W_]/', $password)) {
                                array_push($errors, "Password must have at least one special character");
                            }
                            if ($password !== $passwordRepeat) {
                                array_push($errors, "Password does not match");
                            }
                            
                            require_once "database.php";
                            $sql = "SELECT * FROM users WHERE email = ?";
                            $stmt = mysqli_stmt_init($conn);
                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                die("SQL error");
                            }
                            mysqli_stmt_bind_param($stmt, "s", $email);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            $rowCount = mysqli_num_rows($result);
                            if ($rowCount > 0) {
                                array_push($errors, "Email already exists!");
                            }
                            if (count($errors) > 0) {
                                foreach ($errors as $error) {
                                    echo "<div class='alert alert-danger'>".htmlspecialchars($error)."</div>";
                                }
                            } else {
                                $sql = "INSERT INTO users (name, email, password) VALUES ( ?, ?, ? )";
                                $stmt = mysqli_stmt_init($conn);
                                if (!mysqli_stmt_prepare($stmt, $sql)) {
                                    die("SQL error");
                                }
                                mysqli_stmt_bind_param($stmt, "sss", $name, $email, $passwordHash);
                                if (mysqli_stmt_execute($stmt)) {
                                    echo "<div class='alert alert-success'>You are registered successfully.</div>";
                                } else {
                                    die("Something went wrong");
                                }
                            }
                        }
                        ?>
                        <form name="registrationForm" action="index.php" method="post" onsubmit="return validateForm()">
                            <div class="form-group mb-3">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="repeat_password">Repeat Password</label>
                                <input type="password" class="form-control" name="repeat_password" required>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary" name="submit">Register</button>
                            </div>
                        </form>
                        <div class="text-center mt-3">
                            <p>Already Registered? <a href="login.php">Login Here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
