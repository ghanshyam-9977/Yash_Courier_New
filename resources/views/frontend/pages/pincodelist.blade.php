<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Locations - Find Hub Near You</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-bottom: 40px;
        }

        .header-section {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 40px 0 60px 0;
            margin-bottom: -30px;
        }

        .header-section h1 {
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .header-section p {
            font-size: 1.1rem;
            opacity: 0.95;
        }

        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .search-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 35px 40px;
            margin-bottom: 30px;
        }

        .search-label {
            font-weight: 600;
            font-size: 1.1rem;
            color: #2c3e50;
            margin-bottom: 15px;
            display: block;
        }

        #pincodeInput {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px 20px;
            font-size: 1.1rem;
            transition: all 0.3s;
        }

        #pincodeInput:focus {
            border-color: #2a5298;
            box-shadow: 0 0 0 3px rgba(42, 82, 152, 0.1);
        }

        .search-btn {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            border: none;
            padding: 15px 35px;
            font-weight: 600;
            font-size: 1.05rem;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(42, 82, 152, 0.3);
        }

        .results-header {
            background: white;
            border-radius: 10px;
            padding: 20px 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border-left: 4px solid #2a5298;
        }

        .results-header h5 {
            color: #2c3e50;
            font-weight: 600;
            margin: 0;
        }

        .branch-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 25px;
        }

        .branch-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #e8e8e8;
            position: relative;
            overflow: hidden;
        }

        .branch-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #1e3c72 0%, #2a5298 100%);
        }

        .branch-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .branch-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .branch-name {
            color: #1e3c72;
            font-weight: 700;
            font-size: 1.35rem;
            line-height: 1.3;
            margin: 0;
        }

        .pincode-badge {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.95rem;
            font-weight: 600;
            white-space: nowrap;
            box-shadow: 0 2px 8px rgba(42, 82, 152, 0.3);
        }

        .branch-detail {
            margin: 14px 0;
            color: #555;
            font-size: 1rem;
            display: flex;
            align-items: flex-start;
        }

        .branch-detail i {
            color: #2a5298;
            width: 24px;
            margin-right: 10px;
            margin-top: 3px;
            font-size: 1.1rem;
        }

        .branch-detail strong {
            color: #2c3e50;
            margin-right: 5px;
            min-width: 70px;
            display: inline-block;
        }

        .no-results {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }

        .no-results i {
            color: #cbd5e0;
            margin-bottom: 20px;
        }

        .no-results h4 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .no-results p {
            color: #718096;
        }

        .info-banner {
            background: #e8f4fd;
            border-left: 4px solid #2a5298;
            padding: 15px 20px;
            border-radius: 8px;
            margin-top: 20px;
            color: #1e3c72;
        }

        .info-banner i {
            margin-right: 10px;
        }

        @media (max-width: 768px) {
            .header-section h1 {
                font-size: 1.8rem;
            }

            .branch-grid {
                grid-template-columns: 1fr;
            }

            .search-card {
                padding: 25px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="header-section">
        <div class="main-container">
            <h1><i class="fas fa-map-marked-alt"></i> Service Locations</h1>
            <p>Find our delivery hubs and service centers near you</p>
        </div>
    </div>

    <div class="main-container">
        <div class="search-card">
            <label class="search-label">
                <i class="fas fa-search"></i> Search by Pincode
            </label>
            <form id="searchForm" class="row g-3">
                <div class="col-md-9">
                    <input type="text" class="form-control form-control-lg" id="pincodeInput"
                        placeholder="Enter your area pincode (e.g., 400001)" maxlength="6" pattern="[0-9]*">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary search-btn w-100">
                        <i class="fas fa-search"></i> Find Hubs
                    </button>
                </div>
            </form>
            <div class="info-banner">
                <i class="fas fa-info-circle"></i>
                <strong>Tip:</strong> Enter your pincode to find the nearest service hub for faster deliveries and pickups
            </div>
        </div>

        <div class="results-header">
            <h5 id="resultsCount"><i class="fas fa-spinner fa-spin"></i> Loading service hubs...</h5>
        </div>

        <div class="branch-grid" id="branchList"></div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        const branches = @json($hubs);

        function displayBranches(branchesToShow) {
            const branchList = document.getElementById('branchList');
            const resultsCount = document.getElementById('resultsCount');

            if (branchesToShow.length === 0) {
                branchList.innerHTML = `
                    <div class="no-results" style="grid-column: 1/-1;">
                        <i class="fas fa-map-marker-alt fa-4x"></i>
                        <h4>No Service Hubs Found</h4>
                        <p>We couldn't find any hubs matching your pincode. Please try a different pincode or contact our support team.</p>
                    </div>
                `;
                resultsCount.innerHTML = '<i class="fas fa-exclamation-circle"></i> No hubs found for this pincode';
                return;
            }

            resultsCount.innerHTML = `<i class="fas fa-check-circle" style="color: #22c55e;"></i> Found ${branchesToShow.length} service hub${branchesToShow.length > 1 ? 's' : ''} in your area`;

            branchList.innerHTML = branchesToShow.map(branch => `
                <div class="branch-card">
                    <div class="branch-header">
                        <h3 class="branch-name">
                            <i class="fas fa-warehouse"></i> ${branch.name ?? 'Service Hub'}
                        </h3>
                        <span class="pincode-badge">${branch.pincode}</span>
                    </div>

                    <div class="branch-detail">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <strong>Address:</strong>
                            <span>${branch.address ?? 'Address not available'}${branch.city ? ', ' + branch.city : ''}</span>
                        </div>
                    </div>

                    <div class="branch-detail">
                        <i class="fas fa-phone-alt"></i>
                        <div>
                            <strong>Contact:</strong>
                            <span>${branch.phone ?? 'Contact information not available'}</span>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Display all branches on load
        displayBranches(branches);

        // Only allow numbers in pincode input
        document.getElementById('pincodeInput').addEventListener('keypress', function(e) {
            if (!/[0-9]/.test(e.key)) {
                e.preventDefault();
            }
        });

        // Search functionality
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const pincode = document.getElementById('pincodeInput').value.trim();

            if (pincode === '') {
                displayBranches(branches);
                return;
            }

            const filteredBranches = branches.filter(branch =>
                branch.pincode.toString().includes(pincode)
            );

            displayBranches(filteredBranches);
        });

        // Real-time search as user types
        document.getElementById('pincodeInput').addEventListener('input', function() {
            const pincode = this.value.trim();

            if (pincode === '') {
                displayBranches(branches);
                return;
            }

            const filteredBranches = branches.filter(branch =>
                branch.pincode.toString().includes(pincode)
            );

            displayBranches(filteredBranches);
        });
    </script>
</body>

</html>