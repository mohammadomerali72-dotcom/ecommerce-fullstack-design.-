<?php 
include 'db.php';
include 'header.php'; 

if(!isset($_SESSION['user_email'])){
    echo "<script>window.open('login.php','_self');</script>";
    exit();
}

$email = $_SESSION['user_email'];
$get_user = "SELECT * FROM users WHERE user_email='$email'";
$run_user = $conn->query($get_user);
$row_user = $run_user->fetch_assoc();

// Update Logic
if(isset($_POST['update'])){
    $new_name = $_POST['name'];
    $new_pass = $_POST['pass'];
    
    $update_user = "UPDATE users SET user_name='$new_name', user_pass='$new_pass' WHERE user_email='$email'";
    if($conn->query($update_user)){
        $_SESSION['user_name'] = $new_name; // Update session
        echo "<script>alert('Profile Updated Successfully!'); window.open('settings.php','_self');</script>";
    }
}
?>

<section class="padding-y bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-4">Account Settings</h4>
                        
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" value="<?php echo $row_user['user_name']; ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" value="<?php echo $row_user['user_email']; ?>" disabled>
                                <small class="text-dark">Email cannot be changed.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="text" name="pass" class="form-control" value="<?php echo $row_user['user_pass']; ?>" required>
                            </div>

                            <button type="submit" name="update" class="btn btn-primary w-100">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>