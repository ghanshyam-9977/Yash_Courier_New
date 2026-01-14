<!-- resources/views/backend/fastbooking/print_shipper.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Fast Booking Shipper Print</title>
    <style>
        /* General body for screen */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #000;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
        }

        h2 {
            margin-top: 20px;
            margin-bottom: 5px;
            font-size: 18px;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            margin-bottom: 20px;
            font-size: 12px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        /* Avoid page break inside a booking */
        .booking-section {
            page-break-inside: avoid;
            margin-bottom: 40px;
        }

        /* Print button styles */
        .print-button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 8px 15px;
            font-size: 14px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .print-button:hover {
            background-color: #0056b3;
        }

        /* Print specific styles */
        @media print {
            body {
                margin: 0;
                font-size: 11pt;
                color: #000;
            }

            .print-button {
                display: none !important;
            }

            /* Remove background colors for printing */
            th {
                background-color: #fff !important;
            }

            /* Avoid breaking bookings between pages */
            .booking-section {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            /* Optional: make tables smaller in print */
            table {
                font-size: 11pt;
            }
        }
    </style>
</head>
<body>

    <button class="print-button" onclick="window.print()">Print this page</button>

    <h1>Fast Booking Shipper Details</h1>

    @forelse($bookingsData as $booking)
        <section class="booking-section">
            <h2>Booking No: {{ $booking->booking_no }}</h2>

            <table>
                <tr>
                    <th>From Branch ID</th>
                    <td>{{ $booking->from_branch_id }}</td>

                    <th>To Branch ID</th>
                    <td>{{ $booking->to_branch_id }}</td>
                </tr>
                <tr>
                    <th>Payment Type</th>
                    <td>{{ $booking->payment_type }}</td>

                    <th>Slip No</th>
                    <td>{{ $booking->booking_no ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Total Pieces</th>
                    <td>{{ $booking->total_pcs }}</td>

                    <th>Total Weight</th>
                    <td>{{ $booking->total_weight }}</td>
                </tr>
                <tr>
                    <th>Total Amount</th>
                    <td>{{ $booking->total_amount }}</td>

                    <th>COD Amount</th>
                    <td>{{ $booking->cod_amount }}</td>
                </tr>
                <tr>
                    <th>Remark</th>
                    <td colspan="3">{{ $booking->remark }}</td>
                </tr>
            </table>

            <strong>Parcels / Items:</strong>
            @if($booking->items->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Tracking No</th>
                        <th>Receiver Name</th>
                        <th>Address</th>
                        <th>Pieces</th>
                        <th>Weight</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($booking->items as $item)
                    <tr>
                        <td>{{ $item->tracking_no }}</td>
                        <td>{{ $item->receiver_name }}</td>
                        <td>{{ $item->address }}</td>
                        <td>{{ $item->pcs }}</td>
                        <td>{{ $item->weight }}</td>
                        <td>{{ $item->amount }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Separator line after parcels -->
            <hr style="border: 1px solid #000; margin: 20px 0;" />

            @else
                <p>No parcels found for this booking.</p>
            @endif
        </section>
    @empty
        <p>No bookings found.</p>
    @endforelse

</body>
</html>
