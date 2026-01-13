<!-- Updated drs-entry.blade.php with multiple shipments support -->

@extends('backend.partials.master')

@section('title')
    DRS Entry - {{ __('levels.add') }}
@endsection

@section('maincontent')
<style>
    .drs-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
    }

    .drs-header h2 {
        font-size: 28px;
        margin-bottom: 8px;
        font-weight: 700;
    }

    .drs-header p {
        font-size: 14px;
        opacity: 0.95;
    }

    .drs-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: box-shadow 0.3s ease;
        margin-bottom: 20px;
    }

    .drs-card:hover {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    }

    .drs-card .card-body {
        padding: 25px;
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #667eea;
        display: inline-block;
    }

    .form-control,
    .form-control:focus {
        border-radius: 8px;
        border: 2px solid #e0e0e0;
        padding: 12px 15px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    label {
        font-weight: 600;
        color: #444;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .required::after {
        content: " *";
        color: #ef4444;
    }

    .button-group {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        flex-wrap: wrap;
        margin-top: 30px;
    }

    .btn-custom {
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 13px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    .btn-secondary-custom {
        background: #f0f0f0;
        color: #333;
    }

    .btn-secondary-custom:hover {
        background: #e0e0e0;
    }

    .btn-success-custom {
        background: #10b981;
        color: white;
    }

    .btn-success-custom:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
    }

    .btn-info-custom {
        background: #3b82f6;
        color: white;
    }

    .btn-info-custom:hover {
        background: #2563eb;
        transform: translateY(-2px);
    }

    .btn-danger-custom {
        background: #ef4444;
        color: white;
        padding: 8px 16px;
        font-size: 12px;
    }

    .btn-danger-custom:hover {
        background: #dc2626;
        transform: translateY(-2px);
    }

    .btn-add-shipment {
        background: #10b981;
        color: white;
        margin-top: 15px;
    }

    .btn-add-shipment:hover {
        background: #059669;
    }

    /* Shipments Table Styles */
    .shipments-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .shipments-table thead {
        background: #667eea;
        color: white;
    }

    .shipments-table th,
    .shipments-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #e0e0e0;
        font-size: 13px;
    }

    .shipments-table tbody tr:hover {
        background: #f5f5f5;
    }

    .shipment-row {
        display: none;
    }

    .shipment-row.active {
        display: table-row;
    }

    .shipment-row input,
    .shipment-row select {
        width: 100%;
        padding: 8px;
        border-radius: 6px;
        border: 1px solid #ddd;
        font-size: 12px;
    }

    .shipment-row input:focus,
    .shipment-row select:focus {
        border-color: #667eea;
        outline: none;
        box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
    }

    .remove-row-btn {
        padding: 4px 8px;
        font-size: 11px;
    }

    .shipment-counter {
        display: inline-block;
        background: #667eea;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        margin-left: 10px;
    }

    /* Toast Styles */
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
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .toast {
        background: #10b981;
        color: white;
        padding: 16px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
        animation: slideIn 0.3s ease-out;
        min-width: 300px;
    }

    .toast.error {
        background: #ef4444;
    }

    .form-row-spacing {
        margin-bottom: 20px;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .chart-section {
        margin-top: 40px;
        padding: 30px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    .chart-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 20px;
        color: #333;
    }

    @media (max-width: 768px) {
        .button-group {
            flex-direction: column;
        }

        .btn-custom {
            width: 100%;
        }

        .shipments-table {
            font-size: 12px;
        }

        .shipments-table th,
        .shipments-table td {
            padding: 8px;
        }
    }
</style>

<div class="container-fluid dashboard-content">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="drs-header">
                <h2>📦 DRS (Delivery Run Sheet) Entry</h2>
                <p>Add new delivery run sheet with multiple shipments</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <form id="drs-form" action="{{ route('drs.store') }}" method="POST">
                @csrf

                <!-- Basic Information Section -->
                <div class="drs-card">
                    <div class="card-body">
                        <div class="section-title">📋 Basic Information</div>
                        <div class="row form-row-spacing">
                            <div class="col-md-4">
                                <label class="required">DRS No</label>
                                <input type="text" name="drs_no" class="form-control" placeholder="Enter DRS number" required>
                            </div>
                            <div class="col-md-4">
                                <label class="required">Date</label>
                                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="required">Time</label>
                                <input type="time" name="time" class="form-control" value="{{ date('H:i') }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipments Section -->
                <div class="drs-card">
                    <div class="card-body">
                        <div class="section-title">
                            🚚 Shipments
                            <span class="shipment-counter" id="shipment-count">0 added</span>
                        </div>

                        <table class="shipments-table">
                            <thead>
                                <tr>
                                    <th width="8%">#</th>
                                    <th width="12%">Tracking No</th>
                                    <th width="10%">Pincode</th>
                                    <th width="10%">Area</th>
                                    <th width="12%">Receiver</th>
                                    <th width="8%">Del. Boy</th>
                                    <th width="8%">Weight</th>
                                    <th width="6%">Pcs</th>
                                    <th width="8%">Action</th>
                                </tr>
                            </thead>
                            <tbody id="shipments-tbody">
                                <!-- Rows will be inserted here -->
                            </tbody>
                        </table>

                        <button type="button" class="btn-custom btn-add-shipment" id="btn-add-shipment">
                            + Add Shipment
                        </button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="button-group">
                    <button type="button" class="btn-custom btn-secondary-custom" id="btn-cancel">Cancel</button>
                    <button type="button" class="btn-custom btn-info-custom" id="btn-print">Print</button>
                    <button type="submit" class="btn-custom btn-primary-custom" id="btn-save">Save DRS</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function selectArea(el, area) {
        const container = el.closest('[style*="position:relative"]');
        const input = container.querySelector('.area-input');
        input.value = area;
        const suggestions = container.querySelector('.area-suggestions');
        suggestions.style.display = 'none';
    }

    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('drs-form');
        let shipmentCount = 0;
        const deliveryBoys = @json($deliveryBoys);
        const areas = @json($areas);

        function selectArea(el, area) {
            const container = el.closest('[style*="position:relative"]');
            const input = container.querySelector('.area-input');
            input.value = area;
            const suggestions = container.querySelector('.area-suggestions');
            suggestions.style.display = 'none';
        }

        console.log('Script loaded. Delivery boys:', deliveryBoys.length);

        // Toast Notification
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

        // Create shipment row
        function createShipmentRow(index) {
            const row = document.createElement('tr');
            row.className = 'shipment-row active';
            row.dataset.index = index;
            row.innerHTML = `
                <td>${index + 1}</td>
                <td>
                    <input type="text" name="shipments[${index}][tracking_no]" placeholder="Tracking #" required>
                </td>
                <td>
                    <input type="text" name="shipments[${index}][pincode]" placeholder="Pincode" required>
                </td>
                <td>
                    <div style="position:relative;">
                        <input type="text" name="shipments[${index}][area]" placeholder="Area" class="area-input" required>
                        <div class="area-suggestions" style="position:absolute; top:100%; left:0; width: 250px; background:white; border:1px solid #ccc; max-height:150px; overflow-y:auto; display:none; z-index:1000;"></div>
                    </div>
                </td>
                <td>
                    <input type="text" name="shipments[${index}][receiver_name]" placeholder="Receiver Name" required>
                </td>
                <td>
                    <select name="shipments[${index}][delivery_boy_id]" required>
                        <option value="">Select</option>
                        ${deliveryBoys.map(boy => `<option value="${boy.id}">${boy.user?.name ?? 'N/A'}</option>`).join('')}
                    </select>
                </td>
                <td>
                    <input type="number" name="shipments[${index}][weight]" placeholder="Weight" step="0.01">
                </td>
                <td>
                    <input type="number" name="shipments[${index}][pieces]" value="1" min="1" required>
                </td>
                <td>
                    <button type="button" class="btn-custom btn-danger-custom remove-row-btn" onclick="removeShipmentRow(${index})">
                        Remove
                    </button>
                </td>
            `;

            // Add autocomplete for area
            const areaInput = row.querySelector('.area-input');
            const suggestionsDiv = row.querySelector('.area-suggestions');
            areaInput.addEventListener('input', function() {
                const value = this.value.toLowerCase();
                const filtered = areas.filter(area => area.toLowerCase().startsWith(value));
                suggestionsDiv.innerHTML = filtered.map(area => `<div style="padding:5px; cursor:pointer; border-bottom:1px solid #eee;" onclick="selectArea(this, '${area.replace(/'/g, "\\'")}')">${area}</div>`).join('');
                suggestionsDiv.style.display = filtered.length && value ? 'block' : 'none';
            });
            areaInput.addEventListener('blur', function() {
                setTimeout(() => suggestionsDiv.style.display = 'none', 200);
            });

            return row;
        }

        // Remove shipment row
        window.removeShipmentRow = function(index) {
            const row = document.querySelector(`tr[data-index="${index}"]`);
            if (row) {
                row.remove();
                updateShipmentCount();
                console.log('Row removed. Remaining:', document.querySelectorAll('.shipment-row.active').length);
            }
        };

        // Update shipment counter
        function updateShipmentCount() {
            const count = document.querySelectorAll('.shipment-row.active').length;
            document.getElementById('shipment-count').textContent = count + ' added';
        }

        // Add shipment button handler
        const addBtn = document.getElementById('btn-add-shipment');
        if (addBtn) {
            addBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Add button clicked');
                const tbody = document.getElementById('shipments-tbody');
                tbody.appendChild(createShipmentRow(shipmentCount));
                shipmentCount++;
                updateShipmentCount();
            });
        } else {
            console.error('Add button not found');
        }

        // Form Submit
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const rows = document.querySelectorAll('.shipment-row.active');
                if (rows.length === 0) {
                    showToast('Please add at least one shipment', 'error');
                    return;
                }

                const formData = new FormData(form);

                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw new Error(JSON.stringify(data));
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        showToast(data.message, 'success');
                        form.reset();
                        document.getElementById('shipments-tbody').innerHTML = '';
                        shipmentCount = 0;
                        updateShipmentCount();
                        // Redirect to index page
                        window.location.href = data.redirect;
                    })
                    .catch(error => {
                        try {
                            const errorData = JSON.parse(error.message);
                            const messages = Object.values(errorData.errors || {}).flat().join(', ');
                            showToast(messages || 'Validation failed', 'error');
                        } catch {
                            showToast('An error occurred', 'error');
                        }
                        console.error('Error:', error);
                    });
            });
        }

        // Cancel Button
        const cancelBtn = document.getElementById('btn-cancel');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                if (confirm('Are you sure?')) {
                    window.history.back();
                }
            });
        }

        // Print Button
        const printBtn = document.getElementById('btn-print');
        if (printBtn) {
            printBtn.addEventListener('click', function() {
                window.print();
            });
        }
    });
</script>
@endpush
