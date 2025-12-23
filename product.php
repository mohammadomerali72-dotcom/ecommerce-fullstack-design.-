<?php 
// 1. Connection and Session Setup
session_start();
include_once 'db.php'; 
include_once 'functions.php';
include_once 'header.php'; 

// Standardize database connection variable
if (!isset($conn)) {
    $conn = isset($db) ? $db : (isset($con) ? $con : null);
}

// 2. HANDLE WISHLIST (Save for Later)
if(isset($_GET['add_wishlist'])){
    // Using user_email session for security
    if(!isset($_SESSION['user_email'])){
        echo "<script>alert('Please login first to save items!'); window.open('login.php','_self')</script>";
    } else {
        $email = $_SESSION['user_email'];
        // Fetch user_id from email
        $u_info = $conn->query("SELECT user_id FROM users WHERE user_email='$email'")->fetch_assoc();
        $u_id = $u_info['user_id'];
        $p_id = (int)$_GET['add_wishlist'];

        $check = $conn->query("SELECT * FROM wishlist WHERE user_id='$u_id' AND product_id='$p_id'");
        if($check->num_rows > 0){
            echo "<script>alert('Already in your wishlist!'); window.open('product.php?id=$p_id','_self')</script>";
        } else {
            $conn->query("INSERT INTO wishlist (user_id, product_id) VALUES ('$u_id', '$p_id')");
            echo "<script>alert('Saved to wishlist!'); window.open('product.php?id=$p_id','_self')</script>";
        }
    }
}

// 3. HANDLE REVIEW SUBMISSION
if(isset($_POST['submit_review'])){
    $u_name = mysqli_real_escape_string($conn, $_POST['user_name']);
    $u_rating = (int)$_POST['rating'];
    $u_review = mysqli_real_escape_string($conn, $_POST['review_text']);
    $p_id = (int)$_POST['product_id'];

    if(!empty($u_name) && !empty($u_review)){
        $insert_sql = "INSERT INTO product_reviews (product_id, user_name, rating, review_text, review_date) 
                       VALUES ('$p_id', '$u_name', '$u_rating', '$u_review', NOW())";
        if($conn->query($insert_sql)){
            echo "<script>alert('Review submitted!'); window.open('product.php?id=$p_id','_self')</script>";
        }
    }
}

// 4. GET MAIN PRODUCT DATA
if(isset($_GET['id'])){
    $product_id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "SELECT * FROM products JOIN categories ON products.product_cat = categories.cat_id WHERE product_id = '$product_id'";
    $result = $conn->query($sql);
    
    if($result && $result->num_rows > 0){
        $row = $result->fetch_assoc();
        
        // Dynamic Rating Logic
        $rating_query = "SELECT AVG(rating) as avg_rating, COUNT(review_id) as total_revs FROM product_reviews WHERE product_id = '$product_id'";
        $rating_res = $conn->query($rating_query);
        $rating_data = $rating_res->fetch_assoc();
        
        $average = round($rating_data['avg_rating'], 1); 
        $total_reviews = $rating_data['total_revs'];
    } else {
        echo "<script>window.open('index.php','_self')</script>"; exit();
    }
} else {
    echo "<script>window.open('index.php','_self')</script>"; exit();
}
?>

