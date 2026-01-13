<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Branches - Service Network</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .top-bar {
            background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
            color: white;
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .top-bar h1 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }

        .top-bar .breadcrumb {
            background: transparent;
            margin: 0;
            padding: 0;
        }

        .top-bar .breadcrumb-item {
            color: rgba(255, 255, 255, 0.7);
        }

        .top-bar .breadcrumb-item.active {
            color: white;
        }

        .top-bar .breadcrumb-item + .breadcrumb-item::before {
            color: rgba(255, 255, 255, 0.5);
        }

        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 15px;
        }

        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        }

        .filter-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .filter-group {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-input {
            flex: 1;
            min-width: 250px;
        }

        .filter-input input,
        .filter-input select {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 18px;
            font-size: 1rem;
            width: 100%;
            transition: all 0.3s;
        }

        .filter-input input:focus,
        .filter-input select:focus {
            border-color: #2c5364;
            box-shadow: 0 0 0 3px rgba(44, 83, 100, 0.1);
            outline: none;
        }

        .stats-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .stat-card {
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .table-header {
            background: linear-gradient(135deg, #0f2027 0%, #2c5364 100%);
            color: white;
            padding: 20px 25px;
            font-size: 1.3rem;
            font-weight: 600;
        }

        .table {
            margin: 0;
        }

        .table thead th {
            background: #f8f9fa;
            color: #2c3e50;
            font-weight: 600;
            border: none;
            padding: 18px 20px;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            border-bottom: 1px solid #e8e8e8;
            transition: all 0.2s;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
        }

        .table tbody td {
            padding: 20px;
            vertical-align: middle;
            color: #555;
        }

        .branch-name-cell {
            font-weight: 600;
            color: #2c5364;
            font-size: 1.1rem;
        }

        .branch-name-cell i {
            color: #667eea;
            margin-right: 8px;
        }

        .pincode-pill {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
            font-size: 0.9rem;
        }

        .city-badge {
            background: #e8f4fd;
            color: #2c5364;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 500;
            display: inline-block;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .contact-info span {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .contact-info i {
            color: #667eea;
            width: 16px;
        }

        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .no-data i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #ddd;
        }

        .export-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .export-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            color: white;
        }

        @media (max-width: 768px) {
            .table-container {
                overflow-x: auto;
            }

            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }

            .filter-group {
                flex-direction: column;
            }

            .filter-input {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="top-bar">
        <div class="main-container">
            <h1><i class="fas fa-network-wired"></i> Our Service Network</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" style="color: rgba(255,255,255,0.7);">Home</a></li>
                    <li class="breadcrumb-item active">All Branches</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="main-container">
        <div class="stats-section">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number" id="totalBranches">0</div>
                    <div class="stat-label">Total Branches</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="totalCities">0</div>
                    <div class="stat-label">Cities Covered</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="totalPincodes">0</div>
                    <div class="stat-label">Pincodes Served</div>
                </div>
            </div>
        </div>

        <div class="filter-section">
            <div class="filter-title"><i class="fas fa-filter"></i> Filter Branches</div>
            <div class="filter-group">
                <div class="filter-input">
                    <input type="text" id="searchInput" placeholder="Search by name, address, or phone..." 
                           class="form-control">
                </div>
                <div class="filter-input">
                    <select id="cityFilter" class="form-select">
                        <option value="">All Cities</option>
                    </select>
                </div>
                <div class="filter-input">
                    <input type="text" id="pincodeFilter" placeholder="Filter by pincode..." 
                           class="form-control" maxlength="6">
                </div>
                <button class="btn export-btn" onclick="exportToCSV()">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
        </div>

        <div class="table-container">
            <div class="table-header">
                <i class="fas fa-list"></i> Branch Directory (<span id="displayCount">0</span> branches)
            </div>
            <div class="table-responsive">
                <table class="table" id="branchTable">
                    <thead>
                        <tr>
                            <th>Branch Name</th>
                            <th>Location</th>
                            <th>Pincode</th>
                            <th>Contact Details</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        const branches = @json($hubs);
        let filteredBranches = [...branches];

        function calculateStats() {
            const cities = new Set(branches.map(b => b.city).filter(Boolean));
            const pincodes = new Set(branches.map(b => b.pincode).filter(Boolean));

            document.getElementById('totalBranches').textContent = branches.length;
            document.getElementById('totalCities').textContent = cities.size;
            document.getElementById('totalPincodes').textContent = pincodes.size;
        }

        function populateCityFilter() {
            const cities = [...new Set(branches.map(b => b.city).filter(Boolean))].sort();
            const cityFilter = document.getElementById('cityFilter');
            
            cities.forEach(city => {
                const option = document.createElement('option');
                option.value = city;
                option.textContent = city;
                cityFilter.appendChild(option);
            });
        }

        function displayBranches(branchesToShow) {
            const tableBody = document.getElementById('tableBody');
            const displayCount = document.getElementById('displayCount');
            
            displayCount.textContent = branchesToShow.length;

            if (branchesToShow.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="4">
                            <div class="no-data">
                                <i class="fas fa-search"></i>
                                <h4>No Branches Found</h4>
                                <p>Try adjusting your filters</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }

            tableBody.innerHTML = branchesToShow.map(branch => `
                <tr>
                    <td class="branch-name-cell">
                        <i class="fas fa-building"></i>
                        ${branch.name ?? 'Service Hub'}
                    </td>
                    <td>
                        <div>${branch.address ?? 'N/A'}</div>
                        ${branch.city ? `<span class="city-badge mt-2">${branch.city}</span>` : ''}
                    </td>
                    <td>
                        <span class="pincode-pill">${branch.pincode}</span>
                    </td>
                    <td>
                        <div class="contact-info">
                            <span>
                                <i class="fas fa-phone"></i>
                                ${branch.phone ?? 'N/A'}
                            </span>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function applyFilters() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const cityFilter = document.getElementById('cityFilter').value;
            const pincodeFilter = document.getElementById('pincodeFilter').value;

            filteredBranches = branches.filter(branch => {
                const matchesSearch = !searchTerm || 
                    (branch.name?.toLowerCase().includes(searchTerm)) ||
                    (branch.address?.toLowerCase().includes(searchTerm)) ||
                    (branch.phone?.toLowerCase().includes(searchTerm));

                const matchesCity = !cityFilter || branch.city === cityFilter;
                const matchesPincode = !pincodeFilter || branch.pincode?.toString().includes(pincodeFilter);

                return matchesSearch && matchesCity && matchesPincode;
            });

            displayBranches(filteredBranches);
        }

        function exportToCSV() {
            let csv = 'Branch Name,Address,City,Pincode,Phone\n';
            
            filteredBranches.forEach(branch => {
                csv += `"${branch.name ?? 'Service Hub'}","${branch.address ?? ''}","${branch.city ?? ''}","${branch.pincode}","${branch.phone ?? ''}"\n`;
            });

            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'branches_list.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }

        // Event listeners
        document.getElementById('searchInput').addEventListener('input', applyFilters);
        document.getElementById('cityFilter').addEventListener('change', applyFilters);
        document.getElementById('pincodeFilter').addEventListener('input', applyFilters);

        // Only allow numbers in pincode
        document.getElementById('pincodeFilter').addEventListener('keypress', function(e) {
            if (!/[0-9]/.test(e.key)) {
                e.preventDefault();
            }
        });

        // Initialize
        calculateStats();
        populateCityFilter();
        displayBranches(branches);
    </script>
</body>

</html>