<?php
//  include_once prevents connection errors if files are called multiple times
include_once 'db.php';
include_once 'functions.php'; 

// Start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//  DYNAMIC PROFILE PICTURE LOGIC (Local DB Image with Gravatar Fallback)
$final_pic = "https://www.gravatar.com/avatar/?d=mp"; // Default Placeholder
if(isset($_SESSION['user_name'])){
    $u_img = isset($_SESSION['user_image']) ? $_SESSION['user_image'] : '';
    $u_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';
    
    // Check if the user has an image in the database and if the file exists on the server
    if(!empty($u_img) && file_exists("images/$u_img")){
        $final_pic = "images/$u_img";
    } else {
        // Fallback: Generate a unique colorful avatar based on their email
        $hash = md5(strtolower(trim($u_email)));
        $final_pic = "https://www.gravatar.com/avatar/$hash?d=identicon&s=150";
    }
}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>OlexaMart | Your Premium Shop</title>
    
    <!-- Bootstrap 5 & FontAwesome 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- CUSTOM CSS -->
    <link href="css/style.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css">
</head>
<body>

<!-- ========================= MOBILE HEADER (App Style) ========================= -->
<header class="mobile-header d-block d-lg-none border-bottom bg-white shadow-sm sticky-top">
    <div class="d-flex justify-content-between align-items-center px-3 py-2">
        <div class="d-flex align-items-center">
            <button class="btn btn-light border-0 me-2 p-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                <i class="fa fa-bars fs-5"></i>
            </button>
            <a href="index.php" class="text-decoration-none fw-bold text-primary fs-5">
                <i class="fa fa-shopping-bag"></i> OlexaMart
            </a>
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="cart.php" class="text-dark position-relative">
                <i class="fa fa-shopping-cart fs-5"></i>
                <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light" style="font-size: 8px;">
                    <?php echo count($_SESSION['cart']); ?>
                </span>
                <?php endif; ?>
            </a>
            <!--  CORRECTED: Clicking the image now opens Settings/Profile instead of Orders -->
            <a href="<?php echo isset($_SESSION['user_name']) ? 'settings.php' : 'login.php'; ?>">
                <img src="<?php echo $final_pic; ?>" class="rounded-circle border" width="30" height="30" style="object-fit: cover; background:#f8f9fa;">
            </a>
        </div>
    </div>
</header>

<!-- ========================= DESKTOP HEADER ========================= -->
<header class="section-header bg-white d-none d-lg-block border-bottom">
    
    <!-- TOP ROW: Logo | Search | Icons -->
    <div class="container py-3">
        <div class="row align-items-center">
            
            <!-- 1. LOGO -->
            <div class="col-lg-2">
                <a href="index.php" class="d-flex align-items-center text-decoration-none">
                    <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center me-2" style="width: 42px; height: 42px;">
                        <i class="fa fa-shopping-bag fs-5"></i>
                    </div>
                    <span class="fs-4 fw-bold text-primary">OlexaMart</span>
                </a>
            </div>

            <!-- 2. SEARCH BAR -->
            <div class="col-lg-5">
                <form action="shop.php" method="GET">
                    <div class="input-group border border-primary rounded overflow-hidden shadow-sm">
                        <input type="text" class="form-control border-0" name="search" placeholder="Search products...">
                        <select class="form-select border-0 border-start bg-light" name="category_id" style="max-width: 130px;">
                            <option value="">All Category</option>
                            <?php 
                            if(isset($conn)){
                                $cat_q = $conn->query("SELECT * FROM categories");
                                while($cat = $cat_q->fetch_assoc()){ 
                                    echo '<option value="'.$cat['cat_id'].'">'.$cat['cat_title'].'</option>'; 
                                } 
                            }
                            ?>
                        </select>
                        <button class="btn btn-primary px-4" type="submit">Search</button>
                    </div>
                </form>
            </div>

            <!-- 3. ICONS (Profile, Message, Orders, Wishlist, Cart) -->
            <div class="col-lg-5">
                <div class="d-flex justify-content-end gap-4 align-items-center">
                    
                    <!-- Profile -->
                    <a href="<?php echo isset($_SESSION['user_name']) ? 'settings.php' : 'login.php'; ?>" class="text-decoration-none text-muted text-center">
                        <?php if(isset($_SESSION['user_name'])): ?>
                            <img src="<?php echo $final_pic; ?>" class="rounded-circle border mb-1 shadow-sm" width="24" height="24" style="object-fit: cover;">
                        <?php else: ?>
                            <i class="fa fa-user d-block fs-5 mb-1"></i>
                        <?php endif; ?>
                        <span class="small d-block">Profile</span>
                    </a>

                    <!-- Message -->
                    <a href="contact.php" class="text-decoration-none text-muted text-center">
                        <i class="fa fa-comment-dots d-block fs-5 mb-1"></i>
                        <span class="small d-block">Message</span>
                    </a>

                    <!-- Orders -->
                    <a href="my_orders.php" class="text-decoration-none text-muted text-center">
                        <i class="fa fa-box d-block fs-5 mb-1"></i>
                        <span class="small d-block">Orders</span>
                    </a>

                    <!-- Wishlist -->
                    <a href="wishlist.php" class="text-decoration-none text-muted text-center">
                        <i class="fa fa-heart d-block fs-5 mb-1"></i>
                        <span class="small d-block">Wishlist</span>
                    </a>

                    <!-- My Cart -->
                    <a href="cart.php" class="text-decoration-none text-muted text-center position-relative">
                        <i class="fa fa-shopping-cart d-block fs-5 mb-1"></i>
                        <span class="small d-block">My cart</span>
                        <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" style="margin-top: -5px;"></span>
                        <?php endif; ?>
                    </a>

                </div>
            </div>

        </div>
    </div>

    <!-- NAVBAR ROW -->
    <div class="bg-white border-top">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light p-0">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link ps-0 fw-bold" href="shop.php"><i class="fa fa-bars me-2"></i> All category</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="shop.php">All products</a></li>
                    <li class="nav-item"><a class="nav-link" href="services.php">Services</a></li>
                </ul>

                <!-- SHIP TO: Expanded Country List -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-dark fw-bold" href="#" data-bs-toggle="dropdown">
                            Ship to <img id="currentFlag" src="https://flagcdn.com/w20/pk.png" class="ms-1 border" width="20">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="min-width: 200px; max-height: 350px; overflow-y: auto;">
                            <li><a class="dropdown-item" href="#" onclick="setCountry('Pakistan', 'pk')"><img src="https://flagcdn.com/w20/pk.png" width="20" class="me-2 border"> Pakistan</a></li>
                            <li><a class="dropdown-item" href="#" onclick="setCountry('USA', 'us')"><img src="https://flagcdn.com/w20/us.png" width="20" class="me-2 border"> USA</a></li>
                            <li><a class="dropdown-item" href="#" onclick="setCountry('UAE', 'ae')"><img src="https://flagcdn.com/w20/ae.png" width="20" class="me-2 border"> UAE</a></li>
                            <li><a class="dropdown-item" href="#" onclick="setCountry('UK', 'gb')"><img src="https://flagcdn.com/w20/gb.png" width="20" class="me-2 border"> United Kingdom</a></li>
                            <li><a class="dropdown-item" href="#" onclick="setCountry('Canada', 'ca')"><img src="https://flagcdn.com/w20/ca.png" width="20" class="me-2 border"> Canada</a></li>
                            <li><a class="dropdown-item" href="#" onclick="setCountry('Germany', 'de')"><img src="https://flagcdn.com/w20/de.png" width="20" class="me-2 border"> Germany</a></li>
                            <li><a class="dropdown-item" href="#" onclick="setCountry('Australia', 'au')"><img src="https://flagcdn.com/w20/au.png" width="20" class="me-2 border"> Australia</a></li>
                            <li><a class="dropdown-item" href="#" onclick="setCountry('Saudi Arabia', 'sa')"><img src="https://flagcdn.com/w20/sa.png" width="20" class="me-2 border"> Saudi Arabia</a></li>
                            <li><a class="dropdown-item" href="#" onclick="setCountry('China', 'cn')"><img src="https://flagcdn.com/w20/cn.png" width="20" class="me-2 border"> China</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>

