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
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 11px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

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
        }

        .items-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }

        .items-table thead {
            background: #f3f4f6;
            border-top: 1px solid #ddd;
            border-bottom: 2px solid #ddd;
        }

        .items-table th {
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
            border-bottom: 1px solid #f0f0f0;
        }

        .items-table tbody tr:hover {
            background: #f9f9f9;
        }

        .item-input {
            width: 100%;
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
            color: white;
        }

        .btn-save:hover {
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
            color: #333;
            border: 1px solid #ddd;
        }

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

            .button-group {
                flex-direction: column;
            }

            .btn-custom {
                width: 100%;
            }
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
                        @error('network')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
                    </div>
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
                <div class="button-group">
                    <button type="submit" class="btn-custom btn-save">
                        💾 {{ isset($booking) ? 'Update' : 'Create' }} Booking
                    </button>
                    {{-- <button type="button" class="btn-custom btn-selector">🎯 Selector</button>
                    <button type="button" class="btn-custom btn-shipper">📍 Shipper</button> --}}
                    <button type="button" class="btn-custom btn-cancel" id="btn-cancel">❌ Cancel</button>
                </div>
            </form>
        </div>
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
            toast.textContent = message;
            toastContainer.appendChild(toast);

            setTimeout(() => {
                toast.remove();
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
                body.innerHTML = '<tr id="empty-row"><td colspan="7" style="text-align: center; color: #999; padding: 30px;">No items added yet</td></tr>';
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
                <td><input type="text" name="items[receiver_name][]" class="item-input" placeholder="Receiver Name"></td>
                <td><input type="text" name="items[address][]" class="item-input" placeholder="Address"></td>
                <td><input type="number" name="items[pcs][]" class="item-input item-pcs" placeholder="0" min="1"></td>
                <td><input type="number" step="0.01" name="items[weight][]" class="item-input item-weight" placeholder="0.00" min="0.01"></td>
                <td><input type="number" step="0.01" name="items[amount][]" class="item-input item-amount" placeholder="0.00" min="0"></td>
                <td><button type="button" class="btn-remove-item" onclick="removeItem(this)">Remove</button></td>
            `;

            body.appendChild(row);
            row.querySelectorAll('.item-pcs, .item-weight, .item-amount').forEach(input => {
                input.addEventListener('change', calculateTotals);
                input.addEventListener('input', calculateTotals);
            });

            const firstInput = row.querySelector('input');
            if (firstInput) firstInput.focus();
            calculateTotals();
        }

        document.getElementById('btn-add-item').addEventListener('click', addNewItem);

        document.getElementById('btn-cancel').addEventListener('click', function() {
            if (confirm('Are you sure you want to cancel?')) {
                window.history.back();
            }
        });

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const itemRows = document.querySelectorAll('.item-row');
            if (itemRows.length === 0) {
                showToast('Please add at least one item', 'error');
                return;
            }

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
                    showToast(data.message || 'Error occurred', 'error');
                }
            })
            .catch(error => {
                showToast('Error: ' + error.message, 'error');
                console.error('Error:', error);
            });
        });

        document.querySelectorAll('.item-pcs, .item-weight, .item-amount').forEach(input => {
            input.addEventListener('change', calculateTotals);
            input.addEventListener('input', calculateTotals);
        });

        calculateTotals();
    </script>
@endsection
