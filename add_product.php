<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
require_once "database.php";
require_once "header.php";

$errors = [];
if (isset($_POST["submit"])) {
    $name = htmlspecialchars($_POST["name"]);
    $price = $_POST["price"];
    $description = htmlspecialchars($_POST["description"]);
    $image = '';

    if (empty($name) || empty($price)) {
        array_push($errors, "All fields are required");
    }

    // Kiểm tra sản phẩm trùng lặp
    $sql = "SELECT * FROM products WHERE name = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die("SQL error");
    }
    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rowCount = mysqli_num_rows($result);
    if ($rowCount > 0) {
        array_push($errors, "Product already exists");
    }

    // Xử lý upload hình ảnh
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $fileName = $_FILES['image']['name'];
        $fileTmpName = $_FILES['image']['tmp_name'];
        $fileSize = $_FILES['image']['size'];
        $fileError = $_FILES['image']['error'];
        $fileType = $_FILES['image']['type'];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        if (in_array($fileActualExt, $allowed)) {
            if ($fileError === 0) {
                if ($fileSize < 1000000) { // < 1MB
                    $fileNewName = uniqid('', true) . "." . $fileActualExt;
                    $fileDestination = 'uploads/' . $fileNewName;
                    move_uploaded_file($fileTmpName, $fileDestination);
                    $image = $fileNewName;
                } else {
                    array_push($errors, "Your file is too big!");
                }
            } else {
                array_push($errors, "There was an error uploading your file!");
            }
        } else {
            array_push($errors, "You cannot upload files of this type!");
        }
    }

    if (count($errors) == 0) {
        $sql = "INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            die("SQL error");
        }
        mysqli_stmt_bind_param($stmt, "sdss", $name, $price, $description, $image);
        if (mysqli_stmt_execute($stmt)) {
            // Xóa cache khi sản phẩm được thêm mới
            array_map('unlink', glob("cache/products_*.json"));
            array_map('unlink', glob("cache/products_total_*.txt"));
            header("Location: products.php");
            exit();
        } else {
            die("Something went wrong");
        }
    }
}
?>

<div class="container mt-5">
    <h1>Add Product</h1>
    <?php if (count($errors) > 0): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form action="add_product.php" method="post" enctype="multipart/form-data">
        <div class="form-group mb-3">
            <label for="name">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group mb-3">
            <label for="price">Price (VND)</label>
            <input type="number" step="1000" class="form-control" id="price" name="price" required>
        </div>
        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <div class="form-group mb-3">
            <label for="image">Product Image</label>
            <input type="file" class="form-control" id="image" name="image">
        </div>
        <button type="submit" class="btn btn-primary" name="submit">Add Product</button>
    </form>
</div>

<?php
require_once "footer.php";
?>
