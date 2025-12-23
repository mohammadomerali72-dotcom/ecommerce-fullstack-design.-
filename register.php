<?php 
include 'db.php';
include 'functions.php';
include 'header.php'; 

// Logic to Register User
if(isset($_POST['register'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $type = $_POST['usertype']; // 'customer' or 'supplier'
    $ip = getIPAddress();

    // Check if email already exists
    $check_email = "SELECT * FROM users WHERE user_email='$email'";
    $run_check = $conn->query($check_email);

    if($run_check->num_rows > 0){
        echo "<script>alert('Email already registered! Please Login.');</script>";
    } else {
        // Insert User
        $insert_user = "INSERT INTO users (user_name, user_email, user_pass, user_type, user_ip) VALUES ('$name', '$email', '$pass', '$type', '$ip')";
        if($conn->query($insert_user)){
            echo "<script>alert('Registration Successful! Please Login.'); window.open('login.php','_self');</script>";
        }
    }
}
?>

<section class="padding-y bg-light">
    <div class="container">
        <!-- Used Bootstrap grid to control width and spacing instead of inline CSS -->
        <div class="row justify-content-center mt-5">
            <div class="col-12 col-md-8 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title mb-4 text-center">Sign up</h4>
                        
                        <form method="post">
                            <!-- Name -->
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input name="name" class="form-control" placeholder="Type full name" type="text" required>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input name="email" class="form-control" placeholder="Type email" type="email" required>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input name="password" class="form-control" placeholder="At least 6 characters" type="password" required>
                            </div>

                            <!-- User Type (Hidden logic or Select) -->
                            <div class="mb-3">
                                 <label class="form-label">I am a</label>
                                 <select name="usertype" class="form-select">
                                     <option value="customer">Customer (Buyer)</option>
                                     <option value="supplier">Supplier (Seller)</option>
                                 </select>
                            </div>

                            <div class="mb-4">
                                <button type="submit" name="register" class="btn btn-primary w-100"> Register </button>
                            </div>

                            <p class="text-center mb-1">Have an account? <a href="login.php">Log in</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>