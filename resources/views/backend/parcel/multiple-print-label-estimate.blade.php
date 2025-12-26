<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Parcel Delivery Sheet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
        }

        th {
            background: #f2f2f2;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h3 {
            margin: 0;
        }

        .info {
            margin-bottom: 10px;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h3>YES COURIER</h3>
        <p>R.I.O, R.K. GARDEN BEHIND IBEX, CINEMA RAMPUR ROAD, HALDWANI 263139</p>
        <h4>Delivery Boy: {{ strtoupper($deliveryBoyName ?? 'N/A') }}</h4>
    </div>

    <div class="info">
        <p><strong>DR No:</strong> {{ $dr_no ?? 'N/A' }} &nbsp;&nbsp; <strong>Date:</strong> {{ now()->format('d-m-Y')
            }} &nbsp;&nbsp; <strong>Time:</strong> {{ now()->format('H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>CN No.</th>
                <th>WT</th>
                <th>Receiver Name / Address</th>
                <th>Stamp / Name / Sign</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($parcels as $index => $parcel)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td style="text-align:center;">
                    <svg class="barcode" jsbarcode-format="CODE128" jsbarcode-value="{{ $parcel->barcode }}"
                        jsbarcode-textmargin="2" jsbarcode-fontoptions="bold" jsbarcode-height="40"
                        jsbarcode-width="1.5">
                    </svg>
                    <div>{{ $parcel->barcode }}</div>
                </td>
                <td>{{ $parcel->weight ?? '0.0' }}</td>
                <td>
                    <strong>{{ $parcel->customer_name }}</strong><br>
                    {{ $parcel->customer_address }}
                </td>
                <td></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        // Uncomment to auto-print
        window.print();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
    <script>
        window.onload = function () {
            JsBarcode(".barcode").init();
        };
    </script>

</body>

</html>