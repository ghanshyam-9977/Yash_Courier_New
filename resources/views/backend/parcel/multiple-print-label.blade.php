<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- ✅ Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .page {
            margin-top: 2px;
            display: inline-block;
        }

        .main-table {
            border: 1px dashed;
            padding: 2px;
        }

        .label-section {
            width: 7.5cm;
        }

        /* ✅ Hide button only during print */
        @media print {

            form,
            button {
                display: none !important;
            }

            .page {
                page-break-after: always !important;
                margin-top: 3px;
            }

            .main-table {
                border: unset;
                padding: unset;
            }

            * {
                font-size: 10px !important;
            }

            .label-section {
                width: unset;
                height: unset;
            }

            @page {
                size: 7.5cm 3.5cm;
                margin: 5px !important;
            }

            body {
                zoom: 100%;
            }
        }
    </style>
</head>

<body class="p-3">

    <!-- ✅ Buttons Section -->
    <div class="d-flex justify-content-end gap-2 mb-3">

        <!-- 🖨️ Print Labels -->
        <form action="{{ route('parcel.multiple.print-label-estimate') }}" method="get" target="_blank" id="print_label_form_1">
            @csrf
            <div id="print_label_content_1"></div>
            <button type="submit" class="btn btn-primary btn-sm multiplelabelprint" id="print_labels_btn_1"
                style="padding: 8px 15px; font-size: 14px; display:none;">
                🖨️ Print Labels
            </button>
        </form>

        <!-- 🧾 Print Labels Info -->
        <form action="{{ route('parcel.multiple.print-label-info') }}" method="get" target="_blank" id="print_label_form_2">
            @csrf
            <div id="print_label_content_2"></div>
            <button type="submit" class="btn btn-success btn-sm multiplelabelprint" id="print_labels_btn_2"
                style="padding: 8px 15px; font-size: 14px; display:none;">
                🧾 Print Labels Info
            </button>
        </form>

        <form action="{{ route('parcel.multiple.print-label-orderinfo') }}" method="get" target="_blank" id="print_label_form_3">
            @csrf
            <div id="print_label_content_3"></div>
            <button type="submit" class="btn btn-success btn-sm multiplelabelprint" id="print_labels_btn_3"
                style="padding: 8px 15px; font-size: 14px; display:none;">
                🧾 Print Labels Order
            </button>
        </form>



    </div>

    <!-- ✅ Parcels List -->
    @foreach ($parcels as $parcel)
    <div class="page" style="padding-top:0px;">
        <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
            <input type="checkbox" class="parcel-checkbox" value="{{ $parcel->id }}"> Select

            <p><strong>ID:</strong> {{ $parcel->id }}</p>
            <p><strong>Merchant:</strong> {{ $parcel->merchant->business_name ?? 'N/A' }}</p>
            <p><strong>Delivery Boy Name:</strong> {{ $parcel->deliveryman_name ?? 'N/A' }}</p>
            <p><strong>Pickup Address:</strong> {{ $parcel->pickup_address }}</p>
            <p><strong>Pickup Phone:</strong> {{ $parcel->pickup_phone }}</p>
            <hr>
            <p><strong>Customer Name:</strong> {{ $parcel->customer_name }}</p>
            <p><strong>Customer Phone:</strong> {{ $parcel->customer_phone }}</p>
            <p><strong>Customer Address:</strong> {{ $parcel->customer_address }}</p>
            <hr>
            <p><strong>Cash Collection:</strong> ₹{{ number_format($parcel->cash_collection, 2) }}</p>
            <p><strong>Total Amount:</strong> ₹{{ number_format($parcel->total_delivery_amount, 2) }}</p>
            <hr>
            <p><strong>Tracking ID:</strong> {{ $parcel->tracking_id }}</p>
            <p><strong>Barcode:</strong> {{ $parcel->barcode }}</p>
            <p><strong>Status:</strong> {{ $parcel->status }}</p>
            <p><strong>Created At:</strong> {{ $parcel->created_at->format('d-m-Y h:i A') }}</p>
        </div>
    </div>
    @endforeach

    <!-- ✅ Script Section -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('.parcel-checkbox');

            const btn1 = document.getElementById('print_labels_btn_1');
            const content1 = document.getElementById('print_label_content_1');

            const btn2 = document.getElementById('print_labels_btn_2');
            const content2 = document.getElementById('print_label_content_2');

            const btn3 = document.getElementById('print_labels_btn_3');
            const content3 = document.getElementById('print_label_content_3');

            checkboxes.forEach(cb => {
                cb.addEventListener('change', function () {
                    const selected = Array.from(checkboxes)
                        .filter(c => c.checked)
                        .map(c => c.value);

                    // reset hidden inputs
                    content1.innerHTML = '';
                    content2.innerHTML = '';
                    content3.innerHTML = '';

                    selected.forEach(id => {
                        content1.innerHTML += `<input type="hidden" name="parcels[]" value="${id}">`;
                        content2.innerHTML += `<input type="hidden" name="parcels[]" value="${id}">`;
                        content3.innerHTML += `<input type="hidden" name="parcels[]" value="${id}">`;
                    });

                    // Show buttons if any parcel selected
                    const visible = selected.length > 0;
                    btn1.style.display = visible ? 'inline-block' : 'none';
                    btn2.style.display = visible ? 'inline-block' : 'none';
                    btn3.style.display = visible ? 'inline-block' : 'none';
                });
            });
        });
    </script>

</body>

</html>
