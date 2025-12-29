<!DOCTYPE html>
<html>

<head>
    <title>Branch List</title>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }
        }


        @page {
            size: A4;
            margin: 10mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
        }

        h4 {
            text-align: center;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .date {
            text-align: right;
            font-size: 11px;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
        }

        th {
            text-align: center;
            font-weight: bold;
        }

        td {
            font-size: 11.5px;
        }

        .center {
            text-align: center;
        }

        .nowrap {
            white-space: nowrap;
        }

        .address {
            white-space: normal;
        }

        @media print {
            body {
                margin: 0;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <h4>Branch List</h4>
    <div class="date">Date: {{ now()->format('d-m-Y') }}</div>

    <table>
        <thead>
            <tr>
                <th width="40">S.No</th>
                <th width="140">Branch Name</th>
                <th>Address</th>
                <th width="120">Contact Person</th>
                <th width="110">Contact No</th>
                <th width="90">City</th>
                <th width="70">Pincode</th>
            </tr>
        </thead>

        <tbody>
            @forelse($hubs as $index => $hub)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $hub->name }}</td>
                    <td class="address">{{ $hub->address }}</td>
                    <td>{{ $hub->contact_person }}</td>
                    <td class="nowrap">{{ $hub->phone }}</td>
                    <td class="center">{{ $hub->city }}</td>
                    <td class="center">{{ $hub->pincode }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="center">No records found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>
