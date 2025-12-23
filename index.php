<?php
// 1. Session and Connections
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include_once 'db.php';     
include_once 'functions.php';

// Standardize connection variable
if (!isset($conn)) { $conn = isset($db) ? $db : $con; }

// ✅ 2. HANDLE INQUIRY FORM SUBMISSION
if(isset($_POST['submit_inquiry'])){
    $item = $conn->real_escape_string($_POST['item_name']);
    $details = $conn->real_escape_string($_POST['details']);
    $qty = (int)$_POST['quantity'];
    
    // Ensure your 'requests' table has columns: item_name, details, quantity
    $sql = "INSERT INTO requests (item_name, details, quantity) VALUES ('$item', '$details', '$qty')";
    if($conn->query($sql)){
        echo "<script>alert('Inquiry sent successfully!');</script>";
    }
}

include_once 'header.php';
?>

<!-- ========================= 1. HERO SECTION ========================= -->
<section class="padding-y bg-light">
<div class="container">
    <div class="row g-3"> 
        <!-- Categories Sidebar -->
        <aside class="col-lg-3 d-none d-lg-block"> 
            <nav class="card h-100 border shadow-sm overflow-hidden">
                <div class="card-header bg-white fw-bold border-bottom">Top Categories</div>
                <ul class="menu-category list-unstyled m-0 py-2">
                    <li><a href="shop.php?category_id=1" class="d-block px-3 py-2 text-dark small">Mobiles</a></li>
                    <li><a href="shop.php?category_id=2" class="d-block px-3 py-2 text-dark small">Laptops</a></li>
                    <li><a href="shop.php?category_id=3" class="d-block px-3 py-2 text-dark small">Home Interior</a></li>
                    <li><a href="shop.php?category_id=4" class="d-block px-3 py-2 text-dark small">Fashion</a></li>
                    <li><a href="shop.php?category_id=5" class="d-block px-3 py-2 text-dark small">Kitchen Items</a></li>
                    <li class="border-top mt-2 pt-2"><a href="shop.php" class="d-block px-3 py-2 text-primary fw-bold small">All Categories ></a></li>
                </ul>
            </nav>
        </aside> 

        <!-- Main Banner (Uses hero_bg.jpg from your folder) -->
        <div class="col-lg-6 col-md-12">
            <div id="hero-banner" class="card-banner border-0 h-100 shadow-sm" style="background: #C3EEF1 url('images/hero_bg.jpg') center right no-repeat; background-size: cover; border-radius: 6px; min-height: 380px;">
                <div class="p-5" style="max-width: 380px; position: relative; z-index: 2;">
                    <h4 class="fw-normal text-dark">Latest Trending</h4>
                    <h2 class="fw-bold mb-4 text-dark" style="font-size: 2.4rem;">Electronic Items & Fashion</h2>
                    <a href="shop.php" class="btn btn-white text-dark fw-bold shadow-sm px-4 py-2 border-0 bg-white">Shop Now</a>
                </div>
            </div>
        </div> 

        <!-- Profile Widget -->
        <div class="col-lg-3 d-none d-lg-block">
            <div class="d-flex flex-column gap-3 h-100">
                <div class="card border shadow-sm p-3 bg-white">
                    <div class="d-flex align-items-center mb-3">
                        <?php 
                        if(isset($_SESSION['user_name'])){
                            $u_img = isset($_SESSION['user_image']) ? $_SESSION['user_image'] : '';
                            $u_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';
                            // Fallback to Gravatar if image file doesn't exist
                            $pic = (!empty($u_img) && file_exists("images/$u_img")) ? "images/$u_img" : "https://www.gravatar.com/avatar/".md5($u_email)."?d=identicon";
                            echo '<img src="'.$pic.'" class="rounded-circle me-2 border" width="45" height="45" style="object-fit:cover;">';
                            echo '<div style="line-height:1.1"><span class="fw-bold text-dark small d-block">Hi, '.$_SESSION['user_name'].'</span></div>';
                        } else {
                            echo '<div class="bg-light rounded-circle p-2 me-2 border text-center" style="width:45px; height:45px;"><i class="fa fa-user text-muted"></i></div>';
                            echo '<span class="fw-bold text-dark small">Hi, User <br> let\'s get started</span>';
                        }
                        ?>
                    </div>
                    <div class="d-grid gap-2">
                        <?php if(!isset($_SESSION['user_name'])): ?>
                            <a href="register.php" class="btn btn-primary btn-sm fw-bold border-0">Join now</a>
                            <a href="login.php" class="btn btn-light btn-sm text-primary fw-bold border">Log in</a>
                        <?php else: ?>
                            <a href="logout.php" class="btn btn-danger btn-sm fw-bold border-0">Logout</a>
                            <a href="my_orders.php" class="btn btn-outline-primary btn-sm fw-bold">My Orders</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card border-0 p-3 text-dark shadow-sm" style="background: #FFF0DF; border-radius: 6px;">
                    <span class="fw-bold small">Get US $10 off with a new supplier</span>
                </div>
                <div class="card border-0 p-3 text-white shadow-sm" style="background: #55BDC3; border-radius: 6px;">
                    <span class="fw-bold small">Send quotes with supplier preferences</span>
                </div>
            </div>
        </div> 
    </div> 
