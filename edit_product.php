<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
require_once "database.php";
require_once "header.php";

$id = $_GET['id'];
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    die("SQL error");
}
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_array($result, MYSQLI_ASSOC);

if (!$product) {
    die("Product not found");
}

$errors = [];
if (isset($_POST["submit"])) {
    $name = htmlspecialchars($_POST["name"]);
    $price = $_POST["price"];
    $description = htmlspecialchars($_POST["description"]);
    $image = $product['image'];

    if (empty($name) || empty($price)) {
        array_push($errors, "All fields are required");
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
                    // Xóa hình ảnh cũ nếu có
                    if (!empty($product['image']) && file_exists('uploads/' . $product['image'])) {
                        unlink('uploads/' . $product['image']);
                    }
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
        $sql = "UPDATE products SET name = ?, price = ?, description = ?, image = ? WHERE id = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            die("SQL error");
        }
        mysqli_stmt_bind_param($stmt, "sdssi", $name, $price, $description, $image, $id);
        if (mysqli_stmt_execute($stmt)) {
            // Xóa cache khi sản phẩm được cập nhật
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
    <h1>Edit Product</h1>
    <?php if (count($errors) > 0): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form action="edit_product.php?id=<?php echo $product['id']; ?>" method="post" enctype="multipart/form-data">
        <div class="form-group mb-3">
            <label for="name">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
        </div>
        <div class="form-group mb-3">
            <label for="price">Price (VND)</label>
            <input type="number" step="1000" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
        </div>
        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description"><?php echo htmlspecialchars($product['description']); ?></textarea>
        </div>
        <div class="form-group mb-3">
            <label for="image">Product Image</label>
            <input type="file" class="form-control" id="image" name="image">
            <?php if ($product['image']): ?>
                <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" width="100">
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary" name="submit">Update Product</button>
    </form>
</div>

<?php
require_once "footer.php";
?>
