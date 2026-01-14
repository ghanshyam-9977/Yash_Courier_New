<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ledger Print</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Courier New", monospace;
            background: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border: 2px solid #333;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
        }

        .header p {
            font-size: 12px;
            margin-top: 4px;
        }

        .branch-section {
            margin-bottom: 20px;
            padding: 15px;
            background: #f9f9f9;
            border: 1px solid #ddd;
        }

        .branch-select {
            width: 100%;
            padding: 8px;
            border: 1px solid #333;
            font-family: "Courier New", monospace;
            font-size: 13px;
            margin-bottom: 15px;
        }

        .input-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 15px;
        }

        .input-field label {
            font-size: 12px;
            font-weight: bold;
        }

        .input-field input {
            padding: 7px;
            border: 1px solid #333;
            font-size: 12px;
            font-family: "Courier New", monospace;
            width: 100%;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 10px;
            color: #666;
        }

        .error {
            display: none;
            background: #fee;
            color: #c33;
            padding: 10px;
            border: 1px solid #f99;
            margin-bottom: 10px;
        }

        .ledger-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-top: 10px;
        }

        .ledger-table th,
        .ledger-table td {
            border: 1px solid #333;
            padding: 6px;
        }

        .ledger-table th {
            background: #f0f0f0;
            text-align: left;
        }

        .ledger-table td {
            text-align: right;
        }

        .ledger-table td:nth-child(1),
        .ledger-table td:nth-child(2) {
            text-align: left;
        }

        .total-row {
            font-weight: bold;
            background: #f0f0f0;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
        }

        .print-button {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 13px;
            border: none;
            cursor: pointer;
            background: #333;
            color: #fff;
        }

        .print-date-section {
            text-align: right;
            margin-top: 15px;
            font-size: 12px;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .container {
                border: none;
                padding: 0;
            }

            .branch-section,
            .print-button {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- HEADER -->
        <div class="header">
            <h1 id="company-title">M/S YES COURIERS NETWORK SERVICE</h1>
            <p id="company-address">
                VILLAGE HATHIKHAL, HOUSE NO 41, PO-ARJUNPUR, HALDWANI (NAINITAL), UK-263139
            </p>

            <div class="print-date-section">
                DATE: <span id="print-date"></span>
            </div>
        </div>

        <!-- FILTERS -->
        <div class="branch-section">

            <label><strong>Select Branch:</strong></label>
            <select id="branch-select" class="branch-select">
                <option value="">-- Select Branch --</option>

                @foreach ($payments as $payment)
                    <option value="{{ $payment->from_branch_id }},{{ $payment->to_branch_id }}">
                        {{ $hubs[$payment->from_branch_id] ?? $payment->from_branch_id }}
                        →
                        {{ $hubs[$payment->to_branch_id] ?? 'N/A' }}
                    </option>
                @endforeach
            </select>


            <div class="input-group">
                <div class="input-field">
                    <label>Company Name</label>
                    <input type="text" id="company-name" value="M/S YES COURIERS NETWORK SERVICE">
                </div>
                <div class="input-field">
                    <label>Address</label>
                    <input type="text" id="company-address-input"
                        value="VILLAGE HATHIKHAL, HOUSE NO 41, PO-ARJUNPUR, HALDWANI (NAINITAL), UK-263139">
                </div>
            </div>

            <div class="error" id="error-msg"></div>
            <div class="loading" id="loading">Loading ledger data…</div>
        </div>

        <!-- LEDGER TABLE -->
        <table class="ledger-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Particulars</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody id="ledger-body">
                <tr>
                    <td colspan="5" style="text-align:center;">Select a branch</td>
                </tr>
            </tbody>
        </table>

        <!-- TOTAL -->
        <table class="ledger-table">
            <tr class="total-row">
                <td colspan="2">TOTAL</td>
                <td id="total-debit">0.00</td>
                <td id="total-credit">0.00</td>
                <td id="total-balance">0.00</td>
            </tr>
        </table>

        <button class="print-button" onclick="window.print()">Print Ledger</button>
    </div>

    <script>
        document.getElementById("print-date").innerText =
            new Date().toLocaleDateString("en-IN");

        // Update header when company name changes
        document.getElementById("company-name").addEventListener("input", function() {
            document.getElementById("company-title").innerText = this.value || "M/S YES COURIERS NETWORK SERVICE";
        });

        // Update header when address changes
        document.getElementById("company-address-input").addEventListener("input", function() {
            document.getElementById("company-address").innerText = this.value ||
                "VILLAGE HATHIKHAL, HOUSE NO 41, PO-ARJUNPUR, HALDWANI (NAINITAL), UK-263139";
        });

        document.getElementById("branch-select").addEventListener("change", function() {

            const value = this.value;
            const ledgerBody = document.getElementById("ledger-body");
            const loading = document.getElementById("loading");
            const error = document.getElementById("error-msg");

            if (!value) {
                ledgerBody.innerHTML =
                    '<tr><td colspan="5" style="text-align:center;">Select a branch</td></tr>';
                return;
            }

            const [fromId, toId] = value.split(",");

            loading.style.display = "block";
            error.style.display = "none";
            ledgerBody.innerHTML = "";

            fetch(`/admin/ledger?from_branch_id=${fromId}&to_branch_id=${toId}`)
                .then(res => res.json())
                .then(data => {

                    loading.style.display = "none";

                    let totalDebit = 0;
                    let totalCredit = 0;
                    let balance = 0;

                    if (!data.ledger || data.ledger.length === 0) {
                        ledgerBody.innerHTML =
                            '<tr><td colspan="5" style="text-align:center;">No entries found</td></tr>';
                        return;
                    }

                    data.ledger.forEach(row => {
                        const debit = Number(row.debit) || 0;
                        const credit = Number(row.credit) || 0;

                        balance += debit - credit;
                        totalDebit += debit;
                        totalCredit += credit;

                        ledgerBody.innerHTML += `
                        <tr>
                            <td>${new Date(row.entry_date).toLocaleDateString("en-IN")}</td>
                            <td>${row.particulars}</td>
                            <td>${debit.toFixed(2)}</td>
                            <td>${credit.toFixed(2)}</td>
                            <td>${balance.toFixed(2)}</td>
                        </tr>`;
                    });

                    document.getElementById("total-debit").innerText = totalDebit.toFixed(2);
                    document.getElementById("total-credit").innerText = totalCredit.toFixed(2);
                    document.getElementById("total-balance").innerText =
                        (totalDebit - totalCredit).toFixed(2);
                })
                .catch(() => {
                    loading.style.display = "none";
                    error.style.display = "block";
                    error.innerText = "Failed to load ledger data";
                });
        });
    </script>

</body>

</html>
