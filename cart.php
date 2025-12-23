<?php 
// 1. Connection and Session Setup
session_start();
include_once 'db.php';
include_once 'functions.php';
include_once 'header.php'; 

// Standardize database connection variable
if (!isset($conn)) {
    if (isset($db)) { $conn = $db; } 
    elseif (isset($con)) { $conn = $con; }
}

$ip = getIPAddress();

// ✅ 2. HANDLE "REMOVE ALL" ACTION
if(isset($_GET['remove_all'])){
    mysqli_query($conn, "DELETE FROM cart WHERE ip_add='$ip'");
    echo "<script>alert('Cart has been cleared!'); window.open('cart.php','_self')</script>";
}

// ✅ 3. HANDLE "SAVE FOR LATER" (Move from Cart to Wishlist)
if(isset($_GET['save_id'])){
    if(!isset($_SESSION['user_email'])){
        echo "<script>alert('Please login to save items!'); window.open('login.php','_self')</script>";
    } else {
        $p_id = mysqli_real_escape_string($conn, $_GET['save_id']);
        $email = $_SESSION['user_email'];
        
        // Get user_id from database
        $u_info = $conn->query("SELECT user_id FROM users WHERE user_email='$email'")->fetch_assoc();
        $u_id = $u_info['user_id'];

        // Logic: Add to Wishlist, then Remove from Cart
        $conn->query("INSERT IGNORE INTO wishlist (user_id, product_id) VALUES ('$u_id', '$p_id')");
        $conn->query("DELETE FROM cart WHERE p_id='$p_id' AND ip_add='$ip'");
        
        echo "<script>alert('Item saved for later!'); window.open('cart.php','_self')</script>";
    }
}

// 4. HANDLE DELETE SINGLE ITEM
if(isset($_GET['delete_id'])){
    $del_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    mysqli_query($conn, "DELETE FROM cart WHERE p_id='$del_id' AND ip_add='$ip'");
    echo "<script>window.open('cart.php','_self')</script>";
}

// 5. HANDLE QUANTITY CHANGE
if(isset($_POST['change_qty'])){
    $p_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $new_qty = $_POST['qty'] + ($_POST['change_qty'] == 'increase' ? 1 : -1);
    if($new_qty >= 1) {
        mysqli_query($conn, "UPDATE cart SET qty='$new_qty' WHERE p_id='$p_id' AND ip_add='$ip'");
    }
    echo "<script>window.open('cart.php','_self')</script>";
}
?>

