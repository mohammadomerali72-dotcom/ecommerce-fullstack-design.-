<?php 
// 1. Connection and Session Setup
session_start();
include_once 'db.php'; 
include_once 'functions.php';

// Standardize database connection variable
if (!isset($conn)) {
    $conn = isset($db) ? $db : (isset($con) ? $con : null);
}

// ✅ 2. HANDLE "ADD TO CART" LOGIC
if(isset($_GET['add_cart'])){
    $p_id = mysqli_real_escape_string($conn, $_GET['add_cart']);
    $ip = getIPAddress(); // Function from your functions.php
    
    // Check if already in cart
    $check_cart = $conn->query("SELECT * FROM cart WHERE p_id='$p_id' AND ip_add='$ip'");
    if($check_cart->num_rows > 0){
        echo "<script>alert('Product already in cart!'); window.open('shop.php','_self')</script>";
    } else {
        $q = "INSERT INTO cart (p_id, ip_add, qty) VALUES ('$p_id', '$ip', 1)";
        if($conn->query($q)){
            echo "<script>alert('Product added to cart!'); window.open('shop.php','_self')</script>";
        }
    }
}

// ✅ 3. HANDLE "ADD TO WISHLIST" LOGIC
if(isset($_GET['add_wishlist'])){
    if(!isset($_SESSION['user_email'])){
        echo "<script>alert('Please login to save items!'); window.open('login.php','_self')</script>";
    } else {
        $email = $_SESSION['user_email'];
        $p_id = mysqli_real_escape_string($conn, $_GET['add_wishlist']);
        
        // Get user_id
        $user_q = $conn->query("SELECT user_id FROM users WHERE user_email='$email'");
        $u_data = $user_q->fetch_assoc();
        $u_id = $u_data['user_id'];

        // Check if already in wishlist
        $check_wish = $conn->query("SELECT * FROM wishlist WHERE user_id='$u_id' AND product_id='$p_id'");
        if($check_wish->num_rows > 0){
            echo "<script>alert('Already in your wishlist!'); window.open('shop.php','_self')</script>";
        } else {
            $q = "INSERT INTO wishlist (user_id, product_id) VALUES ('$u_id', '$p_id')";
            if($conn->query($q)){
                echo "<script>alert('Saved to wishlist!'); window.open('shop.php','_self')</script>";
            }
        }
    }
}

include_once 'header.php'; 

// --- 4. DYNAMIC FILTER LOGIC ---
$conditions = [];
$cat_id = $_GET['category_id'] ?? '';
$brand_id = $_GET['brand_id'] ?? '';
$search = $_GET['search'] ?? '';
$min = (int)($_GET['min'] ?? 0);
$max = (int)($_GET['max'] ?? 0);

if (!empty($search)) { $conditions[] = "(product_title LIKE '%$search%' OR product_keywords LIKE '%$search%')"; }
if (!empty($cat_id)) { $conditions[] = "product_cat = '$cat_id'"; }
if (!empty($brand_id)) { $conditions[] = "product_brand = '$brand_id'"; }
if ($max > 0) { $conditions[] = "product_price BETWEEN '$min' AND '$max'"; }

$sql = "SELECT * FROM products";
if (count($conditions) > 0) { $sql .= " WHERE " . implode(' AND ', $conditions); }
$result = $conn->query($sql);

$current_title = "All Products";
if(!empty($cat_id)){
    $cat_name_q = $conn->query("SELECT cat_title FROM categories WHERE cat_id = '$cat_id'");
    if($cat_name_q->num_rows > 0) $current_title = $cat_name_q->fetch_assoc()['cat_title'];
}
?>

