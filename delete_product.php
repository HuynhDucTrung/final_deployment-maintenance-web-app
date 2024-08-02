<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
require_once "database.php";

$id = $_GET['id'];

// Lấy thông tin sản phẩm để xóa hình ảnh
$sql = "SELECT image FROM products WHERE id = ?";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    die("SQL error");
}
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_array($result, MYSQLI_ASSOC);

if ($product && $product['image']) {
    // Xóa hình ảnh khỏi thư mục uploads
    $file = 'uploads/' . $product['image'];
    if (file_exists($file)) {
        unlink($file);
    }
}

$sql = "DELETE FROM products WHERE id = ?";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    die("SQL error");
}
mysqli_stmt_bind_param($stmt, "i", $id);
if (mysqli_stmt_execute($stmt)) {
    // Xóa cache khi sản phẩm bị xóa
    array_map('unlink', glob("cache/products_*.json"));
    array_map('unlink', glob("cache/products_total_*.txt"));
    header("Location: products.php");
    exit();
} else {
    die("Something went wrong");
}
?>
