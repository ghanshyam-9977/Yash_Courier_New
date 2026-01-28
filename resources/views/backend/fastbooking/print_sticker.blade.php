<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            size: A4;
<<<<<<< HEAD
            margin: 8mm;
=======
            margin: 10mm;
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
        }

        body {
            margin: 0;
<<<<<<< HEAD
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 10px;
            background: #f5f5f5;
        }

        /* Print button */
        .print-bar {
            text-align: right;
            margin-bottom: 12px;
            padding: 10px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .print-btn {
            padding: 10px 20px;
            font-size: 14px;
            border: none;
            background: #2563eb;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }

        .print-btn:hover {
            background: #1d4ed8;
        }

        @media print {
            .print-bar {
                display: none;
            }

            body {
                background: white;
            }
        }

        /* 4 STICKERS PER ROW */
        .sticker-wrapper {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6mm;
            padding: 5mm;
        }

        .sticker {
            width: 100%;
            aspect-ratio: 48 / 50;
            border: 2px solid #333;
            padding: 4mm;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: white;
            page-break-inside: avoid;
            font-size: 9px;
        }

        .info {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .info div {
            line-height: 1.2;
            margin-bottom: 2px;
=======
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
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
        }

        .field-label {
            font-weight: bold;
<<<<<<< HEAD
            color: #222;
        }

        .field-value {
            word-break: break-word;
        }

        .barcode {
            text-align: center;
            margin-top: 3px;
=======
        }

        .barcode {
            margin-top: 10px;
            width: 100%;
            height: 60px;
            text-align: center;
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
        }

        .barcode img {
            width: 100%;
<<<<<<< HEAD
            height: 18mm;
            object-fit: contain;
        }

        @media screen {
            .sticker {
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            }
        }
=======
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
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
    </style>
</head>

<body>

<<<<<<< HEAD
    <div class="print-bar">
        <button class="print-btn" onclick="window.print()">🖨️ Print Stickers</button>
    </div>

    <div class="sticker-wrapper">
        @foreach ($fastBookings as $booking)
            <div class="sticker">
                <div class="info">
                    <div><span class="field-label">Tracking:</span> <span
                            class="field-value">{{ $booking->tracking_no }}</span></div>
                    <div><span class="field-label">PCS:</span> <span class="field-value">{{ $booking->pcs }}</span></div>
                    <div><span class="field-label">Receiver:</span> <span
                            class="field-value">{{ $booking->receiver_name }}</span></div>
                    <div><span class="field-label">Address:</span> <span
                            class="field-value">{{ $booking->address }}</span></div>
                    <div><span class="field-label">COD:</span> <span
                            class="field-value">₹{{ optional($booking->fastBooking)->cod_amount ?? 0 }}</span></div>
                </div>
                <div class="barcode">
                    <img src="{{ asset('storage/' . $booking->barcode_image) }}" alt="Barcode">
                </div>
=======
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
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
            </div>
        @endforeach
    </div>

</body>
<<<<<<< HEAD

=======
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
</html>