<div class="bg-light pb-5">

    <!-- ========================= 📱 MOBILE HEADER ========================= -->
    <div class="d-lg-none bg-white shadow-sm sticky-top" style="top: 0; z-index: 1020;">
        <div class="d-flex align-items-center px-3 py-2 border-bottom">
            <a href="index.php" class="text-dark me-3"><i class="fa fa-arrow-left"></i></a>
            <h6 class="mb-0 fw-bold flex-grow-1 text-truncate"><?php echo $current_title; ?></h6>
            <div class="d-flex gap-3">
                <a href="cart.php" class="text-dark"><i class="fa fa-shopping-cart"></i></a>
                <a href="settings.php" class="text-dark"><i class="fa fa-user"></i></a>
            </div>
        </div>
        
        <div class="scroll-categories p-2">
            <a href="shop.php" class="pill-link <?php echo ($cat_id == '') ? 'active':''; ?>">All Items</a>
            <?php
            $cat_list = $conn->query("SELECT * FROM categories");
            while($c = $cat_list->fetch_assoc()){
                $active = ($cat_id == $c['cat_id']) ? 'active' : '';
                echo '<a href="shop.php?category_id='.$c['cat_id'].'" class="pill-link '.$active.'">'.$c['cat_title'].'</a>';
            }
            ?>
        </div>
    </div>

    <!-- ========================= 💻 MAIN CONTENT ========================= -->
    <div class="container py-3 py-lg-4">
        
        <nav class="d-none d-lg-block mb-3 small">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="index.php" class="text-muted text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active">Shop</li>
                <?php if(!empty($cat_id)) echo '<li class="breadcrumb-item active">'.$current_title.'</li>'; ?>
            </ol>
        </nav>

        <div class="row">
            <!-- SIDEBAR -->
            <aside class="col-lg-3 d-none d-lg-block">
                <div class="card p-3 shadow-sm border-0 mb-4 sticky-top" style="top: 20px;">
                    <h6 class="fw-bold mb-3 border-bottom pb-2">Categories</h6>
                    <ul class="list-unstyled small mb-4">
                        <?php 
                        $cats = $conn->query("SELECT * FROM categories");
                        while($ct = $cats->fetch_assoc()){
                            $style = ($cat_id == $ct['cat_id']) ? 'fw-bold text-primary' : 'text-muted';
                            echo '<li><a href="shop.php?category_id='.$ct['cat_id'].'" class="d-block py-1 text-decoration-none '.$style.'">'.$ct['cat_title'].'</a></li>';
                        }
                        ?>
                    </ul>
                    
                    <h6 class="fw-bold mb-3 border-bottom pb-2">Brands</h6>
                    <div class="brand-list small mb-4 overflow-auto" style="max-height: 200px;">
                        <?php 
                        $brands = $conn->query("SELECT * FROM brands");
                        while($b = $brands->fetch_assoc()){
                            $is_checked = ($brand_id == $b['brand_id']) ? 'checked' : '';
                            echo '<label class="d-block mb-2 cursor-pointer">
                                    <input type="checkbox" '.$is_checked.' onclick="window.location.href=\'shop.php?brand_id='.$b['brand_id'].'\'"> '.$b['brand_title'].'
                                  </label>';
                        }
                        ?>
                    </div>

                    <h6 class="fw-bold mb-3 border-bottom pb-2">Price range</h6>
                    <form action="shop.php" method="GET">
                        <div class="row g-2 mb-3">
                            <div class="col-6"><input class="form-control form-control-sm" name="min" placeholder="0" type="number" value="<?php echo $min; ?>"></div>
                            <div class="col-6"><input class="form-control form-control-sm" name="max" placeholder="Max" type="number" value="<?php echo $max; ?>"></div>
                        </div>
                        <button class="btn btn-sm btn-primary w-100 fw-bold">Apply Filter</button>
                    </form>
                </div>
            </aside>

            <!-- PRODUCT LIST -->
            <main class="col-lg-9">
                <header class="d-flex align-items-center mb-3 bg-white p-3 rounded shadow-sm border">
                    <strong class="text-dark small"><?php echo $result->num_rows; ?> Items found</strong>
                </header>

                <div class="row g-2 g-lg-3">
                <?php
                if ($result && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $pid = $row['product_id'];
                        $rating_res = $conn->query("SELECT AVG(rating) as avg_rate FROM product_reviews WHERE product_id = '$pid'");
                        $avg = round($rating_res->fetch_assoc()['avg_rate'], 1);
                ?>
                    <div class="col-12">
                        <div class="card card-product-list shadow-sm border rounded bg-white overflow-hidden">
                            <div class="row g-0 align-items-center">
                                <!-- Image -->
                                <div class="col-4 col-lg-3 p-3 text-center border-end-lg">
                                    <a href="product.php?id=<?php echo $row['product_id']; ?>">
                                        <img src="images/<?php echo $row['product_image']; ?>" class="img-fluid" style="max-height: 160px; object-fit: contain;">
                                    </a>
                                </div>
                                <!-- Content -->
                                <div class="col-8 col-lg-6">
                                    <div class="card-body py-2 py-lg-4">
                                        <a href="product.php?id=<?php echo $row['product_id']; ?>" class="title h6 text-dark d-block mb-1 text-decoration-none fw-bold">
                                            <?php echo $row['product_title']; ?>
                                        </a>
                                        <div class="stars text-warning small mb-1">
                                            <?php for($i=1;$i<=5;$i++) echo ($i<=$avg) ? '<i class="fa fa-star"></i>' : '<i class="fa-regular fa-star"></i>'; ?>
                                            <span class="text-warning fw-bold ms-1" style="font-size: 11px;"><?php echo ($avg > 0) ? $avg : '0.0'; ?></span>
                                        </div>
                                        <p class="text-muted small d-none d-lg-block mb-2">
                                            <?php echo substr($row['product_desc'], 0, 130); ?>...
                                        </p>
                                        <div class="text-success small fw-bold"><i class="fa fa-truck me-1"></i> Free Shipping</div>
                                    </div>
                                </div>
                                <!-- Price & Buttons (Desktop) -->
                                <div class="col-lg-3 d-none d-lg-flex flex-column justify-content-center p-4 border-start bg-light bg-opacity-25 h-100 text-center">
                                    <div class="h4 fw-bold text-dark mb-3">$<?php echo $row['product_price']; ?></div>
                                    <div class="d-grid gap-2">
                                        <a href="?add_cart=<?php echo $row['product_id']; ?>" class="btn btn-primary btn-sm fw-bold py-2"><i class="fa fa-shopping-cart me-2"></i> Add to cart</a>
                                        <a href="?add_wishlist=<?php echo $row['product_id']; ?>" class="btn btn-light btn-sm border text-danger fw-bold py-2"><i class="fa fa-heart me-2"></i> Wishlist</a>
                                    </div>
                                </div>
                                <!-- Mobile Price -->
                                <div class="col-8 d-lg-none px-3 pb-2 mt-n2">
                                    <strong class="h5 text-dark">$<?php echo $row['product_price']; ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php 
                    } 
                }
                ?>
                </div>
            </main>
        </div>
    </div>
</div>

<?php include_once 'footer.php'; ?>