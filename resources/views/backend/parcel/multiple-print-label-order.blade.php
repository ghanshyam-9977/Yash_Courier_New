<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OLS Logistics - Print Labels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 10px;
            font-family: 'Courier New', monospace;
            margin: 0;
        }

        .receipt {
            width: 380px;
            background: white;
            border: 2px dashed #000;
            padding: 12px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
            page-break-inside: avoid;
            margin-bottom: 20px;
        }

        .header {
            background: linear-gradient(90deg, #003087, #0050b3);
            color: white;
            text-align: center;
            padding: 8px;
            border-radius: 8px 8px 0 0;
            margin: -12px -12px 12px -12px;
        }

        .logo {
            font-size: 26px;
            font-weight: bold;
        }

        .logo span {
            color: #ff6600;
        }

        .barcode {
            text-align: center;
            margin: 12px 0;
        }

        .barcode img {
            height: 55px;
        }

        .footer {
            background: linear-gradient(90deg, #003087, #0050b3);
            color: white;
            padding: 8px;
            border-radius: 0 0 8px 8px;
            margin: 12px -12px -12px -12px;
            font-size: 13px;
        }

        .border-bottom-dotted {
            border-bottom: 2px dotted #000;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }

        .text-orange {
            color: #ff6600;
            font-weight: bold;
        }

        /* 3 Labels in One Row */
        .label-row {
            display: flex;
            justify-content: space-between;
            flex-wrap: nowrap;
            gap: 15px;
            margin-bottom: 20px;
            padding: 0 10px;
        }

        /* Print Optimization */
        @media print {
            body {
                padding: 0;
                background: white;
            }

            .label-row {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            .receipt {
                margin: 0;
                box-shadow: none;
                border: 2px dashed #000;
                width: 380px !important;
            }

            @page {
                size: A4 landscape;
                margin: 5mm;
            }
        }
    </style>
</head>

<body onload="window.print()">

    @php
        $chunks = $parcels->chunk(3); // 3 labels per row
    @endphp

    <div class="container-fluid">
        @foreach ($chunks as $chunk)
            <div class="label-row">
                @foreach ($chunk as $parcel)
                    <div class="receipt">
                        <!-- Header -->
                        <div class="header">
                            <div class="logo">O<span>LS</span> LOGISTICS <br>
                                <small>PRIVATE LIMITED</small>
                            </div>
                        </div>

                        <!-- Top Info -->
                        <div class="border-bottom-dotted">
                            <div class="row g-1 text-uppercase small">
                                <div class="col-6"><strong>{{ $parcel->tracking_id }}</strong></div>
                                <div class="col-6 text-end">Inv {{ $parcel->invoice_no ?? '2025263475' }}</div>
                                <div class="col-6">TOTAL PCS : {{ $parcel->number_of_parcels ?? 2 }}</div>
                                <div class="col-6 text-end">Booking Date
                                    {{ \Carbon\Carbon::parse($parcel->created_at)->format('d-m-Y') }}</div>
                            </div>
                            <p class="mt-2 mb-0"><strong>Eway Bill</strong></p>
                            <p class="mb-0">
                                <strong>{{ strtoupper($parcel->customer_name ?? 'SUSHIL KUMAR GANGWAR') }}</strong><br>
                                {{ strtoupper($parcel->customer_address ?? 'PBJK BIRSWARKAR NAGAR CHWARAH 100 FOOTA ROAD ANOOP MONIKA HOSPITAL KE BAGAL') }}<br>
                                MO.NO. {{ $parcel->customer_phone ?? '7983238005' }}
                            </p>
                        </div>

                        <h4 class="text-center my-2">
                            {{ $parcel->hub?->city ?? 'N/A' }} - {{ $parcel->hub?->pincode ?? 'N/A' }}
                        </h4>



                        <div class="row g-2 small text-uppercase">
                            <div class="col-6">Booking Client</div>
                            <div class="col-6 text-end"><strong>BOOKING HALDWANI</strong></div>
                            <div class="col-6">OLS HLD COURIER MODE</div>
                            <div class="col-6 text-end">
                                {{ $parcel->deliveryman_name ?? 'HALDWANI UT' }}<br>
                                {{ $parcel->pickup_phone_full ?? '9758866337' }}
                            </div>
                        </div>

                        <div class="barcode">
                            <img src="https://barcode.tec-it.com/barcode.ashx?data={{ $parcel->tracking_id }}-{{ str_pad($loop->parent->iteration . $loop->iteration, 4, '0', STR_PAD_LEFT) }}&code=Code128&dpi=96"
                                alt="Barcode">
                            <p class="mb-0"><strong>*
                                    {{ $parcel->tracking_id }}-{{ str_pad($loop->parent->iteration . $loop->iteration, 4, '0', STR_PAD_LEFT) }}
                                    *</strong></p>
                        </div>

                        <!-- Footer -->
                        <div class="footer text-center">
                            <strong>For Pickup & Delivery Contact No :</strong><br>
                            9310917631, 9313371459, 9711078054<br>
                            E-mail : support@olslogistics.in, Website : www.olslogistics.in
                        </div>
                    </div>
                @endforeach

                <!-- Agar row me 3 se kam hai to empty divs for alignment -->
                @if ($chunk->count() < 3)
                    @for ($i = $chunk->count(); $i < 3; $i++)
                        <div style="width: 380px;"></div>
                    @endfor
                @endif
            </div>
        @endforeach
    </div>

    <script>
        window.print();
        // Optional: print ke baad band ho jaye
        // setTimeout(() => window.close(), 1000);
    </script>
</body>

</html>
