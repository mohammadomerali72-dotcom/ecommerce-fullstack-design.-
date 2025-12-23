<?php include 'header.php'; ?>

<section class="bg-light py-5">
    <div class="container">
        <div class="row">
            
            <!-- Contact Form -->
            <div class="col-lg-6 mx-auto">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h3 class="card-title text-center mb-4">Contact Support / Seller Chat</h3>
                        <!-- Replaced text-muted with text-dark for better visibility -->
                        <p class="text-center text-dark mb-4">Start a conversation with us regarding your orders or inquiries.</p>
                        
                        <form onsubmit="event.preventDefault(); alert('Message sent! We will reply via email.');">
                            <div class="mb-3">
                                <label class="form-label">Your Name</label>
                                <input type="text" class="form-control" placeholder="Type your name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" placeholder="name@example.com" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Subject</label>
                                <select class="form-select">
                                    <option>General Inquiry</option>
                                    <option>Order Issue</option>
                                    <option>Chat with Supplier</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Message</label>
                                <textarea class="form-control" rows="5" placeholder="Type your message here..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Contact Info Sidebar -->
            <div class="col-lg-4 mx-auto mt-4 mt-lg-0">
                <div class="card shadow-sm border-0 p-4">
                    <h4 class="mb-3">Quick Help</h4>
                    <!-- Replaced text-muted with text-dark -->
                    <p class="text-dark">Need instant help? Check our FAQs or email us directly.</p>
                    
                    <ul class="list-unstyled mt-3">
                        <li class="mb-3 d-flex align-items-center">
                            <div class="bg-light rounded-circle p-2 me-3"><i class="fa fa-map-marker-alt text-primary"></i></div>
                            <span>Karachi, Pakistan</span>
                        </li>
                        <li class="mb-3 d-flex align-items-center">
                            <div class="bg-light rounded-circle p-2 me-3"><i class="fa fa-phone text-primary"></i></div>
                            <span>+92 300 1234567</span>
                        </li>
                        <li class="mb-3 d-flex align-items-center">
                            <div class="bg-light rounded-circle p-2 me-3"><i class="fa fa-envelope text-primary"></i></div>
                            <span>support@olexamart.com</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>

<?php include 'footer.php'; ?>