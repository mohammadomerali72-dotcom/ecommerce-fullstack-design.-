<?php
// ✅ Safety check for DB connection
if(!isset($conn)) { include 'db.php'; }
?>

<!-- ========================= NEWSLETTER SECTION ========================= -->
<section class="py-5 bg-light border-top">
    <div class="container text-center">
        <h5 class="fw-bold text-dark mb-2">Subscribe to our newsletter</h5>
        <p class="text-muted mb-4 small">Get daily news on upcoming offers from many suppliers all over the world</p>

        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8">
                <?php
                if(isset($_POST['subscribe'])){
                    $email = mysqli_real_escape_string($conn, $_POST['user_email']);
                    if(!empty($email)){
                        $sub_sql = "INSERT INTO newsletter (email) VALUES ('$email')";
                        if($conn->query($sub_sql)){
                            echo "<div class='alert alert-success py-2 small mb-3'>Subscribed Successfully!</div>";
                        }
                    }
                }
                ?>
                <form class="d-flex gap-2" action="" method="POST">
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="fa fa-envelope text-muted"></i></span>
                        <input type="email" class="form-control border-start-0" name="user_email" placeholder="Email" required>
                    </div>
                    <button type="submit" name="subscribe" class="btn btn-primary px-4 fw-bold">Subscribe</button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- ========================= MAIN FOOTER ========================= -->
<footer class="section-footer bg-white border-top py-5">
    <div class="container">
        <div class="row g-4">
            
            <!-- 1. BRAND & SOCIALS -->
            <aside class="col-lg-3 col-md-12">
                <article>
                    <a href="index.php" class="d-flex align-items-center mb-3 text-decoration-none">
                        <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                            <i class="fa fa-shopping-bag"></i>
                        </div>
                        <h4 class="m-0 fw-bold text-primary">OlexaMart</h4> 
                    </a>
                    <p class="text-muted small mb-4">
                        Best information about the company <br> 
                        goes here but now lorem ipsum is.
                    </p>
                    <div class="d-flex gap-2">
                        <a class="social-icon" target="_blank" rel="noreferrer" href="https://www.facebook.com/login" title="Facebook Login"><i class="fab fa-facebook-f"></i></a>
                        <a class="social-icon" target="_blank" rel="noreferrer" href="https://x.com/login" title="Twitter Login"><i class="fab fa-twitter"></i></a>
                        <a class="social-icon" target="_blank" rel="noreferrer" href="https://www.instagram.com/accounts/login/" title="Instagram Login"><i class="fab fa-instagram"></i></a>
                        <a class="social-icon" target="_blank" rel="noreferrer" href="https://accounts.google.com/ServiceLogin?service=youtube" title="YouTube Login"><i class="fab fa-youtube"></i></a>
                        <a class="social-icon" target="_blank" rel="noreferrer" href="https://www.linkedin.com/login" title="LinkedIn Login"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </article>
            </aside>
            
            <!-- 2. ABOUT -->
            <aside class="col-6 col-sm-4 col-lg-2">
                <h6 class="fw-bold text-dark mb-3">About</h6>
                <ul class="list-unstyled small text-muted">
                    <li class="mb-2"><a href="about.php" class="text-muted text-decoration-none">About Us</a></li>
                    <li class="mb-2"><a href="shop.php" class="text-muted text-decoration-none">Find store</a></li>
                    <li class="mb-2"><a href="shop.php" class="text-muted text-decoration-none">Categories</a></li>
                    <li class="mb-2"><a href="blog.php" class="text-muted text-decoration-none">Blogs</a></li>
                </ul>
            </aside>
            
            <!-- 3. PARTNERSHIP -->
            <aside class="col-6 col-sm-4 col-lg-2">
                <h6 class="fw-bold text-dark mb-3">Partnership</h6>
                <ul class="list-unstyled small text-muted">
                    <li class="mb-2"><a href="about.php" class="text-muted text-decoration-none">Legal Info</a></li>
                    <li class="mb-2"><a href="register.php" class="text-muted text-decoration-none">Sell with us</a></li>
                    <li class="mb-2"><a href="services.php" class="text-muted text-decoration-none">Services</a></li>
                </ul>
            </aside>

            <!-- 4. INFORMATION -->
            <aside class="col-6 col-sm-4 col-lg-2">
                <h6 class="fw-bold text-dark mb-3">Information</h6>
                <ul class="list-unstyled small text-muted">
                    <li class="mb-2"><a href="contact.php" class="text-muted text-decoration-none">Help Center</a></li>
                    <li class="mb-2"><a href="contact.php" class="text-muted text-decoration-none">Refund Policy</a></li>
                    <li class="mb-2"><a href="services.php" class="text-muted text-decoration-none">Shipping Info</a></li>
                </ul>
            </aside>
            
            <!-- 5. APP STORE LINKS -->
            <aside class="col-lg-3 col-md-12 text-lg-end text-start">
                 <h6 class="fw-bold text-dark mb-3">Get app</h6>
                 <div class="d-flex flex-column gap-2 align-items-lg-end align-items-start">
                    <a href="https://www.apple.com/app-store/" target="_blank" class="d-block">
                        <img src="https://developer.apple.com/app-store/marketing/guidelines/images/badge-download-on-the-app-store.svg" style="height: 42px;">
                    </a>
                    <a href="https://play.google.com/store" target="_blank" class="d-block">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" style="height: 42px;">
                    </a>
                 </div>
            </aside>
            
        </div> 
    </div>
</footer>

<!-- ========================= COPYRIGHT & LANGUAGE ========================= -->
<section class="footer-bottom bg-light py-3 border-top">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Copyright Text -->
            <p class="text-muted mb-0 small">© 2025 OlexaMart. All rights reserved.</p>
            
            <!-- ✅ LANGUAGE DROPDOWN -->
            <div class="dropdown">
                <button class="btn btn-sm btn-light dropdown-toggle fw-bold border" type="button" id="langSwitcher" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://flagcdn.com/w20/us.png" width="20" class="me-1 border"> English
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li>
                        <a class="dropdown-item small" href="#" onclick="changeLanguage('English', 'us')">
                            <img src="https://flagcdn.com/w20/us.png" width="20" class="me-2 border"> English
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item small" href="#" onclick="changeLanguage('Urdu', 'pk')">
                            <img src="https://flagcdn.com/w20/pk.png" width="20" class="me-2 border"> Urdu (اردو)
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item small" href="#" onclick="changeLanguage('Arabic', 'sa')">
                            <img src="https://flagcdn.com/w20/sa.png" width="20" class="me-2 border"> Arabic (العربية)
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- ⭐ JavaScript to update the button flag instantly ⭐ -->
<script>
function changeLanguage(langName, flagCode) {
    // Construct the new Flag URL
    const flagUrl = "https://flagcdn.com/w20/" + flagCode + ".png";
    
    // Update the button content
    document.getElementById('langSwitcher').innerHTML = `
        <img src="${flagUrl}" width="20" class="me-1 border"> ${langName}
    `;
    
    // Optional: You can add logic here to redirect or change page language
    console.log("Language changed to: " + langName);
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>