<section class="padding-y bg-light" style="min-height: 80vh;">
    <div class="container">
        
        <h4 class="fw-bold mb-4">Shopping cart</h4>

        <div class="row">
            <!-- 🛒 LEFT COLUMN: CART ITEMS -->
            <div class="col-lg-9">
                <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                    <div class="card-body p-0">
                        <?php 
                        $subtotal = 0; $tax = 14.00;
                        $run_cart = $conn->query("SELECT * FROM cart c JOIN products p ON c.p_id = p.product_id WHERE c.ip_add = '$ip'");
                        $count_cart = ($run_cart) ? $run_cart->num_rows : 0;

                        if($count_cart > 0){
                            while($row = $run_cart->fetch_assoc()){
                                $total = $row['product_price'] * $row['qty'];
                                $subtotal += $total;
                        ?>
                        <!-- ✅ ITEM ROW (Aligned in one line) -->
                        <article class="p-3 border-bottom bg-white">
                            <div class="row align-items-center g-3">
                                
                                <!-- 1. Image -->
                                <div class="col-3 col-lg-2">
                                    <div class="rounded border p-2 bg-light d-flex align-items-center justify-content-center" style="height: 90px;">
                                        <img src="images/<?php echo $row['product_image']; ?>" style="max-height: 100%; max-width:100%; object-fit:contain;">
                                    </div>
                                </div>

                                <!-- 2. Info & Actions -->
                                <div class="col-9 col-lg-4">
                                    <h6 class="mb-1 fw-bold"><a href="product.php?id=<?php echo $row['product_id']; ?>" class="text-dark text-decoration-none"><?php echo $row['product_title']; ?></a></h6>
                                    <p class="text-muted small mb-2">Seller: OlexaMart Official</p>
                                    <div class="d-flex gap-2">
                                        <!-- Functional Delete -->
                                        <a href="cart.php?delete_id=<?php echo $row['product_id']; ?>" class="btn btn-sm btn-outline-danger border-0 px-0 small"><i class="fa fa-trash"></i> Remove</a>
                                        <!-- Functional Save -->
                                        <a href="cart.php?save_id=<?php echo $row['product_id']; ?>" class="btn btn-sm btn-outline-primary border-0 px-0 small ms-2"><i class="fa fa-heart"></i> Save</a>
                                    </div>
                                </div>

                                <!-- 3. Quantity Controls -->
                                <div class="col-6 col-lg-3">
                                    <form method="post" class="d-flex align-items-center justify-content-lg-center">
                                        <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                        <input type="hidden" name="qty" value="<?php echo $row['qty']; ?>">
                                        <div class="qty-input-group border rounded-2 bg-white d-flex align-items-center">
                                            <button type="submit" name="change_qty" value="decrease" class="btn btn-light btn-sm border-0 px-3 fw-bold">-</button>
                                            <div class="px-3 fw-bold small"><?php echo $row['qty']; ?></div>
                                            <button type="submit" name="change_qty" value="increase" class="btn btn-light btn-sm border-0 px-3 fw-bold">+</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- 4. Price -->
                                <div class="col-6 col-lg-3 text-end">
                                    <div class="h5 fw-bold text-dark mb-0">$<?php echo number_format($total, 0); ?></div>
                                    <small class="text-muted d-block">$<?php echo $row['product_price']; ?> / per item</small>
                                </div>

                            </div>
                        </article>
                        <?php } } else { ?>
                            <div class='text-center py-5'>
                                <i class="fa fa-shopping-basket fa-3x text-muted mb-3 opacity-25"></i>
                                <h5>Your cart is empty</h5>
                                <a href="shop.php" class="btn btn-primary mt-3 px-4 fw-bold">Start Shopping</a>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="shop.php" class="btn btn-light border fw-bold px-4"> <i class="fa fa-arrow-left"></i> Back to shop</a>
                    <?php if($count_cart > 0): ?>
                        <!-- ✅ WORKING REMOVE ALL BUTTON -->
                        <a href="cart.php?remove_all=true" class="btn btn-light border text-danger fw-bold px-4" onclick="return confirm('Clear your entire cart?')">Remove all</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 💰 RIGHT COLUMN: SUMMARY -->
            <div class="col-lg-3">
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body">
                        <label class="form-label text-muted small fw-bold">Have a coupon?</label>
                        <div class="input-group">
                            <input type="text" class="form-control shadow-none" placeholder="Add code">
                            <button class="btn btn-light border">Apply</button>
                        </div>
                    </div>
                </div>
                
                <div class="card shadow-sm border-0 bg-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                          <span class="text-muted">Subtotal:</span><span class="text-dark fw-bold">$<?php echo number_format($subtotal, 0); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                          <span class="text-muted">Tax:</span><span class="text-success fw-bold">+ $<?php echo ($count_cart > 0) ? $tax : 0; ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                          <span class="h5 fw-bold">Total:</span><span class="h5 fw-bold text-dark">$<?php echo ($count_cart > 0) ? number_format($subtotal + $tax, 0) : 0; ?></span>
                        </div>
                        
                        <?php if($count_cart > 0): ?>
                            <a href="checkout.php" class="btn btn-success w-100 fw-bold py-2 shadow-sm rounded-2">Proceed to Checkout</a>
                        <?php endif; ?>
                        
                        <div class="mt-4 text-center">
                            <img src="https://flagcdn.com/w20/pk.png" width="16" class="me-1"> <small class="text-muted">Secure checkout in Pakistan</small>
                            <div class="mt-2"><img src="https://bootstrap-ecommerce.com/bootstrap5-ecommerce/images/misc/payments.png" height="22"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 💾 SAVED FOR LATER SECTION -->
        <h5 class="mt-5 mb-3 fw-bold">Recommended for you</h5>
        <div class="row g-3">
            <?php
            $rec_res = $conn->query("SELECT * FROM products ORDER BY RAND() LIMIT 4");
            while($rec = $rec_res->fetch_assoc()){
            ?>
            <div class="col-lg-3 col-md-4 col-6">
                <div class="card h-100 border-0 shadow-sm p-3 bg-white">
                    <a href="product.php?id=<?php echo $rec['product_id']; ?>" style="height:150px; display:flex; align-items:center; justify-content:center;">
                        <img src="images/<?php echo $rec['product_image']; ?>" style="max-height:100%; max-width:100%; object-fit:contain;">
                    </a>
                    <div class="mt-3">
                        <strong class="d-block text-dark">$<?php echo $rec['product_price']; ?></strong>
                        <a href="product.php?id=<?php echo $rec['product_id']; ?>" class="text-muted small text-decoration-none text-truncate d-block mb-3"><?php echo $rec['product_title']; ?></a>
                        <a href="?add_cart=<?php echo $rec['product_id']; ?>" class="btn btn-outline-primary btn-sm w-100 fw-bold"><i class="fa fa-shopping-cart"></i> Move to cart</a>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

    </div>
</section>

<?php include 'footer.php'; ?>