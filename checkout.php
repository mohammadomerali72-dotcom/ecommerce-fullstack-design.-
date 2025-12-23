<?php 
include 'db.php'; 
include 'functions.php';
include 'header.php'; 

// 1. CHECK LOGIN
if(!isset($_SESSION['user_email'])){
    echo "<script>alert('Please Login first!'); window.open('login.php','_self');</script>";
    exit();
}

// 2. CHECK CART
$ip = getIPAddress();
$check_cart = "SELECT * FROM cart WHERE ip_add='$ip'";
$run_cart_check = mysqli_query($conn, $check_cart);
if(mysqli_num_rows($run_cart_check) == 0){
    echo "<script>window.open('index.php','_self');</script>";
    exit();
}
?>

<section class="padding-y bg-light">
    <div class="container">
        <h3 class="card-title mb-4">Checkout</h3>
        <div class="row">
            
            <!-- LEFT: FORM -->
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Delivery Info</h4>
                        
                        <form action="order_success.php" method="POST">
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="c_name" class="form-control" placeholder="John" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" class="form-control" placeholder="Doe">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <!-- ✅ Important: name="c_phone" -->
                                <input type="text" name="c_phone" class="form-control" placeholder="+92 300 1234567" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <!-- ✅ Important: name="c_address" -->
                                <textarea name="c_address" class="form-control" rows="2" placeholder="House number, Street name" required></textarea>
                            </div>

                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label">City</label>
                                    <!-- ✅ Important: name="c_city" -->
                                    <input type="text" name="c_city" class="form-control" placeholder="Karachi" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Postal Code</label>
                                    <input type="text" class="form-control" placeholder="75000">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Payment Method</label>
                                <!-- ✅ Important: name="c_payment" -->
                                <select name="c_payment" class="form-select">
                                    <option value="COD">Cash on Delivery (COD)</option>
                                    <option value="Card">Credit Card (Stripe/Visa)</option>
                                    <option value="Bank">Bank Transfer</option>
                                </select>
                            </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: SUMMARY -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Order Summary</h5>
                        <?php
                        $total = 0;
                        $sel_cart = "SELECT * FROM cart WHERE ip_add='$ip'";
                        $run_cart = mysqli_query($conn, $sel_cart);
                        while($row_cart = mysqli_fetch_array($run_cart)){
                            $pro_id = $row_cart['p_id'];
                            $qty = $row_cart['qty'];
                            $get_product = "SELECT * FROM products WHERE product_id='$pro_id'";
                            $run_product = mysqli_query($conn, $get_product);
                            while($row_pro = mysqli_fetch_array($run_product)){
                                $sub_total = $row_pro['product_price'] * $qty;
                                $total += $sub_total;
                        ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span><?php echo $row_pro['product_title']; ?> (x<?php echo $qty; ?>)</span>
                            <span class="fw-bold">$<?php echo $sub_total; ?></span>
                        </div>
                        <?php }} ?>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold">Total Amount:</span>
                            <span class="fw-bold text-primary h5">$<?php echo $total; ?></span>
                        </div>
                        
                        <!-- ✅ SUBMIT BUTTON -->
                        <button type="submit" name="submit_order" class="btn btn-success w-100 mt-3">Place Order</button>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>