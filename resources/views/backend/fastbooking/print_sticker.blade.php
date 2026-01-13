<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        /* Container for all stickers */
        .sticker-wrapper {
            display: grid;
            grid-template-columns: repeat(auto-fill, 3in);
            gap: 10px;
        }

        /* Single sticker */
        .sticker {
            width: 3in;
            height: 2in;
            border: 1px solid #000;
            padding: 6px;
            box-sizing: border-box;
            page-break-inside: avoid;
        }

        .field-label {
            font-weight: bold;
        }

        .barcode {
            margin-top: 10px;
            width: 100%;
            height: 60px;
            text-align: center;
        }

        .barcode img {
            width: 100%;
            height: 60px;
            object-fit: contain;
        }

        /* .handwritten-note {
            margin-top: 12px;
            font-style: italic;
            font-size: 10px;
            border-top: 1px dashed #333;
            padding-top: 5px;
        } */
    </style>
</head>

<body>

    <div class="sticker-wrapper">
        @foreach ($fastBookings as $booking)
            <div class="sticker">
                <div><span class="field-label">Tracking No:</span> {{ $booking->tracking_no }}</div>
                <div><span class="field-label">PCS:</span> {{ $booking->pcs }}</div>
                <div><span class="field-label">Receiver Name:</span> {{ $booking->receiver_name }}</div>
                <div><span class="field-label">Address:</span> {{ $booking->address }}</div>
                <div><span class="field-label">COD:</span> ₹{{ $booking->cod_amount }}</div>

                <div class="barcode">
                    <img src="{{ asset('storage/' . $booking->barcode_image) }}" alt="Barcode">
                </div>

                {{-- <div class="handwritten-note">
                    Handle with care
                </div> --}}
            </div>
        @endforeach
    </div>

</body>
</html>
