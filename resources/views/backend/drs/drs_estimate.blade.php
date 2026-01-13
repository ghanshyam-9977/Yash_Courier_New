<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dynamic DRS ENTRY - Print Out</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            background-color: #f5f5f5;
            color: #000;
            padding: 20px;
        }

        .search-container {
            max-width: 210mm;
            margin: 0 auto 20px;
            background: white;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .search-box {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .search-box input {
            flex: 1;
            padding: 10px;
            border: 2px solid #333;
            font-size: 14px;
            font-family: 'Courier New', monospace;
        }

        .search-box button {
            padding: 10px 25px;
            background: #000;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
            border-radius: 2px;
        }

        .search-box button:hover {
            background: #333;
        }

        .error-message {
            color: #d32f2f;
            padding: 10px;
            background: #ffebee;
            border-radius: 2px;
            display: none;
        }

        .success-message {
            color: #2e7d32;
            padding: 10px;
            background: #f1f8e9;
            border-radius: 2px;
            display: none;
        }

        .loading {
            text-align: center;
            display: none;
            color: #666;
        }

        .a4-container {
            max-width: 210mm;
            margin: 0 auto;
            background: white;
            padding: 15mm;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: none;
        }

        .a4-container.active {
            display: block;
        }

        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 12px;
            letter-spacing: 1px;
        }

        .header-row-1 {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1fr;
            gap: 10px;
            margin-bottom: 8px;
        }

        .header-row-2 {
            display: grid;
            grid-template-columns: 2fr 2fr 1fr;
            gap: 10px;
            margin-bottom: 15px;
        }

        .header-field {
            display: flex;
            flex-direction: column;
        }

        .header-field label {
            font-weight: bold;
            margin-bottom: 2px;
            font-size: 10px;
        }

        .header-field input {
            border: 1px solid #000;
            padding: 4px;
            font-family: 'Courier New', monospace;
            font-size: 11px;
            height: 20px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 10px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 5px 4px;
            text-align: left;
            height: 18px;
        }

        .data-table th {
            background: #fff;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
        }

        .data-table tbody tr {
            height: 22px;
        }

        .col-sno {
            width: 6%;
            text-align: center;
        }

        .col-tracking {
            width: 18%;
        }

        .col-weight {
            width: 10%;
            text-align: right;
        }

        .col-pcs {
            width: 7%;
            text-align: center;
        }

        .col-signature {
            width: 17%;
            text-align: center;
        }

        .footer-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 20px;
            font-size: 10px;
        }

        .footer-field {
            display: flex;
            flex-direction: column;
        }

        .footer-field label {
            font-weight: bold;
            margin-bottom: 4px;
        }

        .footer-field input {
            border: none;
            border-bottom: 1px solid #000;
            padding: 4px 0;
            font-family: 'Courier New', monospace;
            font-size: 11px;
            height: 22px;
        }

        .signature-box {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            margin-top: 30px;
            padding-right: 20px;
        }

        .signature-line {
            width: 100px;
            border-bottom: 1px solid #000;
            margin-bottom: 2px;
        }

        .signature-label {
            font-size: 9px;
            text-align: center;
            margin-top: 2px;
        }

        .button-group {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 25px;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 2px;
            border: none;
        }

        .btn-print {
            background: #000;
            color: white;
        }

        .btn-print:hover {
            background: #333;
        }

        .btn-reset {
            background: #666;
            color: white;
        }

        .btn-reset:hover {
            background: #888;
        }

        @media print {
            body {
                padding: 0;
                margin: 0;
                background: white;
            }

            .search-container,
            .button-group {
                display: none !important;
            }

            .a4-container {
                max-width: 100%;
                height: auto;
                margin: 0;
                padding: 15mm;
                border: none;
                box-shadow: none;
                page-break-after: always;
            }
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="search-container">
        <div class="search-box">
            <input type="text" id="drsInput" placeholder="DRS Number खोजें (e.g., DRS-20240156)" autocomplete="off" />
            <button onclick="searchDRS()">🔍 Search</button>
        </div>
        <div class="error-message" id="errorMsg"></div>
        <div class="success-message" id="successMsg"></div>
        <div class="loading" id="loading">Loading...</div>
    </div>

    <div class="a4-container" id="drsContainer">
        <div class="title">DRS ENTRY (Print Out)</div>

        <div class="header-row-1">
            <div class="header-field">
                <label>DRS No</label>
                <input type="text" id="drsNo"  />
            </div>
            <div class="header-field">
                <label>Date</label>
                <input type="text" id="drsDate"  />
            </div>
            <div class="header-field">
                <label>Time</label>
                <input type="text" id="drsTime"  />
            </div>
            <div class="header-field">
                <label>Area Name</label>
                <input type="text" id="areaName"  />
            </div>
        </div>

        <div class="header-row-2">
            <div class="header-field">
                <label>Delivery Boy Name</label>
                <input type="text" id="deliveryBoyName"  />
            </div>
            <div class="header-field">
                <label>Contact Person</label>
                <input type="text" id="contactPerson"  />
            </div>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th class="col-sno">S.No</th>
                    <th class="col-tracking">Tracking No</th>
                    <th class="col-weight">Weight (kg)</th>
                    <th class="col-pcs">Pcs</th>
                    <th class="col-signature">Signature</th>
                </tr>
            </thead>
            <tbody id="shipmentTableBody">
                <tr>
                    <td colspan="7" class="no-data">No shipments to display</td>
                </tr>
            </tbody>
        </table>

        <div class="footer-section">
            <div class="footer-field">
                <label>Total Consignments</label>
                <input type="text" id="totalConsignments"  />
            </div>
            <div class="footer-field">
                <label>Total Weight</label>
                <input type="text" id="totalWeight"  />
            </div>
        </div>

        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-label">Signature of Delivery Boy</div>
        </div>

        <div class="button-group">
            <button class="btn btn-print" onclick="window.print()">🖨️ Print DRS Entry</button>
            <button class="btn btn-reset" onclick="resetForm()">🔄 Reset</button>
        </div>
    </div>

    <script>
        const errorMsg = document.getElementById("errorMsg");
        const successMsg = document.getElementById("successMsg");
        const loading = document.getElementById("loading");

        function showError(message) {
            errorMsg.textContent = message;
            errorMsg.style.display = "block";
            successMsg.style.display = "none";
        }

        function showSuccess(message) {
            successMsg.textContent = message;
            successMsg.style.display = "block";
            errorMsg.style.display = "none";
        }

        function hideMessages() {
            errorMsg.style.display = "none";
            successMsg.style.display = "none";
        }

        function searchDRS() {
            const drsNumber = document.getElementById("drsInput").value.trim().toUpperCase();

            if (!drsNumber) {
                showError("कृपया DRS नंबर दर्ज करें");
                return;
            }



            loading.style.display = "block";
            hideMessages();

            fetch(`${drsSearchUrl}?drs_no=${encodeURIComponent(drsNumber)}`, {
                    method: "GET",
                    headers: {
                        Accept: "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                })
                .then((response) => {
                    // console.log("Response Status:", response.data);
                    loading.style.display = "none";
                    if (!response.ok) {
                        if (response.status === 404) {
                            throw new Error("DRS नंबर नहीं मिला");
                        }
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log("Response Data:", data);
                    if (data.error) {
                        showError(data.error);
                        return;
                    }

                    populateForm(data);
                    document.getElementById("drsContainer").classList.add("active");
                    showSuccess("DRS डेटा सफलतापूर्वक लोड हुआ");
                })
                .catch((error) => {
                    console.error("Error:", error);
                    showError("Database error: " + error.message);
                });
        }

        function populateForm(data) {
            document.getElementById("drsNo").value = data.drs_no || "";
            document.getElementById("drsDate").value = data.drs_date || "";
            document.getElementById("drsTime").value = data.drs_time || "";
            document.getElementById("areaName").value = data.area_name || "";
            document.getElementById("deliveryBoyName").value = data.delivery_boy_name || "";
            document.getElementById("contactPerson").value = data.contact_person || "";

            const tbody = document.getElementById("shipmentTableBody");
            tbody.innerHTML = "";

            let totalWeight = 0;
            let shipments = data.shipments || [];

            if (shipments.length === 0) {
                const row = document.createElement("tr");
                row.innerHTML = '<td colspan="7" class="no-data">कोई शिपमेंट नहीं</td>';
                tbody.appendChild(row);
                document.getElementById("totalConsignments").value = "0";
                document.getElementById("totalWeight").value = "0.00 kg";
                return;
            }

            shipments.forEach((shipment, index) => {
                totalWeight += parseFloat(shipment.weight) || 0;
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td class="col-sno">${index + 1}</td>
                    <td class="col-tracking">${shipment.tracking || ""}</td>
                    <td class="col-weight">${parseFloat(shipment.weight || 0).toFixed(2)}</td>
                    <td class="col-pcs">${shipment.pcs || ""}</td>
                    <td class="col-signature"></td>
                `;
                tbody.appendChild(row);
            });

            document.getElementById("totalConsignments").value = shipments.length;
            document.getElementById("totalWeight").value = totalWeight.toFixed(2) + " kg";
        }

        function resetForm() {
            document.getElementById("drsInput").value = "";
            document.getElementById("drsContainer").classList.remove("active");
            hideMessages();
            document.getElementById("drsInput").focus();
        }

        // Enter key support
        document.getElementById("drsInput").addEventListener("keypress", (e) => {
            if (e.key === "Enter") {
                searchDRS();
            }
        });

        // Focus on input on page load
        window.addEventListener("load", () => {
            document.getElementById("drsInput").focus();
        });
    </script>
    <script>
        const drsSearchUrl = "{{ route('drs.search') }}";
    </script>

</body>

</html>
