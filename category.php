<?php
include 'db.php';  // Make sure to include the database connection
include 'header.php'; 

// 1. Check if an ID was clicked
if (isset($_GET['id'])) {
    $cat_id = intval($_GET['id']); // Clean the ID to be safe

    // 2. Get Category Name (for the title)
    // FIXED: Changed 'id' to 'cat_id' and 'name' to 'cat_title'
    $cat_sql = "SELECT * FROM categories WHERE cat_id = $cat_id";
    $cat_result = $conn->query($cat_sql);
    
    if($cat_row = $cat_result->fetch_assoc()) {
        $page_title = $cat_row['cat_title'];
    } else {
        $page_title = "Category";
    }

    // 3. THE FIX: Search the 'product_cat' column in the products table
    // FIXED: Changed 'category_id' to 'product_cat'
    $sql = "SELECT * FROM products WHERE product_cat = $cat_id";
    $result = $conn->query($sql);

} else {
    echo "<h2 class='text-center mt-5'>No category selected</h2>";
    include 'footer.php';
    exit();
}
?>

<div class="container my-5">
    
    <h2 class="text-center mb-4">
        <?php echo htmlspecialchars($page_title); ?>
    </h2>

    <!-- Added Bootstrap row class for better grid layout -->
    <div class="row">
        <?php
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
        ?>
                <!-- Individual Product Card -->
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <!-- FIXED: product_image -->
                        <!-- Added class 'img-wrap' for external CSS control -->
                        <div class="img-wrap p-3 d-flex align-items-center justify-content-center">
                            <img src="images/<?php echo $row['product_image']; ?>" class="card-img-top" alt="<?php echo $row['product_title']; ?>">
                        </div>
                        
                        <div class="card-body text-center">
                            <!-- FIXED: product_title -->
                            <h5 class="card-title text-truncate"><?php echo $row['product_title']; ?></h5>
                            
                            <!-- FIXED: product_price -->
                            <p class="card-text fw-bold text-primary">$<?php echo $row['product_price']; ?></p>
                            
                            <!-- FIXED: product_id -->
                            <a href="product.php?id=<?php echo $row['product_id']; ?>" class="btn btn-outline-primary btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<div class='col-12'><p class='text-center text-muted'>No products found in this category.</p></div>";
        }
        ?>
    </div>
</div>

<?php include 'footer.php'; ?>