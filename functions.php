<?php 
// Connect to Database if not already connected
if (!isset($conn)) {
    include('db.php');
}

// ==========================================
// 1. FUNCTION: GET USER IP ADDRESS
// ==========================================
function getIPAddress() {  
    if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
        $ip = $_SERVER['HTTP_CLIENT_IP'];  
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
    } else {  
        $ip = $_SERVER['REMOTE_ADDR'];  
    }  
    return $ip;  
}  

// ==========================================
// 2. FUNCTION: ADD TO CART
// ==========================================
function add_cart(){
    global $conn; 

    if(isset($_GET['add_cart'])){
        $ip = getIPAddress();
        $p_id = $_GET['add_cart'];

        // Check if product is already in cart
        $check_product = "SELECT * FROM cart WHERE ip_add='$ip' AND p_id='$p_id'";
        $run_check = mysqli_query($conn, $check_product);

        if(mysqli_num_rows($run_check) > 0){
            echo "<script>alert('This product is already in your cart!');</script>";
            echo "<script>window.open('cart.php','_self');</script>";
        } else {
            // Insert into cart
            $query = "INSERT INTO cart (p_id, ip_add, qty) VALUES ('$p_id', '$ip', '1')";
            $run_query = mysqli_query($conn, $query);
            
            // Redirect to Cart Page to show the item
            echo "<script>window.open('cart.php','_self');</script>"; 
        }
    }
}

// ==========================================
// 3. FUNCTION: COUNT ITEMS
// ==========================================
function count_items(){
    global $conn;
    $ip = getIPAddress();
    $get_items = "SELECT * FROM cart WHERE ip_add='$ip'";
    $run_items = mysqli_query($conn, $get_items);
    $count = mysqli_num_rows($run_items);
    return $count;
}

// ==========================================
// 4. FUNCTION: GET TOTAL PRICE
// ==========================================
function total_price(){
    global $conn;
    $ip = getIPAddress();
    $total = 0;
    
    $sel_cart = "SELECT * FROM cart WHERE ip_add='$ip'";
    $run_cart = mysqli_query($conn, $sel_cart);
    
    while($record = mysqli_fetch_array($run_cart)){
        $pro_id = $record['p_id'];
        $pro_qty = $record['qty'];
        
        $get_price = "SELECT * FROM products WHERE product_id='$pro_id'";
        $run_price = mysqli_query($conn, $get_price);
        
        while($p_price = mysqli_fetch_array($run_price)){
            $product_price = $p_price['product_price'];
            $sub_total = $product_price * $pro_qty;
            $total += $sub_total;
        }
    }
    echo "$" . $total;
}

// ==========================================
// 5. LOGIC FOR "BUY NOW" BUTTON (Direct Checkout)
// ==========================================
if(isset($_GET['buy_now'])){
    global $conn;
    $ip = getIPAddress();
    $p_id = $_GET['buy_now'];

    // 1. Check if product is already in cart
    $check_product = "SELECT * FROM cart WHERE ip_add='$ip' AND p_id='$p_id'";
    $run_check = mysqli_query($conn, $check_product);

    // 2. If NOT in cart, add it
    if(mysqli_num_rows($run_check) == 0){
        $query = "INSERT INTO cart (p_id, ip_add, qty) VALUES ('$p_id', '$ip', '1')";
        mysqli_query($conn, $query);
    }

    // 3. REDIRECT DIRECTLY TO CHECKOUT (Skip Cart Page)
    echo "<script>window.open('checkout.php','_self');</script>";
}

// ==========================================
// ⭐ TRIGGER ADD TO CART FUNCTION
// ==========================================
if(isset($_GET['add_cart'])){
    add_cart();
}
?>