<?php 
include 'db.php'; 
include 'functions.php'; // We need this to get the IP address

// ================= 1. ADD ITEM LOGIC =================
// This handles ?add=123 from your shop buttons
if(isset($_GET['add'])){
    
    $p_id = $_GET['add'];
    $ip = getIPAddress();

    // Check if product is already in cart
    $check_query = "SELECT * FROM cart WHERE ip_add='$ip' AND p_id='$p_id'";
    $run_check = mysqli_query($conn, $check_query);

    if(mysqli_num_rows($run_check) > 0){
        // If exists, just alert and go to cart
        echo "<script>alert('This product is already in your cart!');</script>";
        echo "<script>window.open('cart.php','_self');</script>";
    } else {
        // If not exists, insert it into Database
        $insert_query = "INSERT INTO cart (p_id, ip_add, qty) VALUES ('$p_id', '$ip', '1')";
        
        if(mysqli_query($conn, $insert_query)){
            // Success! Go to cart page
            echo "<script>window.open('cart.php','_self');</script>";
        }
    }
}

// ================= 2. REMOVE ITEM LOGIC =================
// Note: Your cart.php actually handles removal itself, 
// but we can keep this here just in case you link to it directly.
if(isset($_GET['remove_id'])){
    $remove_id = $_GET['remove_id'];
    $ip = getIPAddress();
    
    $delete_query = "DELETE FROM cart WHERE p_id='$remove_id' AND ip_add='$ip'";
    $run_delete = mysqli_query($conn, $delete_query);
    
    if($run_delete){
        echo "<script>window.open('cart.php','_self');</script>";
    }
}   
?>