<!-- MOBILE OFFCANVAS SIDEBAR -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu">
  <div class="offcanvas-header bg-light py-4 border-bottom">
    <div class="d-flex align-items-center">
       <img src="<?php echo $final_pic; ?>" class="rounded-circle border me-3" width="55" height="55" style="object-fit: cover; background: #fff;">
       <div>
         <?php if(isset($_SESSION['user_name'])): ?>
            <h6 class="mb-0 fw-bold"><?php echo $_SESSION['user_name']; ?></h6>
            <small><a href="logout.php" class="text-danger text-decoration-none">Sign out</a></small>
         <?php else: ?>
            <a href="login.php" class="text-dark fw-bold text-decoration-none">Sign in</a> | <a href="register.php" class="text-dark fw-bold text-decoration-none">Register</a>
         <?php endif; ?>
       </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body p-0">
    <div class="list-group list-group-flush">
        <a href="index.php" class="list-group-item border-0 px-4 py-3"><i class="fa fa-home me-3 text-muted" style="width:20px;"></i> Home</a>
        <a href="shop.php" class="list-group-item border-0 px-4 py-3"><i class="fa fa-th-large me-3 text-muted" style="width:20px;"></i> Categories</a>
        <a href="my_orders.php" class="list-group-item border-0 px-4 py-3"><i class="fa fa-box me-3 text-muted" style="width:20px;"></i> My orders</a>
        <a href="wishlist.php" class="list-group-item border-0 px-4 py-3"><i class="fa fa-heart me-3 text-muted" style="width:20px;"></i> Wishlist</a>
        <hr class="my-0 mx-4">
        <a href="contact.php" class="list-group-item border-0 px-4 py-3"><i class="fa fa-headset me-3 text-muted" style="width:20px;"></i> Support</a>
    </div>
  </div>
</div>

<!-- ⭐ JAVASCRIPT: Logic to Update and Remember Flag ⭐ -->
<script>
function setCountry(countryName, flagCode) {
    var flagUrl = "https://flagcdn.com/w20/" + flagCode + ".png";
    document.getElementById("currentFlag").src = flagUrl;
    localStorage.setItem("selectedFlag", flagUrl);
}

document.addEventListener("DOMContentLoaded", function() {
    var savedFlag = localStorage.getItem("selectedFlag");
    if(savedFlag) document.getElementById("currentFlag").src = savedFlag;
});
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>