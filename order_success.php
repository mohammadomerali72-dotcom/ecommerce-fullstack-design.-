<?php 
include 'db.php';
include 'functions.php';
include 'header.php'; 

// Check if user is logged in
if(!isset($_SESSION['user_email'])){
    echo "<script>window.open('login.php','_self');</script>";
    exit();
}

// 1. Get User ID
$email = $_SESSION['user_email'];
$get_user = "SELECT * FROM users WHERE user_email='$email'";
$run_user = $conn->query($get_user);
$row_user = $run_user->fetch_assoc();
$user_id = $row_user['user_id'];

// 2. CHECK IF FORM WAS SUBMITTED
if(isset($_POST['submit_order'])){
    
    // ✅ CAPTURE DATA FROM FORM
    $address = $_POST['c_address'];
    $city    = $_POST['c_city'];
    $phone   = $_POST['c_phone'];
    $payment = $_POST['c_payment'];
    
    // 3. Calculate Total & Get Products
    $ip = getIPAddress();
    $total = 0;
    $product_ids = [];

    $sel_cart = "SELECT * FROM cart WHERE ip_add='$ip'";
    $run_cart = $conn->query($sel_cart);

    while($row_cart = $run_cart->fetch_assoc()){
        $pro_id = $row_cart['p_id'];
        $qty = $row_cart['qty'];
        $product_ids[] = $pro_id . "(" . $qty . ")";
        
        $get_pro = "SELECT * FROM products WHERE product_id='$pro_id'";
        $run_pro = $conn->query($get_pro);
        while($row_pro = $run_pro->fetch_assoc()){
            $total += ($row_pro['product_price'] * $qty);
        }
    }

    $product_list = implode(", ", $product_ids);
    $invoice = mt_rand(100000, 999999);

    // 4. INSERT INTO ORDERS TABLE
    if($total > 0){
        $insert_order = "INSERT INTO orders (user_id, invoice_no, product_ids, total_amount, address, city, phone, payment_method, order_status) 
                         VALUES ('$user_id', '$invoice', '$product_list', '$total', '$address', '$city', '$phone', '$payment', 'Pending')";
        
        if($conn->query($insert_order)){
            // 5. EMPTY THE CART
            $empty_cart = "DELETE FROM cart WHERE ip_add='$ip'";
            $conn->query($empty_cart);
            
            // Show Success Message
            echo "
            <div class='container py-5 text-center'>
                <div class='row justify-content-center'>
                    <div class='col-md-8 col-lg-6'>
                        <div class='card shadow-sm p-5'>
                            <div class='mb-4'>
                                <!-- Replaced inline style with fa-5x class -->
                                <i class='fa fa-check-circle text-success fa-5x'></i>
                            </div>
                            <h2 class='fw-bold text-success'>Order Placed Successfully!</h2>
                            <p class='lead'>Thank you for shopping with OlexaMart.</p>
                            <hr>
                            <p><strong>Invoice No:</strong> #$invoice</p>
                            <p><strong>Total Amount:</strong> $$total</p>
                            <div class='mt-4'>
                                <a href='index.php' class='btn btn-primary'>Continue Shopping</a>
                                <a href='my_orders.php' class='btn btn-outline-secondary'>My Orders</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>";
        } else {
             echo "<div class='container py-5 text-center'><h3>Failed to place order: " . $conn->error . "</h3></div>";
        }
    }
} else {
    echo "<script>window.open('index.php','_self');</script>";
}

include 'footer.php'; 
?>