</div> 
</section>

<!-- ========================= 2. DEALS SECTION ========================= -->
<section class="padding-y">
    <div class="container">
        <div class="card border shadow-sm overflow-hidden bg-white">
            <div class="row g-0">
                <div class="col-lg-3 border-end p-4">
                    <h5 class="fw-bold text-dark">Deals and offers</h5>
                    <p class="text-muted small">Electronic equipments</p>
                    <div class="d-flex gap-2 mt-3">
                        <div class="timer-square"><span>04</span><small>Days</small></div>
                        <div class="timer-square"><span>13</span><small>Hour</small></div>
                        <div class="timer-square"><span>34</span><small>Min</small></div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="deals-row-container scroll-hide">
                        <?php
                        $res_d = $conn->query("SELECT * FROM products LIMIT 5");
                        while($deal = $res_d->fetch_assoc()){
                        ?>
                        <div class="col-deal">
                            <a href="product.php?id=<?php echo $deal['product_id']; ?>" class="text-decoration-none">
                                <div class="deal-img-wrapper">
                                    <img src="images/<?php echo $deal['product_image']; ?>">
                                </div>
                                <p class="small text-dark mt-2 mb-1 text-truncate px-2"><?php echo $deal['product_title']; ?></p>
                                <span class="badge rounded-pill bg-danger-light text-danger">-25%</span>
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div> 
        </div> 
    </div> 
</section>

<!-- ========================= 3. HOME AND OUTDOOR (Uses img1.jpg) ========================= -->
<section class="padding-y">
<div class="container">
    <div class="card border shadow-sm overflow-hidden bg-white">
        <div class="row g-0">
            <div class="col-lg-3 p-4 text-white" style="background: url('images/img1.jpg') center/cover no-repeat; min-height: 280px;">
                <div class="bg-dark bg-opacity-25 p-2 rounded" style="max-width: 180px;">
                    <h5 class="fw-bold">Home and <br> outdoor</h5>
                    <a href="shop.php?category_id=3" class="btn btn-light btn-sm mt-3 fw-bold border-0 px-3">Source now</a>
                </div>
            </div>
            <div class="col-lg-9"><div class="row g-0">
                <?php
                $res_h = $conn->query("SELECT * FROM products WHERE product_cat = 3 LIMIT 8");
                while($h = $res_h->fetch_assoc()){
                ?>
                <div class="col-md-3 col-6 border-end border-bottom">
                    <a href="product.php?id=<?php echo $h['product_id']; ?>" class="grid-card-item">
                        <div class="info">
                            <h6 class="mb-0 text-truncate fw-bold small"><?php echo $h['product_title']; ?></h6>
                            <p class="text-muted mb-0 small mt-1">From $<?php echo $h['product_price']; ?></p>
                        </div>
                        <img src="images/<?php echo $h['product_image']; ?>" class="img-side">
                    </a>
                </div>
                <?php } ?>
            </div></div>
        </div> 
    </div>
</div>
</section>

<!-- ========================= 4. CONSUMER ELECTRONICS (Uses img2.jpg) ========================= -->
<section class="padding-y">
<div class="container">
    <div class="card border shadow-sm overflow-hidden bg-white">
        <div class="row g-0">
            <div class="col-lg-3 p-4 text-white" style="background: url('images/img2.jpg') center/cover no-repeat; min-height: 280px;">
                 <div class="bg-dark bg-opacity-25 p-2 rounded" style="max-width: 180px;">
                    <h5 class="fw-bold">Consumer <br> electronics</h5>
                    <a href="shop.php?category_id=1" class="btn btn-light btn-sm mt-3 fw-bold border-0 px-3">Source now</a>
                </div>
            </div>
            <div class="col-lg-9"><div class="row g-0">
                <?php
                $res_e = $conn->query("SELECT * FROM products WHERE product_cat IN (1, 2, 7) LIMIT 8");
                while($e = $res_e->fetch_assoc()){
                ?>
                <div class="col-md-3 col-6 border-end border-bottom">
                    <a href="product.php?id=<?php echo $e['product_id']; ?>" class="grid-card-item">
                        <div class="info">
                            <h6 class="mb-0 text-truncate fw-bold small"><?php echo $e['product_title']; ?></h6>
                            <p class="text-muted mb-0 small mt-1">From $<?php echo $e['product_price']; ?></p>
                        </div>
                        <img src="images/<?php echo $e['product_image']; ?>" class="img-side">
                    </a>
                </div>
                <?php } ?>
            </div></div>
        </div> 
    </div>
</div>
</section>

<!-- ========================= 5. INQUIRY BANNER (FUNCTIONAL) ========================= -->
<section class="padding-y">
    <div class="container">
        <div class="inquiry-banner shadow-sm p-4 p-lg-5" style="background: linear-gradient(135deg, #2C7BE5 0%, #15B2E5 100%); border-radius: 8px;">
            <div class="row align-items-center">
                <div class="col-lg-6 text-white">
                    <h2 class="fw-bold mb-3">An easy way to send requests <br> to all suppliers</h2>
                    <p class="opacity-75">Send your requirements and receive competitive quotes from verified manufacturers in Pakistan.</p>
                </div>
                <div class="col-lg-5 offset-lg-1">
                    <div class="bg-white p-4 rounded text-dark shadow">
                        <h5 class="fw-bold mb-3">Send quote to suppliers</h5>
                        <form method="POST">
                            <input type="text" name="item_name" class="form-control mb-3" placeholder="What item you need?" required>
                            <textarea name="details" class="form-control mb-3" rows="3" placeholder="Type more details (Size, Color, etc.)" required></textarea>
                            <div class="row g-2 mb-3">
                                <div class="col-6"><input type="number" name="quantity" class="form-control" placeholder="Quantity" required></div>
                                <div class="col-6"><select class="form-select border"><option>Pcs</option><option>Kg</option></select></div>
                            </div>
                            <button type="submit" name="submit_inquiry" class="btn btn-primary w-100 fw-bold border-0">Send inquiry</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================= 6. RECOMMENDED ITEMS ========================= -->
<section class="padding-y bg-light">
    <div class="container">
        <h4 class="mb-4 fw-bold text-dark">Recommended items</h4>
        <div class="row g-3">
            <?php
            $res_r = $conn->query("SELECT * FROM products ORDER BY RAND() LIMIT 8");
            while($rec = $res_r->fetch_assoc()){
            ?>
            <div class="col-lg-3 col-md-4 col-6">
                <div class="card h-100 border shadow-sm p-3 bg-white">
                    <a href="product.php?id=<?php echo $rec['product_id']; ?>" style="height: 160px; display: flex; align-items: center; justify-content: center;">
                        <img src="images/<?php echo $rec['product_image']; ?>" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                    </a>
                    <div class="pt-3">
                        <strong class="d-block text-dark" style="font-size:1.1rem;">$<?php echo number_format($rec['product_price'], 0); ?></strong>
                        <a href="product.php?id=<?php echo $rec['product_id']; ?>" class="text-muted small text-decoration-none text-truncate d-block mt-1"><?php echo $rec['product_title']; ?></a>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>

<!-- ========================= 7. EXTRA SERVICES ========================= -->
<section class="padding-y border-top mt-4">
    <div class="container">
        <h4 class="mb-4 fw-bold text-dark">Our extra services</h4>
        <div class="row g-3">
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 overflow-hidden bg-white position-relative">
                    <img src="images/serv1.jpg" style="height: 120px; object-fit: cover;">
                    <div class="card-body p-3 text-start">
                        <h6 class="fw-bold small pe-5">Source from Industry Hubs</h6>
                        <div class="service-icon-box shadow-sm"><i class="fa fa-search"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 overflow-hidden bg-white position-relative">
                    <img src="images/serv2.jpg" style="height: 120px; object-fit: cover;">
                    <div class="card-body p-3 text-start">
                        <h6 class="fw-bold small pe-5">Customize Your Products</h6>
                        <div class="service-icon-box shadow-sm"><i class="fa fa-box-open"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 overflow-hidden bg-white position-relative">
                    <img src="images/serv3.jpg" style="height: 120px; object-fit: cover;">
                    <div class="card-body p-3 text-start">
                        <h6 class="fw-bold small pe-5">Fast Shipping Worldwide</h6>
                        <div class="service-icon-box shadow-sm"><i class="fa fa-paper-plane"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 overflow-hidden bg-white position-relative">
                    <img src="images/serv4.jpg" style="height: 120px; object-fit: cover;">
                    <div class="card-body p-3 text-start">
                        <h6 class="fw-bold small pe-5">Product Monitoring Service</h6>
                        <div class="service-icon-box shadow-sm"><i class="fa fa-shield-alt"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</section>

<?php include 'footer.php'; ?>