<?php 
include 'db.php';
include 'functions.php';
include 'header.php'; 

// Check login
if(!isset($_SESSION['user_email'])){
    echo "<script>window.open('login.php','_self');</script>";
    exit();
}

$email = $_SESSION['user_email'];
$get_user = "SELECT * FROM users WHERE user_email='$email'";
$run_user = $conn->query($get_user);
$row_user = $run_user->fetch_assoc();
$user_id = $row_user['user_id'];
?>

<section class="padding-y bg-light">
    <div class="container">
        <h3 class="mb-4">My Orders</h3>
        
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Invoice No</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $get_orders = "SELECT * FROM orders WHERE user_id='$user_id' ORDER BY order_id DESC";
                        $run_orders = $conn->query($get_orders);

                        if($run_orders->num_rows > 0){
                            while($row_order = $run_orders->fetch_assoc()){
                                $order_id = $row_order['order_id'];
                                $invoice = $row_order['invoice_no'];
                                $amount = $row_order['total_amount'];
                                $date = date('d M Y', strtotime($row_order['order_date']));
                                $status = $row_order['order_status'];
                        ?>
                        <tr>
                            <td>#<?php echo $invoice; ?></td>
                            <td><?php echo $date; ?></td>
                            <td>$<?php echo $amount; ?></td>
                            <td>
                                <span class="badge bg-<?php echo ($status=='Pending')?'warning':'success'; ?>">
                                    <?php echo $status; ?>
                                </span>
                            </td>
                            <td>
                                <a href="view_order.php?order_id=<?php echo $order_id; ?>" class="btn btn-sm btn-primary">View Details</a>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center'>You have no orders yet.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>