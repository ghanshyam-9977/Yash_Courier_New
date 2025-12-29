<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Courier Slip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 15px;
            color: #000;
        }

        .sheet {
            border: 2px solid #000;
            padding: 8px;
            width: 100%;
            margin-bottom: 20px;
            page-break-after: always;
        }

        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }

        .header-left h3 {
            margin: 0;
            font-weight: bold;
        }

        .header-right {
            text-align: right;
        }

        .section-title {
            background-color: #000;
            color: #fff;
            font-weight: bold;
            text-align: center;
            padding: 2px 0;
            border: 1px solid #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 3px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 3px 5px;
            vertical-align: top;
        }

        .barcode-box {
            text-align: center;
            border: 1px solid #000;
            font-weight: bold;
        }

        .barcode {
            font-size: 14px;
            margin-top: 3px;
            display: block;
        }

        .gray {
            background-color: #e6e6e6;
            font-weight: bold;
        }

        .footer-note {
            margin-top: 5px;
            font-size: 11px;
            text-align: center;
            font-weight: bold;
            border-top: 2px solid #000;
            padding-top: 3px;
        }

        .no-print {
            text-align: right;
            margin-bottom: 10px;
        }



        /* Optional: print adjustments */
        @media print {

            body {
                padding: 0;
                background: white;
            }

            .label-row {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            .sheet {
                margin: 0;
                box-shadow: none;
                border: 2px dashed #000;
                width: 100% !important;
            }

            @page {
                size: A4 landscape;
                margin: 5mm;
            }
        }
    </style>
</head>

<body>

    <div class="no-print">
        <button onclick="window.print()">🖨️ Print All Slips</button>
    </div>

    @foreach ($parcels as $parcel)
        <div class="sheet">
            <div class="header">
                <div class="header-left">
                    <h3>OASIS Logistics Solutions</h3>
                    <p><strong>Address:</strong> 36, Ground Floor, New Arya Nagar, Ghaziabad 201001</p>
                    <p><strong>Email:</strong> support@olslogistics.in</p>
                    <p><strong>Contact:</strong> 9133174459 / 9106176317</p>
                </div>
                <div class="header-right">
                    <h4>SUPPLY CHAIN SOLUTION</h4>
                    <p><strong>Invoice No:</strong> {{ $parcel->invoice_no }}</p>
                    <p><strong>Order No:</strong> {{ $parcel->tracking_id }}</p>
                    <p><strong>Invoice Amt:</strong> ₹{{ number_format($parcel->price, 2) }}</p>
                </div>
            </div>

            <table>
                <tr>
                    <th colspan="2" class="section-title">SENDER</th>
                    <th colspan="3" class="section-title">RECEIVER</th>
                </tr>
                <tr>
                    <td colspan="2">OASIS Logistics Solutions - HALDWANI</td>
                    <td colspan="3">{{ $parcel->customer_name }}, {{ $parcel->customer_address ?? 'Address Not Available' }}
                    </td>
                </tr>
                <tr class="gray">
                    <td>CONTACT</td>
                    <td colspan="4">{{ $parcel->customer_phone }}</td>
                </tr>
                <tr>
                    <td><strong>ACTUAL WT</strong></td>
                    <td>{{ $parcel->weight ?? '0.00' }}</td>
                    <td><strong>CHARGED WT</strong></td>
                    <td>—</td>
                    <td><strong>PCS:</strong> {{ $parcel->number_of_parcels }}</td>
                </tr>
                <tr>
                    <td><strong>ORIGIN</strong></td>
                    <td>HALDWANI</td>
                    <td><strong>DESTINATION</strong></td>
                    <td>BAREILLY</td>
                    <td><strong>MODE</strong> SURFACE</td>
                </tr>
                <tr>
                    <td><strong>Booking Date</strong></td>
                    <td>{{ \Carbon\Carbon::parse($parcel->created_at)->format('d-m-Y') }}</td>
                    <td><strong>Payment</strong></td>
                    <td colspan="2">{{ $parcel->parcel_payment_method == 1 ? 'CREDIT' : 'COD' }}</td>
                </tr>
                <tr>
                    <td><strong>FOD AMT</strong></td>
                    <td>0.0</td>
                    <td><strong>COD AMT</strong></td>
                    <td colspan="2">{{ $parcel->cash_collection ?? '0.0' }}</td>
                </tr>
            </table>

            <div style="margin-top:10px; display:flex; justify-content:space-between;">
                <div style="width: 45%;">
                    <p><strong>Received By:</strong> ______________________</p>
                    <p><strong>Signature / Date / Time / Seal</strong></p>
                </div>
                <div class="barcode-box" style="width: 45%;">
                    <div><strong>AWB No:</strong> {{ $parcel->barcode }}</div>
                    <div class="barcode">|| ||| ||||| |||</div>
                    <div><strong>Forwarding No:</strong> {{ $parcel->tracking_id }}</div>
                </div>
            </div>

            <div class="footer-note">
                STRICTLY PROHIBITED FOR SHARE CERTIFICATES, PASSPORT, CASH BEARER CHEQUES & LIQUIDS.
            </div>
        </div>
    @endforeach

</body>

</html>