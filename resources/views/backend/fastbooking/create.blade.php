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
        }

        .row-two-cols {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }

        .row-full {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 12px;
            font-weight: 700;
            color: #555;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        label.required::after {
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
            width: 100%;
            box-sizing: border-box;
        }

        select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 20px;
            padding-right: 36px;
            cursor: pointer;
        }

        select option {
            padding: 8px;
            line-height: 1.5;
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

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        /* Items Table */
        .items-section {
            margin-top: 0;
            padding-top: 0;
            border-top: none;
        }

        .items-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table thead {
            background: #f8f9fa;
            border-bottom: 2px solid #ddd;
        }

        .items-table th {
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
            border-bottom: 1px solid #f0f0f0;
        }

        .items-table tbody tr:hover {
            background: #f9f9f9;
        }

        .item-input {
            width: 100%;
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
            margin-top: 0;
            padding-top: 0;
            border-top: none;
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
            color: white;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
            border: 1px solid #ddd;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
        }

        .totals-row {
            background: #f8f9fa;
            font-weight: 700;
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

        @media (max-width: 1024px) {
            .row-content {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {

            .row-content,
            .row-two-cols,
            .row-full {
                grid-template-columns: 1fr;
            }

            .button-group {
                flex-direction: column;
            }

            .btn-custom {
                width: 100%;
            }

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

            <!-- Booking Details Section -->
            <div class="form-card">
                <!-- Row 1: Booking No, From Branch, To Branch -->
                <div class="row-content">
                    <div class="form-group">
                        <label class="required">Booking No</label>
                        <input type="text" name="booking_no" placeholder="Enter booking number"
                            value="{{ isset($booking) ? $booking->booking_no : '' }}">
                        @error('booking_no')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="">From Branch</label>
                        <select name="from_branch_id">
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
                        <label class="">To Branch</label>
                        <select name="to_branch_id">
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

                <!-- Row 2: Network, Payment Type -->
                <div class="row-two-cols">
                    <div class="form-group">
                        <label class="">Network</label>
                        <select name="network">
                            <option value="">Select Network</option>
                            @foreach ($networks as $network)
                                <option value="{{ $network }}"
                                    {{ isset($booking) && $booking->network == $network ? 'selected' : '' }}>
                                    {{ $network }}
                                </option>
                            @endforeach
                        </select>
                        @error('network')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="">Payment Type</label>
                        <select name="payment_type">
                            <option value="">Select Type</option>
                            <option value="CASH"
                                {{ isset($booking) && $booking->payment_type == 'CASH' ? 'selected' : '' }}>
                                Cash</option>
                            <option value="ONLINE"
                                {{ isset($booking) && $booking->payment_type == 'ONLINE' ? 'selected' : '' }}>Online
                            </option>
                            <option value="COD"
                                {{ isset($booking) && $booking->payment_type == 'COD' ? 'selected' : '' }}>
                                COD</option>
                            <option value="SLIP"
                                {{ isset($booking) && $booking->payment_type == 'SLIP' ? 'selected' : '' }}>
                                Slip</option>
                        </select>
                        @error('payment_type')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Row 3: Forwarding No, E-Way Bill No -->
                <div class="row-two-cols">
                    <div class="form-group">
                        <label>Forwarding No</label>
                        <input type="text" name="forwarding_no" placeholder="Auto / Enter at dispatch time"
                            value="{{ isset($booking) ? $booking->forwarding_no : '' }}">
                        @error('forwarding_no')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>E-Way Bill No</label>
                        <input type="text" name="eway_bill_no" placeholder="Enter E-Way Bill Number"
                            value="{{ isset($booking) ? $booking->eway_bill_no : '' }}">
                        @error('eway_bill_no')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Row 4: Remark (Full Width) -->
                <div class="row-full">
                    <div class="form-group">
                        <label>Remark</label>
                        <textarea name="remark" placeholder="Add remarks">{{ isset($booking) ? $booking->remark : '' }}</textarea>
                        @error('remark')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
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
                            @foreach ($booking->items as $item)
                                <tr class="item-row" data-item-id="{{ $item->id }}">
                                    <td><input type="text" name="items[tracking_no][]" class="item-input"
                                            value="{{ $item->tracking_no }}"></td>
                                    <td><input type="text" name="items[receiver_name][]" class="item-input"
                                            value="{{ $item->receiver_name }}"></td>
                                    <td><input type="text" name="items[address][]" class="item-input"
                                            value="{{ $item->address }}"></td>
                                    <td><input type="number" name="items[pcs][]" class="item-input item-pcs"
                                            value="{{ $item->pcs }}" min="1"></td>
                                    <td><input type="number" step="0.01" name="items[weight][]"
                                            class="item-input item-weight" value="{{ $item->weight }}" min="0.01">
                                    </td>
                                    <td><input type="number" step="0.01" name="items[amount][]"
                                            class="item-input item-amount" value="{{ $item->amount }}" min="0">
                                    </td>
                                    <td><button type="button" class="btn-remove-item"
                                            onclick="removeItem(this)">Remove</button></td>
                                </tr>
                            @endforeach
                        @else
                            <tr id="empty-row">
                                <td colspan="7" style="text-align: center; color: #999; padding: 40px;">No items added
                                    yet</td>
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
                <div class="button-group">
                    <button type="submit" class="btn-custom btn-save">
                        💾 {{ isset($booking) ? 'Update' : 'Create' }} Booking
                    </button>
                    <button type="button" class="btn-custom btn-secondary" id="btn-cancel">❌ Cancel</button>
                </div>
            </div>
        </form>
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
            const icon = type === 'success' ? '✓' : '✕';
            toast.innerHTML = `<span>${icon}</span> <span>${message}</span>`;
            toastContainer.appendChild(toast);

            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease-out forwards';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        function calculateTotals() {
            const pcsInputs = document.querySelectorAll('.item-pcs');
            const weightInputs = document.querySelectorAll('.item-weight');
            const amountInputs = document.querySelectorAll('.item-amount');

            let totalPcs = 0,
                totalWeight = 0,
                totalAmount = 0;

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
                body.innerHTML =
                    '<tr id="empty-row"><td colspan="7" style="text-align: center; color: #999; padding: 40px;">No items added yet</td></tr>';
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
                <td><input type="text" name="items[tracking_no][]" class="item-input" placeholder="Tracking No" ></td>
                <td><input type="text" name="items[receiver_name][]" class="item-input" placeholder="Receiver Name" ></td>
                <td><input type="text" name="items[address][]" class="item-input" placeholder="Address" ></td>
                <td><input type="number" name="items[pcs][]" class="item-input item-pcs" placeholder="0" min="1" ></td>
                <td><input type="number" step="0.01" name="items[weight][]" class="item-input item-weight" placeholder="0.00" min="0.01" ></td>
                <td><input type="number" step="0.01" name="items[amount][]" class="item-input item-amount" placeholder="0.00" min="0" ></td>
                <td><button type="button" class="btn-remove-item" onclick="removeItem(this)">Remove</button></td>
            `;

            body.appendChild(row);
            row.querySelectorAll('.item-pcs, .item-weight, .item-amount').forEach(input => {
                input.addEventListener('change', calculateTotals);
                input.addEventListener('input', calculateTotals);
            });

            calculateTotals();
        }

        // Add Item Button
        document.getElementById('btn-add-item').addEventListener('click', addNewItem);

        // Cancel Button
        document.getElementById('btn-cancel').addEventListener('click', function() {
            if (confirm('Are you sure you want to cancel?')) {
                window.history.back();
            }
        });

        // Form Submit
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const itemRows = document.querySelectorAll('.item-row');
            if (itemRows.length === 0) {
                showToast('Please add at least one item', 'error');
                return;
            }

            const formData = new FormData(form);

            fetch(form.action, {
                    method: isEditMode ? 'POST' : 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const msg = isEditMode ? 'Booking updated successfully!' :
                            'Booking created successfully!';
                        showToast(data.message || msg, 'success');
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 1500);
                    } else {
                        showToast(data.message, 'error');
                    }
                })
                .catch(error => {
                    showToast('Error: ' + error.message, 'error');
                    console.error('Error:', error);
                });
        });

        // Attach change listeners to existing items
        document.querySelectorAll('.item-pcs, .item-weight, .item-amount').forEach(input => {
            input.addEventListener('change', calculateTotals);
            input.addEventListener('input', calculateTotals);
        });

        // Initial calculation
        calculateTotals();
    </script>
@endsection