<style>
/* 🖼️ SPECIFIC FIX FOR IMAGE OVERLAP AND ALIGNMENT */
.img-big-wrap {
    height: 450px !important; width: 100% !important;
    background-color: #fff; border: 1px solid #eee; border-radius: 8px;
    display: flex !important; align-items: center; justify-content: center;
    overflow: hidden !important; /* Forces large images to stay inside */
    padding: 20px;
}
.img-big-wrap img { 
    max-height: 100% !important; max-width: 100% !important;
    object-fit: contain !important; /* No cropping, no stretching */
}
.item-thumb { width: 65px; height: 65px; display: inline-flex; align-items: center; justify-content: center; background: #fff; margin-right: 5px; }
.item-thumb img { max-width: 100%; max-height: 100%; object-fit: contain; }

/* Mobile Column Stacking Fix */
@media (max-width: 991px) {
    .img-big-wrap { height: 320px !important; }
    .ps-lg-3 { padding-left: 0 !important; margin-top: 15px; }
}
</style>

<!-- BREADCRUMB -->
<section class="bg-light py-3 border-bottom">
    <div class="container">
        <ol class="breadcrumb m-0 small">
            <li class="breadcrumb-item"><a href="index.php" class="text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="shop.php" class="text-muted">Shop</a></li>
            <li class="breadcrumb-item active"><?php echo $row['product_title']; ?></li>
        </ol>
    </div>
</section>

<!-- ========================= TOP SECTION ========================= -->
<section class="padding-y bg-white">
<div class="container">
    <div class="row">
        
        <!-- 1. IMAGE GALLERY (Responsive columns prevent overlap) -->
        <aside class="col-12 col-lg-4 mb-4">
            <div class="gallery-wrap"> 
                <div class="img-big-wrap shadow-sm">
                    <img src="images/<?php echo $row['product_image']; ?>">
                </div> 
                <div class="thumbs-wrap mt-2 d-flex">
                    <div class="item-thumb border rounded"><img src="images/<?php echo $row['product_image']; ?>"></div>
                    <div class="item-thumb border rounded"><img src="images/<?php echo $row['product_image']; ?>"></div>
                    <div class="item-thumb border rounded"><img src="images/<?php echo $row['product_image']; ?>"></div>
                </div>
            </div> 
        </aside>

        <!-- 2. PRODUCT INFO -->
        <main class="col-12 col-lg-5 mb-4">
            <div class="ps-lg-3">
                <span class="text-success small fw-bold"><i class="fa fa-check"></i> In stock</span>
                <h4 class="fw-bold mb-2"><?php echo $row['product_title']; ?></h4>
                
                <div class="mb-3 d-flex align-items-center">
                    <div class="text-warning me-2">
                        <?php for($i=1; $i<=5; $i++) echo ($i <= $average) ? '<i class="fa fa-star"></i>' : '<i class="fa-regular fa-star"></i>'; ?>
                    </div>
                    <span class="text-warning fw-bold small"><?php echo $average > 0 ? $average : '0.0'; ?></span>
                    <span class="text-muted ms-2 small">• <?php echo $total_reviews; ?> reviews • 154 sold</span>
                </div>

                <div class="price-range-block mb-4 p-3 rounded" style="background:#FFF0DF;">
                    <div class="row text-center">
                        <div class="col border-end">
                            <span class="d-block fw-bold text-danger h5 mb-0">$<?php echo $row['product_price']; ?></span>
                            <small class="text-muted">1-50 pcs</small>
                        </div>
                        <div class="col border-end">
                            <span class="d-block fw-bold text-dark h5 mb-0">$<?php echo number_format($row['product_price']*0.9, 0); ?></span>
                            <small class="text-muted">50-200 pcs</small>
                        </div>
                        <div class="col">
                            <span class="d-block fw-bold text-dark h5 mb-0">$<?php echo number_format($row['product_price']*0.8, 0); ?></span>
                            <small class="text-muted">200+ pcs</small>
                        </div>
                    </div>
                </div>

                <table class="attr-table w-100">
                    <tr><td class="text-muted py-1" width="100">Price:</td> <td>Negotiable</td></tr>
                    <tr><td class="text-muted py-1">Type:</td> <td><?php echo $row['product_type']; ?></td></tr>
                    <tr><td class="text-muted py-1">Material:</td> <td><?php echo $row['product_material']; ?></td></tr>
                    <tr><td class="text-muted py-1">Design:</td> <td><?php echo $row['product_design']; ?></td></tr>
                </table>
                <hr>
                <table class="attr-table w-100">
                    <tr><td class="text-muted py-1" width="120">Protection:</td> <td>Refund Policy</td></tr>
                    <tr><td class="text-muted py-1">Warranty:</td> <td><?php echo $row['product_warranty']; ?></td></tr>
                </table>
            </div> 
        </main>

        <!-- 3. SUPPLIER CARD -->
        <aside class="col-12 col-lg-3">
            <div class="card shadow-sm border rounded p-3 bg-white">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary text-white rounded p-2 me-2 fw-bold d-flex align-items-center justify-content-center" style="width:40px; height:40px;">G</div>
                    <div><h6 class="mb-0 fw-bold">Guanjoi Trading</h6><small class="text-muted">Verified Supplier</small></div>
                </div>
                <hr>
                <div class="small mb-4">
                    <p class="mb-2"><img src="https://flagcdn.com/w20/pk.png" class="me-2" width="20"> Pakistan, Lahore</p>
                    <p class="mb-2 text-muted"><i class="fa fa-shield-halved me-2"></i> Buyer Protection</p>
                    <p class="mb-0 text-muted"><i class="fa fa-globe me-2"></i> Worldwide shipping</p>
                </div>

                <!-- ACTIONS -->
                <div class="d-grid gap-2">
                    <a href="cart.php?add_cart=<?php echo $row['product_id']; ?>" class="btn btn-primary fw-bold py-2"><i class="fa fa-shopping-cart me-2"></i> Add to cart</a>
                    <a href="checkout.php?buy_now=<?php echo $row['product_id']; ?>" class="btn btn-outline-primary fw-bold py-2">Order now</a>
                </div>
                <div class="text-center mt-3">
                    <a href="?id=<?php echo $product_id; ?>&add_wishlist=<?php echo $product_id; ?>" class="text-muted small text-decoration-none"><i class="fa fa-heart text-danger me-1"></i> Save for later</a>
                </div>
            </div>
        </aside>

    </div> 
</div> 
</section>

<!-- ========================= TABS: DESCRIPTION & REVIEWS ========================= -->
<section class="padding-y bg-light border-top">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <ul class="nav nav-tabs card-header-tabs" role="tablist">
                            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab_desc">Description</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab_rev">Reviews (<?php echo $total_reviews; ?>)</a></li>
                        </ul>
                    </div>
                    <div class="card-body tab-content">
                        <div class="tab-pane fade show active" id="tab_desc">
                            <p><?php echo $row['product_desc']; ?></p>
                            <table class="table table-bordered w-50 small mt-3">
                                <tr class="bg-light"><td>Model</td><td>#<?php echo $row['product_id']; ?>PRD</td></tr>
                                <tr><td>Category</td><td><?php echo $row['cat_title']; ?></td></tr>
                            </table>
                        </div>

                        <div class="tab-pane fade" id="tab_rev">
                            <div class="row">
                                <div class="col-md-7 border-end">
                                    <?php
                                    $rev_sql = "SELECT * FROM product_reviews WHERE product_id = '$product_id' ORDER BY review_id DESC";
                                    $rev_res = $conn->query($rev_sql);
                                    if($rev_res && $rev_res->num_rows > 0){
                                        while($rev = $rev_res->fetch_assoc()){
                                    ?>
                                        <div class="mb-3 border-bottom pb-3 small">
                                            <div class="text-warning mb-1"><?php for($j=1; $j<=5; $j++) echo ($j <= $rev['rating']) ? '<i class="fa fa-star"></i>' : '<i class="fa-regular fa-star"></i>'; ?></div>
                                            <div class="d-flex justify-content-between"><strong class="text-dark"><?php echo $rev['user_name']; ?></strong><span class="text-muted"><?php echo date('M d, Y', strtotime($rev['review_date'])); ?></span></div>
                                            <p class="text-muted mt-1 mb-0"><?php echo $rev['review_text']; ?></p>
                                        </div>
                                    <?php } } else { echo "<p class='text-muted small'>No reviews yet.</p>"; } ?>
                                </div>
                                <div class="col-md-5 ps-lg-4 mt-3 mt-md-0">
                                    <h6 class="fw-bold mb-3">Write a Review</h6>
                                    <form method="POST" class="bg-white p-3 border rounded">
                                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                        <div class="mb-2"><label class="small fw-bold">Rating</label><div class="rating-input"><input type="radio" name="rating" value="5" id="s5"><label for="s5"></label><input type="radio" name="rating" value="4" id="s4"><label for="s4"></label><input type="radio" name="rating" value="3" id="s3"><label for="s3"></label><input type="radio" name="rating" value="2" id="s2"><label for="s2"></label><input type="radio" name="rating" value="1" id="s1" checked><label for="s1"></label></div></div>
                                        <div class="mb-2"><input type="text" name="user_name" class="form-control form-control-sm shadow-none" placeholder="Your Name" required></div>
                                        <div class="mb-3"><textarea name="review_text" class="form-control form-control-sm shadow-none" rows="3" placeholder="Write review..." required></textarea></div>
                                        <button type="submit" name="submit_review" class="btn btn-primary btn-sm w-100 fw-bold">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- RELATED PRODUCTS -->
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="fw-bold mb-4">Related products</h6>
                        <div class="row g-2">
                            <?php
                            $cat = $row['product_cat'];
                            $rel_res = $conn->query("SELECT * FROM products WHERE product_cat = '$cat' AND product_id != '$product_id' LIMIT 4");
                            while($rel = @$rel_res->fetch_assoc()){
                            ?>
                            <div class="col-6 col-md-3 text-center mb-3">
                                <a href="product.php?id=<?php echo $rel['product_id']; ?>" class="d-block border rounded p-2 mb-2 bg-white" style="height:120px;"><img src="images/<?php echo $rel['product_image']; ?>" style="max-height:100%; max-width:100%; object-fit:contain;"></a>
                                <p class="text-truncate small mb-1 text-dark"><?php echo $rel['product_title']; ?></p>
                                <strong class="small text-muted">$<?php echo $rel['product_price']; ?></strong>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR SUGGESTIONS -->
            <aside class="col-lg-3 mt-4 mt-lg-0">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">You may like</h6>
                        <?php
                        $rec_res = $conn->query("SELECT * FROM products WHERE product_id != '$product_id' ORDER BY RAND() LIMIT 5");
                        while($rec = $rec_res->fetch_assoc()){
                        ?>
                        <article class="d-flex mb-3">
                            <a href="product.php?id=<?php echo $rec['product_id']; ?>" class="me-2 border rounded p-1 bg-white" style="width:70px; height:70px; display:flex; align-items:center;"><img src="images/<?php echo $rec['product_image']; ?>" style="width:100%; height:100%; object-fit:contain;"></a>
                            <div class="info"><a href="product.php?id=<?php echo $rec['product_id']; ?>" class="text-dark d-block small text-truncate" style="max-width:130px; font-weight:500;"><?php echo $rec['product_title']; ?></a><strong class="small text-muted">$<?php echo $rec['product_price']; ?></strong></div>
                        </article>
                        <?php } ?>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>