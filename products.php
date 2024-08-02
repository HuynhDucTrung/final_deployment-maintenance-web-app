<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
require_once "database.php";
require_once "header.php";

// Phân trang
$limit = 10; // số sản phẩm trên mỗi trang
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Tìm kiếm
$search = isset($_GET['search']) ? $_GET['search'] : '';
$min_price = isset($_GET['min_price']) ? $_GET['min_price'] : '';
$max_price = isset($_GET['max_price']) ? $_GET['max_price'] : '';

$cacheKey = "cache/products_" . md5($search . $min_price . $max_price . $start . $limit) . ".json";
$cachedProducts = file_exists($cacheKey) ? file_get_contents($cacheKey) : false;

if ($cachedProducts) {
    $products = json_decode($cachedProducts, true);
} else {
    $searchQuery = "WHERE 1=1";
    $params = [];
    $types = '';

    if ($search) {
        $searchQuery .= " AND (name LIKE ? OR description LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $types .= 'ss';
    }
    if ($min_price != '') {
        $searchQuery .= " AND price >= ?";
        $params[] = $min_price;
        $types .= 'd';
    }
    if ($max_price != '') {
        $searchQuery .= " AND price <= ?";
        $params[] = $max_price;
        $types .= 'd';
    }
    $params[] = $start;
    $params[] = $limit;
    $types .= 'ii';

    // Lấy danh sách sản phẩm
    $sql = "SELECT * FROM products $searchQuery LIMIT ?, ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die("SQL error");
    }

    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Lưu vào file cache
    file_put_contents($cacheKey, json_encode($products));
}

// Lấy tổng số sản phẩm
$cacheKeyTotal = "cache/products_total_" . md5($search . $min_price . $max_price) . ".txt";
$cachedTotal = file_exists($cacheKeyTotal) ? file_get_contents($cacheKeyTotal) : false;

if ($cachedTotal) {
    $total = (int)$cachedTotal;
} else {
    $sqlTotal = "SELECT COUNT(*) FROM products $searchQuery";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sqlTotal)) {
        die("SQL error");
    }

    $paramsTotal = array_slice($params, 0, -2);  // Lấy lại các tham số mà không có LIMIT
    $typesTotal = substr($types, 0, -2);  // Lấy lại các kiểu mà không có 'ii'
    if (!empty($typesTotal)) {
        mysqli_stmt_bind_param($stmt, $typesTotal, ...$paramsTotal);
    }
    mysqli_stmt_execute($stmt);
    $resultTotal = mysqli_stmt_get_result($stmt);
    $total = mysqli_fetch_array($resultTotal)[0];

    // Lưu vào file cache
    file_put_contents($cacheKeyTotal, $total);
}

$pages = ceil($total / $limit);

?>

<div class="container mt-5">
    <h1>Products</h1>
    <form class="form-inline my-2 my-lg-0 w-100" method="GET">
        <div class="row w-100">
            <div class="col-md-3 mb-2">
                <input class="form-control w-100" type="search" placeholder="Search" aria-label="Search" name="search" value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-2 mb-2">
                <input class="form-control w-100" type="number" placeholder="Min Price" aria-label="Min Price" name="min_price" value="<?php echo htmlspecialchars($min_price); ?>">
            </div>
            <div class="col-md-2 mb-2">
                <input class="form-control w-100" type="number" placeholder="Max Price" aria-label="Max Price" name="max_price" value="<?php echo htmlspecialchars($max_price); ?>">
            </div>
            <div class="col-md-2 mb-2">
                <button class="btn btn-outline-success w-100" type="submit">Search</button>
            </div>
            <div class="col-md-3 mb-2">
                <a href="add_product.php" class="btn btn-primary w-100">Add Product</a>
            </div>
        </div>
    </form>

    <table class="table table-bordered mt-3">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price (VND)</th>
                <th>Description</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['id']); ?></td>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo number_format(htmlspecialchars($product['price']), 0, ',', '.'); ?> VND</td>
                    <td><?php echo htmlspecialchars($product['description']); ?></td>
                    <td>
                        <?php if ($product['image']): ?>
                            <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" width="100">
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit_product.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_product.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $pages; $i++): ?>
                <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                    <a class="page-link" href="products.php?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search); ?>&min_price=<?php echo htmlspecialchars($min_price); ?>&max_price=<?php echo htmlspecialchars($max_price); ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<?php
require_once "footer.php";
?>
