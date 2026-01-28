@extends('backend.partials.master')

@section('title')
    Fast Booking - {{ isset($booking) ? 'Edit' : 'Create' }}
@endsection

@section('maincontent')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f5f5f5;
<<<<<<< HEAD
            font-family: 'Arial', sans-serif;
        }

        .container-main {
            max-width: 1400px;
            margin: 20px auto;
            padding: 0 15px;
        }

        .form-header {
            background: #1e3a8a;
            color: white;
            padding: 20px 25px;
            border-radius: 4px;
            margin-bottom: 25px;
            font-size: 16px;
            font-weight: bold;
        }

        .form-wrapper {
            background: white;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Three Column Layout */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-row.two-col {
            grid-template-columns: 1fr 1fr;
        }

        .form-row.full {
            grid-template-columns: 1fr;
=======
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .drs-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .drs-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .drs-header h2 {
            font-size: 24px;
            margin: 0;
            font-weight: 700;
        }

        .form-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .row-content {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

<<<<<<< HEAD
        .form-group label {
            font-size: 11px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
=======
        label {
            font-size: 12px;
            font-weight: 700;
            color: #555;
            margin-bottom: 8px;
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

<<<<<<< HEAD
        .form-group label.required::after {
            content: " *";
            color: #dc2626;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group input[type="date"],
        .form-group input[type="tel"],
        .form-group input[type="email"],
        .form-group textarea,
        .form-group select {
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 12px;
            font-family: Arial, sans-serif;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #1e3a8a;
            outline: none;
            box-shadow: 0 0 0 2px rgba(30, 58, 138, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 8px center;
            background-size: 16px;
            padding-right: 32px;
            cursor: pointer;
        }

        /* Checkbox Styling */
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
        }

        .checkbox-group input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: #1e3a8a;
        }

        .checkbox-group label {
            margin: 0;
            font-size: 12px;
            font-weight: normal;
            text-transform: none;
            cursor: pointer;
        }

        /* Section Title */
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Table Styling */
        .items-section {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #e5e7eb;
        }

        .items-section .section-title {
            margin-bottom: 15px;
=======
        .required::after {
            content: " *";
            color: #ef4444;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        input[type="time"],
        input[type="tel"],
        input[type="email"],
        textarea,
        select {
            padding: 12px 14px;
            border: 1.5px solid #ddd;
            border-radius: 6px;
            font-size: 13px;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        input[type="date"]:focus,
        input[type="time"]:focus,
        input[type="tel"]:focus,
        input[type="email"]:focus,
        textarea:focus,
        select:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .row-main-content {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }

        .row-bottom {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }

        /* Items Table */
        .items-section {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #f0f0f0;
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
        }

        .items-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
<<<<<<< HEAD
            margin-bottom: 15px;
        }

        .btn-add-item {
            background: #059669;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-weight: bold;
            font-size: 12px;
            transition: background 0.3s;
        }

        .btn-add-item:hover {
            background: #047857;
=======
            margin-bottom: 20px;
        }

        .items-header h3 {
            font-size: 18px;
            font-weight: 700;
            color: #333;
        }

        .btn-add-item {
            background: #10b981;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-add-item:hover {
            background: #059669;
            transform: translateY(-2px);
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
<<<<<<< HEAD
            font-size: 12px;
        }

        .items-table thead {
            background: #f3f4f6;
            border-top: 1px solid #ddd;
=======
        }

        .items-table thead {
            background: #f8f9fa;
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
            border-bottom: 2px solid #ddd;
        }

        .items-table th {
<<<<<<< HEAD
            padding: 10px;
            text-align: left;
            font-weight: bold;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 11px;
        }

        .items-table td {
            padding: 10px;
=======
            padding: 12px;
            text-align: left;
            font-weight: 700;
            color: #555;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .items-table td {
            padding: 12px;
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
            border-bottom: 1px solid #f0f0f0;
        }

        .items-table tbody tr:hover {
            background: #f9f9f9;
        }

        .item-input {
            width: 100%;
<<<<<<< HEAD
            padding: 7px 8px;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 11px;
            font-family: Arial, sans-serif;
        }

        .item-input:focus {
            border-color: #1e3a8a;
            outline: none;
        }

        .btn-remove-item {
            background: #dc2626;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 11px;
            font-weight: bold;
            transition: background 0.3s;
            width: 100%;
        }

        .btn-remove-item:hover {
            background: #b91c1c;
        }

        .totals-row {
            background: #f3f4f6;
            font-weight: bold;
            border-top: 2px solid #ddd;
        }

        /* Button Group */
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
            justify-content: flex-start;
            flex-wrap: wrap;
        }

        .btn-custom {
            padding: 10px 24px;
            border: none;
            border-radius: 3px;
            font-weight: bold;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-save {
            background: #1e3a8a;
=======
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 13px;
        }

        .btn-remove-item {
            background: #ef4444;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s ease;
        }

        .btn-remove-item:hover {
            background: #dc2626;
        }

        .button-group {
            display: flex;
            gap: 12px;
            justify-content: flex-start;
            flex-wrap: wrap;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
        }

        .btn-custom {
            padding: 12px 28px;
            border-radius: 6px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 12px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-save {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
            color: white;
        }

        .btn-save:hover {
<<<<<<< HEAD
            background: #1e40af;
            box-shadow: 0 2px 4px rgba(30, 58, 138, 0.3);
        }

        .btn-selector {
            background: #7c3aed;
            color: white;
        }

        .btn-selector:hover {
            background: #6d28d9;
        }

        .btn-shipper {
            background: #2563eb;
            color: white;
        }

        .btn-shipper:hover {
            background: #1d4ed8;
        }

        .btn-cancel {
            background: #f3f4f6;
=======
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #f0f0f0;
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
            color: #333;
            border: 1px solid #ddd;
        }

<<<<<<< HEAD
        .btn-cancel:hover {
            background: #e5e7eb;
        }

        /* Toast Notifications */
        #toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }

        .toast {
            background: #059669;
            color: white;
            padding: 12px 16px;
            border-radius: 3px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            margin-bottom: 10px;
            font-size: 12px;
            font-weight: bold;
            animation: slideIn 0.3s ease-out;
        }

        .toast.error {
            background: #dc2626;
=======
        .btn-secondary:hover {
            background: #e0e0e0;
        }

        .totals-row {
            background: #f8f9fa;
            font-weight: 700;
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
        }

        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

<<<<<<< HEAD
        .validation-error {
            color: #dc2626;
            font-size: 11px;
            margin-top: 3px;
        }

        @media (max-width: 1024px) {
            .form-row {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {
            .form-row,
            .form-row.two-col,
            .form-row.full {
                grid-template-columns: 1fr;
            }

            .items-table {
                font-size: 11px;
            }

            .items-table th,
            .items-table td {
                padding: 8px;
            }

=======
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        #toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }

        .toast {
            background: #10b981;
            color: white;
            padding: 14px 16px;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            animation: slideIn 0.3s ease-out;
            margin-bottom: 10px;
            min-width: 320px;
        }

        .toast.error {
            background: #ef4444;
        }

        .validation-error {
            color: #ef4444;
            font-size: 12px;
            margin-top: 4px;
        }

        @media (max-width: 768px) {
            .row-content,
            .row-main-content,
            .row-bottom {
                grid-template-columns: 1fr;
            }

>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
            .button-group {
                flex-direction: column;
            }

            .btn-custom {
                width: 100%;
            }
<<<<<<< HEAD
        }
    </style>

    <div class="container-main">
        <div class="form-header">
            📦 {{ isset($booking) ? 'Edit' : 'Create' }} Fast Booking
        </div>

        <div class="form-wrapper">
            <form id="booking-form"
                action="{{ isset($booking) ? route('fast_bookings.update', $booking->id) : route('fast_bookings.store') }}"
                method="POST">
                @csrf
                @if (isset($booking))
                    @method('PUT')
                @endif

                <!-- Booking Details Section -->
                <div class="section-title">📋 Booking Details</div>

                <!-- Row 1: From Branch, Forwarding No, Network -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="required">From Booking Station</label>
=======

            .items-table {
                font-size: 12px;
            }

            .items-table th,
            .items-table td {
                padding: 8px;
            }
        }
    </style>

    <div class="drs-container">
        <div class="drs-header">
            <h2>📦 {{ isset($booking) ? 'Edit' : 'Create' }} Fast Booking</h2>
        </div>

        <form id="booking-form"
            action="{{ isset($booking) ? route('fast_bookings.update', $booking->id) : route('fast_bookings.store') }}"
            method="POST">
            @csrf
            @if (isset($booking))
                @method('PUT')
            @endif

            <div class="form-card">
                <!-- Main Booking Fields -->
                <div class="row-content">
                    <div class="form-group">
                        <label class="required">Booking No</label>
                        <input type="text" name="booking_no" placeholder="Enter booking number"
                            value="{{ isset($booking) ? $booking->booking_no : '' }}" required>
                        @error('booking_no')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="required">From Branch</label>
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
                        <select name="from_branch_id" required>
                            <option value="">Select Branch</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ isset($booking) && $booking->from_branch_id == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('from_branch_id')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
                    </div>
<<<<<<< HEAD

                    <div class="form-group">
                        <label>Forwarding No</label>
                        <input type="text" name="forwarding_no" placeholder="Auto / Enter at dispatch"
                            value="{{ isset($booking) ? $booking->forwarding_no : '' }}">
                        @error('forwarding_no')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Network</label>
                        <input type="text" name="network" placeholder="Company Name"
                            value="{{ isset($booking) ? $booking->network : '' }}">
=======
                    <div class="form-group">
                        <label class="required">To Branch</label>
                        <select name="to_branch_id" required>
                            <option value="">Select Branch</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ isset($booking) && $booking->to_branch_id == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('to_branch_id')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Network & Payment -->
                <div class="row-main-content">
                    <div class="form-group">
                        <label class="required">Network</label>
                        <select name="network" required>
                            <option value="">Select Network</option>
                            @foreach ($networks as $network)
                                <option value="{{ $network }}"
                                    {{ isset($booking) && $booking->network == $network ? 'selected' : '' }}>
                                    {{ $network }}
                                </option>
                            @endforeach
                        </select>
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
                        @error('network')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
                    </div>
<<<<<<< HEAD
                </div>

                <!-- Row 2: City, State, E-Way Bill No -->
                <div class="form-row">
                    <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city" placeholder="City Name"
                            value="{{ isset($booking) ? $booking->city : '' }}">
                        @error('city')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>State</label>
                        <input type="text" name="state" placeholder="State Name"
                            value="{{ isset($booking) ? $booking->state : '' }}">
                        @error('state')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>E-Way Bill No</label>
                        <input type="text" name="eway_bill_no" placeholder="E-Way Bill Number"
                            value="{{ isset($booking) ? $booking->eway_bill_no : '' }}">
                        @error('eway_bill_no')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Row 3: COD Amount and Remark -->
                <div class="form-row two-col">
                    <div class="form-group">
                        <label>COD Amount</label>
                        <input type="number" step="0.01" name="cod_amount" placeholder="Enter COD Amount"
                            value="{{ isset($booking) ? $booking->cod_amount : '' }}" min="0">
                        @error('cod_amount')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Remark / Notes</label>
                        <textarea name="remark" placeholder="Add remarks here" style="resize: vertical; min-height: 40px;">{{ isset($booking) ? $booking->remark : '' }}</textarea>
                        @error('remark')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Items Section -->
                <div class="items-section">
                    <div class="section-title">📦 Booking Items</div>

                    <div class="items-header">
                        <span style="font-weight: bold; color: #333;">Items List</span>
                        <button type="button" class="btn-add-item" id="btn-add-item">+ Add Item</button>
                    </div>

                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>Tracking No <span style="color: #dc2626;">*</span></th>
                                <th>Receiver Name</th>
                                <th>Address</th>
                                <th>PCS</th>
                                <th>Weight (kg)</th>
                                <th>Amount</th>
                                <th style="width: 70px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="items-body">
                            @if (isset($booking) && $booking->items->count() > 0)
                                @foreach ($booking->items as $item)
                                    <tr class="item-row">
                                        <td><input type="text" name="items[tracking_no][]" class="item-input"
                                                value="{{ $item->tracking_no }}" required></td>
                                        <td><input type="text" name="items[receiver_name][]" class="item-input"
                                                value="{{ $item->receiver_name }}"></td>
                                        <td><input type="text" name="items[address][]" class="item-input"
                                                value="{{ $item->address }}"></td>
                                        <td><input type="number" name="items[pcs][]" class="item-input item-pcs"
                                                value="{{ $item->pcs }}" min="1"></td>
                                        <td><input type="number" step="0.01" name="items[weight][]"
                                                class="item-input item-weight" value="{{ $item->weight }}" min="0.01"></td>
                                        <td><input type="number" step="0.01" name="items[amount][]"
                                                class="item-input item-amount" value="{{ $item->amount }}" min="0"></td>
                                        <td><button type="button" class="btn-remove-item"
                                                onclick="removeItem(this)">Remove</button></td>
                                    </tr>
                                @endforeach
                            @else
                                <tr id="empty-row">
                                    <td colspan="7" style="text-align: center; color: #999; padding: 30px;">No items added yet</td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr class="totals-row">
                                <td colspan="3">TOTALS</td>
                                <td><span id="total-pcs">0</span></td>
                                <td><span id="total-weight">0.00</span></td>
                                <td><span id="total-amount">0.00</span></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                    @error('items')
                        <span class="validation-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Button Group -->
=======

                    <div class="form-group">
                        <label class="required">Payment Type</label>
                        <select name="payment_type" required>
                            <option value="">Select Type</option>
                            <option value="CASH" {{ isset($booking) && $booking->payment_type == 'CASH' ? 'selected' : '' }}>Cash</option>
                            <option value="ONLINE" {{ isset($booking) && $booking->payment_type == 'ONLINE' ? 'selected' : '' }}>Online</option>
                            <option value="COD" {{ isset($booking) && $booking->payment_type == 'COD' ? 'selected' : '' }}>COD</option>
                            <option value="SLIP" {{ isset($booking) && $booking->payment_type == 'SLIP' ? 'selected' : '' }}>Slip</option>
                        </select>
                        @error('payment_type')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Slip No</label>
                        <input type="text" name="slip_no" placeholder="Enter slip number"
                            value="{{ isset($booking) ? $booking->slip_no : '' }}">
                    </div>
                </div>

                <!-- Remarks -->
                <div class="row-bottom">
                    <div class="form-group">
                        <label>Remark</label>
                        <textarea name="remark" placeholder="Add remarks" style="min-height: 80px;">{{ isset($booking) ? $booking->remark : '' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Items Section -->
            <div class="form-card items-section">
                <div class="items-header">
                    <h3>📦 Booking Items</h3>
                    <button type="button" class="btn-add-item" id="btn-add-item">+ Add Item</button>
                </div>

                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Tracking No</th>
                            <th>Receiver Name</th>
                            <th>Address</th>
                            <th>PCS</th>
                            <th>Weight (kg)</th>
                            <th>Amount</th>
                            <th style="width: 80px;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="items-body">
                        @if (isset($booking) && $booking->items->count() > 0)
                            @foreach($booking->items as $item)
                                <tr class="item-row" data-item-id="{{ $item->id }}">
                                    <td><input type="text" name="items[tracking_no][]" class="item-input"
                                        value="{{ $item->tracking_no }}" required></td>
                                    <td><input type="text" name="items[receiver_name][]" class="item-input"
                                        value="{{ $item->receiver_name }}" required></td>
                                    <td><input type="text" name="items[address][]" class="item-input"
                                        value="{{ $item->address }}" required></td>
                                    <td><input type="number" name="items[pcs][]" class="item-input item-pcs"
                                        value="{{ $item->pcs }}" min="1" required></td>
                                    <td><input type="number" step="0.01" name="items[weight][]" class="item-input item-weight"
                                        value="{{ $item->weight }}" min="0.01" required></td>
                                    <td><input type="number" step="0.01" name="items[amount][]" class="item-input item-amount"
                                        value="{{ $item->amount }}" min="0" required></td>
                                    <td><button type="button" class="btn-remove-item" onclick="removeItem(this)">Remove</button></td>
                                </tr>
                            @endforeach
                        @else
                            <tr id="empty-row">
                                <td colspan="7" style="text-align: center; color: #999; padding: 40px;">No items added yet</td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr class="totals-row">
                            <td colspan="3">TOTALS</td>
                            <td><strong id="total-pcs">0</strong></td>
                            <td><strong id="total-weight">0.00</strong></td>
                            <td><strong id="total-amount">0.00</strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                @error('items')
                    <span class="validation-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="form-card">
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
                <div class="button-group">
                    <button type="submit" class="btn-custom btn-save">
                        💾 {{ isset($booking) ? 'Update' : 'Create' }} Booking
                    </button>
<<<<<<< HEAD
                    {{-- <button type="button" class="btn-custom btn-selector">🎯 Selector</button>
                    <button type="button" class="btn-custom btn-shipper">📍 Shipper</button> --}}
                    <button type="button" class="btn-custom btn-cancel" id="btn-cancel">❌ Cancel</button>
                </div>
            </form>
        </div>
=======
                    <button type="button" class="btn-custom btn-secondary" id="btn-cancel">❌ Cancel</button>
                </div>
            </div>
        </form>
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
    </div>

    <script>
        const form = document.getElementById('booking-form');
        const isEditMode = {{ isset($booking) ? 'true' : 'false' }};

        function showToast(message, type = 'success') {
            let toastContainer = document.getElementById('toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'toast-container';
                document.body.appendChild(toastContainer);
            }

            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
<<<<<<< HEAD
            toast.textContent = message;
            toastContainer.appendChild(toast);

            setTimeout(() => {
                toast.remove();
=======
            const icon = type === 'success' ? '✓' : '✕';
            toast.innerHTML = `<span>${icon}</span> <span>${message}</span>`;
            toastContainer.appendChild(toast);

            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease-out forwards';
                setTimeout(() => toast.remove(), 300);
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
            }, 4000);
        }

        function calculateTotals() {
            const pcsInputs = document.querySelectorAll('.item-pcs');
            const weightInputs = document.querySelectorAll('.item-weight');
            const amountInputs = document.querySelectorAll('.item-amount');

            let totalPcs = 0, totalWeight = 0, totalAmount = 0;

            pcsInputs.forEach((input, idx) => {
                totalPcs += parseInt(input.value) || 0;
                totalWeight += parseFloat(weightInputs[idx].value) || 0;
                totalAmount += parseFloat(amountInputs[idx].value) || 0;
            });

            document.getElementById('total-pcs').textContent = totalPcs;
            document.getElementById('total-weight').textContent = totalWeight.toFixed(2);
            document.getElementById('total-amount').textContent = totalAmount.toFixed(2);
        }

        function removeItem(btn) {
            const row = btn.closest('.item-row');
            row.remove();

            const body = document.getElementById('items-body');
            if (body.children.length === 0) {
<<<<<<< HEAD
                body.innerHTML = '<tr id="empty-row"><td colspan="7" style="text-align: center; color: #999; padding: 30px;">No items added yet</td></tr>';
=======
                body.innerHTML = '<tr id="empty-row"><td colspan="7" style="text-align: center; color: #999; padding: 40px;">No items added yet</td></tr>';
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
            }
            calculateTotals();
        }

        function addNewItem() {
            const body = document.getElementById('items-body');
            const emptyRow = body.querySelector('#empty-row');
            if (emptyRow) emptyRow.remove();

            const row = document.createElement('tr');
            row.className = 'item-row';
            row.innerHTML = `
                <td><input type="text" name="items[tracking_no][]" class="item-input" placeholder="Tracking No" required></td>
<<<<<<< HEAD
                <td><input type="text" name="items[receiver_name][]" class="item-input" placeholder="Receiver Name"></td>
                <td><input type="text" name="items[address][]" class="item-input" placeholder="Address"></td>
                <td><input type="number" name="items[pcs][]" class="item-input item-pcs" placeholder="0" min="1"></td>
                <td><input type="number" step="0.01" name="items[weight][]" class="item-input item-weight" placeholder="0.00" min="0.01"></td>
                <td><input type="number" step="0.01" name="items[amount][]" class="item-input item-amount" placeholder="0.00" min="0"></td>
=======
                <td><input type="text" name="items[receiver_name][]" class="item-input" placeholder="Receiver Name" required></td>
                <td><input type="text" name="items[address][]" class="item-input" placeholder="Address" required></td>
                <td><input type="number" name="items[pcs][]" class="item-input item-pcs" placeholder="0" min="1" required></td>
                <td><input type="number" step="0.01" name="items[weight][]" class="item-input item-weight" placeholder="0.00" min="0.01" required></td>
                <td><input type="number" step="0.01" name="items[amount][]" class="item-input item-amount" placeholder="0.00" min="0" required></td>
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
                <td><button type="button" class="btn-remove-item" onclick="removeItem(this)">Remove</button></td>
            `;

            body.appendChild(row);
            row.querySelectorAll('.item-pcs, .item-weight, .item-amount').forEach(input => {
                input.addEventListener('change', calculateTotals);
                input.addEventListener('input', calculateTotals);
            });

<<<<<<< HEAD
            const firstInput = row.querySelector('input');
            if (firstInput) firstInput.focus();
            calculateTotals();
        }

        document.getElementById('btn-add-item').addEventListener('click', addNewItem);

=======
            calculateTotals();
        }

        // Add Item Button
        document.getElementById('btn-add-item').addEventListener('click', addNewItem);

        // Cancel Button
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
        document.getElementById('btn-cancel').addEventListener('click', function() {
            if (confirm('Are you sure you want to cancel?')) {
                window.history.back();
            }
        });

<<<<<<< HEAD
=======
        // Form Submit
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const itemRows = document.querySelectorAll('.item-row');
            if (itemRows.length === 0) {
                showToast('Please add at least one item', 'error');
                return;
            }

<<<<<<< HEAD
            let isValid = true;
            itemRows.forEach((row) => {
                const trackingInput = row.querySelector('input[name="items[tracking_no][]"]');
                if (!trackingInput.value.trim()) {
                    trackingInput.style.borderColor = '#dc2626';
                    showToast('Tracking No is required for all items', 'error');
                    isValid = false;
                } else {
                    trackingInput.style.borderColor = '#ccc';
                }
            });

            if (!isValid) return;

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
=======
            const formData = new FormData(form);

            fetch(form.action, {
                method: isEditMode ? 'POST' : 'POST',
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const msg = isEditMode ? 'Booking updated successfully!' : 'Booking created successfully!';
                    showToast(data.message || msg, 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                } else {
<<<<<<< HEAD
                    showToast(data.message || 'Error occurred', 'error');
=======
                    showToast(data.message, 'error');
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
                }
            })
            .catch(error => {
                showToast('Error: ' + error.message, 'error');
                console.error('Error:', error);
            });
        });

<<<<<<< HEAD
=======
        // Attach change listeners to existing items
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
        document.querySelectorAll('.item-pcs, .item-weight, .item-amount').forEach(input => {
            input.addEventListener('change', calculateTotals);
            input.addEventListener('input', calculateTotals);
        });

<<<<<<< HEAD
=======
        // Initial calculation
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
        calculateTotals();
    </script>
@endsection
