<?php 
// Start session at the very top
session_start();

include 'db.php';
include 'functions.php';
include 'header.php'; 

if(isset($_POST['login'])){
    // Sanitize inputs to prevent SQL errors
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass  = mysqli_real_escape_string($conn, $_POST['password']);

    // Query to fetch user details
    $sel_user = "SELECT * FROM users WHERE user_email='$email' AND user_pass='$pass'";
    $run_user = $conn->query($sel_user);

    if($run_user->num_rows > 0){
        $row = $run_user->fetch_assoc();
        
        //  SAVE ALL DATA TO SESSION (Including the Image)
        $_SESSION['user_id']    = $row['user_id'];
        $_SESSION['user_email'] = $row['user_email'];
        $_SESSION['user_name']  = $row['user_name'];
        $_SESSION['user_type']  = $row['user_type'];
        $_SESSION['user_image'] = $row['user_image']; // This grabs 'my_profile.jpg'

        echo "<script>alert('Welcome back, " . $_SESSION['user_name'] . "!'); window.open('index.php','_self');</script>";
    } else {
        echo "<script>alert('Invalid Email or Password. Please try again.');</script>";
    }
}
?>

<section class="padding-y bg-light" style="min-height: 80vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-4 text-center fw-bold">Sign in</h4>
                        
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Email address</label>
                                <input name="email" class="form-control" placeholder="example@mail.com" type="email" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input name="password" class="form-control" placeholder="Enter password" type="password" required>
                            </div>
                            
                            <div class="mb-3 d-flex justify-content-between">
                                <label class="form-check"> 
                                    <input class="form-check-input" type="checkbox" checked>
                                    <span class="form-check-label small"> Remember me </span>
                                </label>
                                <a href="#" class="small text-decoration-none">Forgot password?</a>
                            </div>

                            <div class="mb-4">
                                <button type="submit" name="login" class="btn btn-primary w-100 fw-bold"> Login </button>
                            </div>

                            <p class="text-center small text-muted">Don't have an account? <a href="register.php" class="text-primary text-decoration-none fw-bold">Sign up</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>