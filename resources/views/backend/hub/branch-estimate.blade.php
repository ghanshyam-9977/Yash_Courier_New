<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YES Couriers Manifest</title>

    <style>
        /* Simple and Clean Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border: 1px solid #ddd;
        }

        /* Type Selector */
        #typeSelector {
            text-align: center;
            padding: 50px 20px;
        }

        #typeSelector h3 {
            margin-bottom: 30px;
            font-size: 24px;
        }

        .big-btn {
            padding: 12px 25px;
            margin: 0 10px;
            border: 1px solid #ccc;
            background: #f8f8f8;
            cursor: pointer;
            font-size: 16px;
            border-radius: 4px;
        }

        .btn-out {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }

        .btn-in {
            background: #28a745;
            color: white;
            border-color: #28a745;
        }

        /* Form Styles */
        .form-card {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            background: #fafafa;
        }

        .form-card h3 {
            margin-bottom: 20px;
            text-align: center;
        }

        .form-card label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-card select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .submit-btn {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 16px;
        }

        .submit-btn:hover {
            background: #0056b3;
        }

        /* Manifest Styles */
        .manifest-card {
            padding: 20px;
            border: 1px solid #ddd;
        }

        .manifest-card h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .manifest-card h2.in-manifest {
            border-bottom-color: #28a745;
        }

        /* Tables */
        .header-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .header-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .header-table td:first-child {
            background: #f9f9f9;
            font-weight: bold;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table th,
        .data-table td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .data-table th {
            background: #f5f5f5;
            font-weight: bold;
        }

        .data-table tbody tr:nth-child(even) {
            background: #fafafa;
        }

        /* Total Row */
        .total-row {
            background: #e9ecef;
            font-weight: bold;
        }

        .total-row .total-label {
            text-align: right;
        }

        /* Buttons */
        .print-button {
            padding: 10px 20px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 16px;
            display: block;
            margin: 20px auto;
        }

        .print-button:hover {
            background: #218838;
        }

        /* No Records */
        .no-records {
            text-align: center;
            padding: 20px;
            color: #666;
            background: #f8f8f8;
            border: 1px solid #ddd;
            margin: 20px 0;
        }

        /* Print Styles - Only show manifest content */
        @media print {
            body {
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .container {
                max-width: none !important;
                margin: 0 !important;
                padding: 0 !important;
                border: none !important;
                background: white !important;
            }

            /* Hide everything except manifest cards */
            #typeSelector,
            .form-card,
            .print-button {
                display: none !important;
            }

            .manifest-card {
                page-break-inside: avoid;
                margin: 0 !important;
                padding: 10px !important;
                border: none !important;
                background: white !important;
            }

            .manifest-card h2 {
                font-size: 20px !important;
                margin-bottom: 15px !important;
                border-bottom: 1px solid black !important;
                padding-bottom: 5px !important;
            }

            .header-table {
                margin-bottom: 15px !important;
                font-size: 12px !important;
            }

            .header-table td {
                padding: 4px !important;
                border: 1px solid black !important;
            }

            .data-table {
                font-size: 11px !important;
                margin-bottom: 10px !important;
            }

            .data-table th,
            .data-table td {
                padding: 4px !important;
                border: 1px solid black !important;
            }

            .data-table th {
                background: #f0f0f0 !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }

            .total-row {
                background: #e0e0e0 !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                font-weight: bold !important;
            }

            .no-records {
                border: 1px solid black !important;
                padding: 10px !important;
            }

            /* Page breaks */
            .manifest-card {
                page-break-after: always;
            }

            .manifest-card:last-child {
                page-break-after: avoid;
            }
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .container {
                padding: 10px;
            }

            .big-btn {
                display: block;
                width: 100%;
                margin: 5px 0;
            }

            .data-table {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    @if (!request()->has('type'))
        <div id="typeSelector">
            <div class="inner">
                <h3>Select Manifest Type</h3>
                <button class="big-btn btn-out" onclick="showManifest('out')">OUT Manifest</button>
                <button class="big-btn btn-in" onclick="showManifest('in')">IN Manifest</button>
            </div>
        </div>
    @endif

    <!-- ── OUT Form ── -->
    @if (request('type') === 'out')
        <div class="container">
            <div class="form-card">
                <h3>OUT Manifest – Select Branches</h3>
                <form method="GET">
                    <input type="hidden" name="type" value="out">

                    <label>From Branch</label>
                    <select name="from_branch" required>
                        <option value="">— Select —</option>
                        @foreach ($hubs as $hub)
                            <option value="{{ $hub->id }}"
                                {{ request('from_branch') == $hub->id ? 'selected' : '' }}>
                                {{ $hub->name }}
                            </option>
                        @endforeach
                    </select>

                    <label>To Branch</label>
                    <select name="to_branch" required>
                        <option value="">— Select —</option>
                        @foreach ($hubs as $hub)
                            <option value="{{ $hub->id }}"
                                {{ request('to_branch') == $hub->id ? 'selected' : '' }}>
                                {{ $hub->name }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="submit-btn">Load OUT Manifest</button>
                </form>
            </div>
        </div>
    @endif

    <!-- ── IN Form ── -->
    @if (request('type') === 'in')
        <div class="container">
            <div class="form-card">
                <h3>IN Manifest – Select Branch</h3>
                <form method="GET">
                    <input type="hidden" name="type" value="in">

                    <!-- For IN usually only one branch is needed (receiving branch) -->
                    <label>Receiving Branch (To)</label>
                    <select name="to_branch" required>
                        <option value="">— Select —</option>
                        @foreach ($hubs as $hub)
                            <option value="{{ $hub->id }}"
                                {{ request('to_branch') == $hub->id ? 'selected' : '' }}>
                                {{ $hub->name }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Optional: From branch filter (if you want to see incoming from specific branch) -->
                    <label>From Branch (optional filter)</label>
                    <select name="from_branch">
                        <option value="">— All Branches —</option>
                        @foreach ($hubs as $hub)
                            <option value="{{ $hub->id }}"
                                {{ request('from_branch') == $hub->id ? 'selected' : '' }}>
                                {{ $hub->name }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="submit-btn">Load IN Manifest</button>
                </form>
            </div>
        </div>
    @endif

    <!-- ── OUT Manifest Display ── -->
    @if (request('type') === 'out' && isset($outRecords))
        <div class="container">
            <div class="manifest-card" id="printArea">
                <h2>OUT MANIFEST</h2>

                <table class="header-table">
                    <tr>
                        <td width="20%"><b>OUTMFNO</b></td>
                        <td width="40%">{{ $manifestNo ?? 'NA' }}</td>
                        <td width="15%"><b>DATE</b></td>
                        <td width="25%">{{ now()->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td><b>FROM</b></td>
                        <td>{{ $fromBranch ?? 'NA' }}</td>
                        <td><b>TO</b></td>
                        <td>{{ $toBranch ?? 'NA' }}</td>
                    </tr>
                </table>

                @if ($outRecords->isEmpty())
                    <div class="no-records">No consignments found for selected branches.</div>
                @else
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Tracking No</th>
                                <th>Weight</th>
                                <th>City</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach ($outRecords as $i => $row)
                                @php $total += $row->amount ?? 0; @endphp
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $row->tracking_number ?? '—' }}</td>
                                    <td>{{ $row->quantity ?? 0 }} {{ $row->unit ?? 'gm' }}</td>
                                    <td>{{ $row->city ?? '—' }}{!! $row->state ? ', ' . $row->state : '' !!}</td>
                                    <td>₹ {{ number_format($row->amount ?? 0, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr class="total-row">
                                <td colspan="4" class="total-label">TOTAL</td>
                                <td>₹ {{ number_format($total, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <button class="print-button" onclick="window.print()">🖨 Print Manifest</button>
                @endif
            </div>
        </div>
    @endif

    <!-- ── IN Manifest Display ── -->
    @if (request('type') === 'in' && isset($inRecords))
        <div class="container">
            <div class="manifest-card" id="printArea">
                <h2 class="in-manifest">IN MANIFEST</h2>

                <table class="header-table">
                    <tr>
                        <td width="20%"><b>INMFNO</b></td>
                        <td width="40%">{{ $manifestNo ?? 'NA' }}</td>
                        <td width="15%"><b>DATE</b></td>
                        <td width="25%">{{ now()->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td><b>FROM BRANCH</b></td>
                        <td>{{ $fromBranch ?? 'NA' }}</td>
                        <td><b>RECEIVING BRANCH</b></td>
                        <td>{{ $toBranch ?? 'NA' }}</td>
                    </tr>
                </table>

                @if ($inRecords->isEmpty())
                    <div class="no-records">No incoming consignments found.</div>
                @else
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Tracking No</th>
                                <th>Weight</th>
                                <th>From City</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach ($inRecords as $i => $row)
                                @php $total += $row->amount ?? 0; @endphp
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $row->tracking_number ?? '—' }}</td>
                                    <td>{{ $row->quantity ?? 0 }} {{ $row->unit ?? 'gm' }}</td>
                                    <td>{{ $row->from_city ?? ($row->city ?? '—') }}{!! $row->from_state ? ', ' . $row->from_state : '' !!}</td>
                                    <td>₹ {{ number_format($row->amount ?? 0, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr class="total-row">
                                <td colspan="4" class="total-label">TOTAL</td>
                                <td>₹ {{ number_format($total, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <button class="print-button" onclick="window.print()">🖨 Print Manifest</button>
                @endif
            </div>
        </div>
    @endif

    <script>
        function showManifest(type) {
            document.getElementById('typeSelector')?.remove();
            const url = new URL(location.href);
            url.searchParams.set('type', type);
            // Optional: clear old params if needed
            url.searchParams.delete('from_branch');
            url.searchParams.delete('to_branch');
            location.href = url;
        }
    </script>
</body>

</html>
