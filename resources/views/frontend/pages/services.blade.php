<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services - Courier Service</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 50px 0;
        }
        .service-card {
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
        }
        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .icon-box {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        .icon-box i {
            font-size: 2rem;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold text-white mb-3">Our Services</h1>
            <p class="lead text-white">Fast, Reliable & Secure Courier Solutions</p>
        </div>

        <div class="row g-4">
            <!-- Express Delivery -->
            <div class="col-md-6 col-lg-4">
                <div class="card service-card">
                    <div class="card-body text-center p-4">
                        <div class="icon-box">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <h3 class="card-title mb-3">Express Delivery</h3>
                        <p class="card-text text-muted">Same-day and next-day delivery for urgent shipments with real-time tracking</p>
                    </div>
                </div>
            </div>

            <!-- Standard Shipping -->
            <div class="col-md-6 col-lg-4">
                <div class="card service-card">
                    <div class="card-body text-center p-4">
                        <div class="icon-box">
                            <i class="fas fa-box"></i>
                        </div>
                        <h3 class="card-title mb-3">Standard Shipping</h3>
                        <p class="card-text text-muted">Reliable and affordable delivery for all your regular shipping needs nationwide</p>
                    </div>
                </div>
            </div>

            <!-- Scheduled Delivery -->
            <div class="col-md-6 col-lg-4">
                <div class="card service-card">
                    <div class="card-body text-center p-4">
                        <div class="icon-box">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3 class="card-title mb-3">Scheduled Delivery</h3>
                        <p class="card-text text-muted">Choose your preferred delivery date and time slot with flexible scheduling</p>
                    </div>
                </div>
            </div>

            <!-- Secure Transport -->
            <div class="col-md-6 col-lg-4">
                <div class="card service-card">
                    <div class="card-body text-center p-4">
                        <div class="icon-box">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="card-title mb-3">Secure Transport</h3>
                        <p class="card-text text-muted">Extra protection for valuable items with insurance and special handling included</p>
                    </div>
                </div>
            </div>

            <!-- International Shipping -->
            <div class="col-md-6 col-lg-4">
                <div class="card service-card">
                    <div class="card-body text-center p-4">
                        <div class="icon-box">
                            <i class="fas fa-globe"></i>
                        </div>
                        <h3 class="card-title mb-3">International Shipping</h3>
                        <p class="card-text text-muted">Global delivery to over 200 countries with customs clearance assistance</p>
                    </div>
                </div>
            </div>

            <!-- Bulk Orders -->
            <div class="col-md-6 col-lg-4">
                <div class="card service-card">
                    <div class="card-body text-center p-4">
                        <div class="icon-box">
                            <i class="fas fa-truck-loading"></i>
                        </div>
                        <h3 class="card-title mb-3">Bulk Orders</h3>
                        <p class="card-text text-muted">Special rates for businesses and high-volume shipments with dedicated support</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>