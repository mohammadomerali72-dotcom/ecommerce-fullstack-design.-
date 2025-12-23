<?php 
include 'db.php';
include 'header.php'; 

if(!isset($_SESSION['user_email'])){
    echo "<script>window.open('login.php','_self');</script>";
    exit();
}

if(isset($_GET['order_id'])){
    $order_id = $_GET['order_id'];
    $get_order = "SELECT * FROM orders WHERE order_id='$order_id'";
    $run_order = $conn->query($get_order);
    $row_order = $run_order->fetch_assoc();
    
    $invoice = $row_order['invoice_no'];
    $amount = $row_order['total_amount'];
    $status = $row_order['order_status'];
    $date = date('d M Y', strtotime($row_order['order_date']));
    $address = $row_order['address'];
    
    // Convert string "1(2), 5(1)" back to array
    $product_ids_string = $row_order['product_ids']; 
    $prod_array = explode(", ", $product_ids_string);
}
?>

<section class="padding-y bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Order Details</h3>
            <a href="my_orders.php" class="btn btn-outline-secondary">Back to Orders</a>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <strong>Invoice: #<?php echo $invoice; ?></strong> 
                <span class="float-end badge bg-<?php echo ($status=='Pending')?'warning':'success'; ?>"><?php echo $status; ?></span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Date:</strong> <?php echo $date; ?></p>
                        <p><strong>Shipping Address:</strong> <?php echo $address; ?></p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p><strong>Total Amount:</strong> <span class="text-primary fw-bold h5">$<?php echo $amount; ?></span></p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($prod_array as $item){
                                // Parse ID(Qty) -> e.g. "5(2)"
                                preg_match('/(\d+)\((\d+)\)/', $item, $matches);
                                $p_id = $matches[1];
                                $p_qty = $matches[2];

                                $get_pro = "SELECT * FROM products WHERE product_id='$p_id'";
                                $run_pro = $conn->query($get_pro);
                                $row_pro = $run_pro->fetch_assoc();
                            ?>
                            <tr>
                                <td><img src="images/<?php echo $row_pro['product_image']; ?>" width="50"></td>
                                <td><?php echo $row_pro['product_title']; ?></td>
                                <td>x <?php echo $p_qty; ?></td>
                                <td>$<?php echo $row_pro['product_price'] * $p_qty; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>