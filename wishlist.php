<?php 
// 1. Connection and Session Setup
session_start();
include_once 'db.php';
include_once 'functions.php';

// Standardize database connection variable
if (!isset($conn)) {
    if (isset($db)) { $conn = $db; } 
    elseif (isset($con)) { $conn = $con; }
}

// 2. SECURITY: Redirect to login if user is not logged in
if(!isset($_SESSION['user_email'])){
    echo "<script>alert('Please login to view your wishlist'); window.open('login.php','_self')</script>";
    exit();
}

// 3. GET USER ID FROM DATABASE
$email = $_SESSION['user_email'];
$user_q = $conn->query("SELECT user_id FROM users WHERE user_email='$email'");
$user_data = $user_q->fetch_assoc();
$u_id = $user_data['user_id'];

// 4. HANDLE REMOVE ACTION
if(isset($_GET['remove_id'])){
    $pid = (int)$_GET['remove_id'];
    $conn->query("DELETE FROM wishlist WHERE user_id='$u_id' AND product_id='$pid'");
    echo "<script>window.open('wishlist.php','_self')</script>";
}

include_once 'header.php'; 
?>

<section class="padding-y bg-light" style="min-height: 80vh;">
    <div class="container">
        
        <h4 class="fw-bold mb-4">My Wishlist</h4>

        <div class="row g-3">
            <?php
            //  Fetch products joined with the wishlist table for this specific user
            $wish_sql = "SELECT p.* FROM wishlist w 
                         JOIN products p ON w.product_id = p.product_id 
                         WHERE w.user_id = '$u_id'";
            $wish_res = $conn->query($wish_sql);

            if($wish_res && $wish_res->num_rows > 0){
                while($row = $wish_res->fetch_assoc()){
            ?>
                <!-- PRODUCT CARD -->
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="card h-100 border-0 shadow-sm p-3 bg-white">
                        
                        <!-- Image Area -->
                        <a href="product.php?id=<?php echo $row['product_id']; ?>" style="height:160px; display:flex; align-items:center; justify-content:center;">
                            <img src="images/<?php echo $row['product_image']; ?>" style="max-height:100%; max-width:100%; object-fit:contain;">
                        </a>
                        
                        <!-- Info Area -->
                        <div class="pt-3">
                            <strong class="d-block text-dark" style="font-size:1.1rem;">$<?php echo number_format($row['product_price'], 0); ?></strong>
                            <a href="product.php?id=<?php echo $row['product_id']; ?>" class="text-muted small text-decoration-none text-truncate d-block mb-3">
                                <?php echo $row['product_title']; ?>
                            </a>
                            
                            <!-- Action Buttons -->
                            <div class="d-grid gap-2">
                                <a href="cart.php?add_cart=<?php echo $row['product_id']; ?>" class="btn btn-primary btn-sm fw-bold">
                                   <i class="fa fa-shopping-cart me-1"></i> Add to cart
                                </a>
                                <a href="wishlist.php?remove_id=<?php echo $row['product_id']; ?>" class="btn btn-outline-danger btn-sm small border-0">
                                    <i class="fa fa-trash me-1"></i> Remove
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
                } 
            } else { 
                //  EMPTY STATE VIEW
            ?>
                <div class="col-12 text-center py-5">
                    <div class="mb-4">
                        <i class="fa fa-heart-broken fa-4x text-muted opacity-25"></i>
                    </div>
                    <h5 class="text-dark">Your wishlist is empty</h5>
                    <p class="text-muted small mb-4">You haven't saved any products yet. Start exploring our shop!</p>
                    <a href="shop.php" class="btn btn-primary px-5 fw-bold">Go to Shop</a>
                </div>
            <?php } ?>
        </div>

    </div>
</section>

<?php include 'footer.php'; ?>