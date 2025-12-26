@extends('backend.partials.master')

@section('title')
    {{ __('hub_payment_request.title') }} {{ __('levels.add') }}
@endsection

@section('maincontent')
    <div class="container-fluid dashboard-content">

        <!-- Page Header -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="page-title">{{ __('hub_payment_request.submit_request') }}</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard.index') }}">{{ __('levels.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('levels.create') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <strong>{{ __('hub_payment_request.submit_request') }}</strong>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('hub-panel.payment-request.store_branch') }}" method="POST">
                            @csrf

                            <!-- Request Type Selection -->
                            <div class="form-group">
                                <label>{{ __('Request Type') }} <span class="text-danger">*</span></label>
                                <div class="mt-2">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="type_out" name="request_type" value="out"
                                            class="custom-control-input" required>
                                        <label class="custom-control-label" for="type_out">OUT</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="type_in" name="request_type" value="in"
                                            class="custom-control-input" required>
                                        <label class="custom-control-label" for="type_in">IN</label>
                                    </div>
                                </div>
                                @error('request_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- <div class="form-group">
                                <label for="vehicle_no">Vehicle No</label>
                                <input type="text" name="vehicle_no" id="vehicle_no" class="form-control"
                                    placeholder="e.g. UP32 AB 1234" value="{{ old('vehicle_no') }}">
                            </div> --}}

                            <div class="form-group" id="vehicle-container" style="display:none;">
                                <label for="vehicle_number" id="vehicle-label">Vehicle No<span
                                        class="text-danger">*</span></label>
                                <input type="text" id="vehicle_number" name="vehicle_no" class="form-control"
                                    placeholder="Enter vehicle number" required>
                                <small class="form-text text-muted" id="vehicle-hint"></small>
                            </div>


                            <!-- Branch Selection Row (Left: From, Right: To) -->
                            <div class="row" id="branch-row" style="display:none;">
                                <!-- From Branch (Left) -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="from_branch_select" id="from-branch-label">
                                            Branch From <span class="text-danger">*</span>
                                        </label>
                                        <select id="from_branch_select" name="from_branch_id"
                                            class="form-control @error('from_branch_id') is-invalid @enderror">
                                            <option value="">Select Branch</option>
                                            @if (isset($branches) && $branches->count() > 0)
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}"
                                                        data-city="{{ $branch->city ?? '' }}"
                                                        data-state="{{ $branch->state ?? '' }}"
                                                        {{ old('from_branch_id') == $branch->id ? 'selected' : '' }}>
                                                        {{ $branch->name }}
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="">No branches found</option>
                                            @endif
                                        </select>
                                        @error('from_branch_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- To Branch (Right) -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="to_branch_id" id="to-branch-label">
                                            To Branch <span class="text-danger">*</span>
                                        </label>
                                        <select id="to_branch_id" name="to_branch_id"
                                            class="form-control @error('to_branch_id') is-invalid @enderror">
                                            <option value="">Select Branch</option>
                                            @if (isset($branches) && $branches->count() > 0)
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}"
                                                        data-city="{{ $branch->city ?? '' }}"
                                                        data-state="{{ $branch->state ?? '' }}"
                                                        {{ old('to_branch_id') == $branch->id ? 'selected' : '' }}>
                                                        {{ $branch->name }}
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="">No branches found</option>
                                            @endif
                                        </select>
                                        @error('to_branch_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Item Type Selection -->
                            <div class="form-group" id="item-type-container" style="display:none;">
                                <label>{{ __('Item Type') }} <span class="text-danger">*</span></label>
                                <div class="mt-2">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="item_document" name="item_type" value="document"
                                            class="custom-control-input" required>
                                        <label class="custom-control-label" for="item_document">Document</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="item_parcel" name="item_type" value="parcel"
                                            class="custom-control-input" required>
                                        <label class="custom-control-label" for="item_parcel">Parcel</label>
                                    </div>
                                </div>
                                @error('item_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Transport Type -->
                            <div class="form-group" id="transport-type-container" style="display:none;">
                                <label for="transport_type">{{ __('Transport Type') }} <span
                                        class="text-danger">*</span></label>
                                <select name="transport_type" id="transport_type"
                                    class="form-control @error('transport_type') is-invalid @enderror" required>
                                    <option value="">{{ __('Select Transport Type') }}</option>
                                    <option value="by_road" {{ old('transport_type') == 'by_road' ? 'selected' : '' }}>
                                        {{ __('By Road') }}
                                    </option>
                                    <option value="by_air" {{ old('transport_type') == 'by_air' ? 'selected' : '' }}>
                                        {{ __('By Air') }}
                                    </option>
                                </select>
                                @error('transport_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Tracking Number -->
                            <div class="form-group" id="tracking-container" style="display:none;">
                                <label for="tracking_number" id="tracking-label">Tracking / Consignment No <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="tracking_number" name="tracking_number" class="form-control"
                                    placeholder="Enter or auto-generated tracking number" required>
                                <small class="form-text text-muted" id="tracking-hint"></small>
                            </div>

                            <!-- City & State Row -->
                            <div class="row" id="location-row" style="display:none;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="city">City <span class="text-danger">*</span></label>
                                        <input type="text" id="city" name="city"
                                            class="form-control @error('city') is-invalid @enderror" placeholder="City"
                                            required readonly>
                                        @error('city')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="state">State <span class="text-danger">*</span></label>
                                        <input type="text" id="state" name="state"
                                            class="form-control @error('state') is-invalid @enderror" placeholder="State"
                                            required readonly>
                                        @error('state')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Weight & Quantity Row -->
                            <div class="row" id="weight-quantity-row" style="display:none;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="unit">Weight Unit<span class="text-danger">*</span></label>
                                        <select id="unit" name="unit"
                                            class="form-control @error('unit') is-invalid @enderror" required>
                                            <option value="">Select Unit</option>
                                            <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram
                                                (kg)</option>
                                            <option value="gram" {{ old('unit') == 'gram' ? 'selected' : '' }}>Gram (g)
                                            </option>
                                            <option value="liter" {{ old('unit') == 'liter' ? 'selected' : '' }}>Liter
                                                (L)</option>
                                            <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>Milliliter
                                                (ml)</option>
                                        </select>
                                        @error('unit')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="quantity">Quantity <span class="text-danger">*</span></label>
                                        <input type="number" id="quantity" name="quantity"
                                            class="form-control @error('quantity') is-invalid @enderror"
                                            placeholder="Enter Quantity" value="{{ old('quantity') }}" required>
                                        @error('quantity')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Amount -->
                            <div class="form-group" id="amount-container" style="display:none;">
                                <label for="amount">{{ __('hub_payment.amount') }} <span
                                        class="text-danger">*</span></label>
                                <input type="number" step="0.01" id="amount" name="amount"
                                    class="form-control @error('amount') is-invalid @enderror"
                                    placeholder="{{ __('Enter Amount') }}" value="{{ old('amount') }}" required>
                                @error('amount')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- GST Checkbox -->
                            <div class="form-group" id="gst-checkbox-container" style="display:none;">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="include_gst"
                                        name="include_gst" {{ old('include_gst') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="include_gst">
                                        Include GST (CGST & SGST)
                                    </label>
                                </div>
                            </div>

                            <!-- GST Fields -->
                            <div id="gst-fields" style="display: none;">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="cgst">CGST (%) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" id="cgst" name="cgst"
                                            class="form-control @error('cgst') is-invalid @enderror"
                                            placeholder="Enter CGST %" value="{{ old('cgst') }}">
                                        @error('cgst')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="sgst">SGST (%) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" id="sgst" name="sgst"
                                            class="form-control @error('sgst') is-invalid @enderror"
                                            placeholder="Enter SGST %" value="{{ old('sgst') }}">
                                        @error('sgst')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="total_with_gst">Total Amount with GST</label>
                                    <input type="text" id="total_with_gst" class="form-control" readonly>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="form-group" id="description-container" style="display:none;">
                                <label for="description">{{ __('hub_payment.description') }} <span
                                        class="text-danger">*</span></label>
                                <textarea name="description" id="description" rows="4"
                                    class="form-control @error('description') is-invalid @enderror" placeholder="{{ __('Enter Description') }}"
                                    required>{{ old('description') }}</textarea>
                                @error('description')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="text-right mt-4">
                                <button type="submit" class="btn btn-success px-4">{{ __('Submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let currentRequestType = '';

            // Request Type Change Handler
            $('input[name="request_type"]').on('change', function() {
                currentRequestType = $(this).val();
                resetAllFields();

                if (currentRequestType === 'out') {
                    handleOutRequest();
                } else if (currentRequestType === 'in') {
                    handleInRequest();
                }
            });

            // OUT Request Flow
            function handleOutRequest() {
                $('#from-branch-label').html('Branch From <span class="text-danger">*</span>');
                $('#to-branch-label').html('To Branch <span class="text-danger">*</span>');
                $('#branch-row').show();
                $('#item-type-container').show();
                $('#transport-type-container').show();
                $('#tracking-container').show();
                $('#vehicle-container').show();
                $('#tracking-label').html('Tracking / Consignment No <span class="text-danger">*</span>');
                $('#vehicle-label').html('Vehicle No <span class="text-danger">*</span>');
                generateTrackingNumber();
                $('#tracking-hint').text('Auto-generated tracking number');
                $('#location-row').show();
                $('#weight-quantity-row').show();
                $('#amount-container').show();
                $('#gst-checkbox-container').show();
                $('#description-container').show();
            }

            // IN Request Flow
            function handleInRequest() {
                $('#from-branch-label').html('From Branch <span class="text-danger">*</span>');
                $('#to-branch-label').html('Receive Branch <span class="text-danger">*</span>');
                $('#branch-row').show();
                $('#item-type-container').show();
                $('#transport-type-container').show();
                $('#tracking-container').show();
                $('#vehicle-container').show();
                $('#tracking-label').html('Tracking / Consignment No <span class="text-danger">*</span>');
                $('#vehicle-label').html('Vehicle No <span class="text-danger">*</span>');
                $('#tracking_number').prop('readonly', false).val('');
                $('#vehicle_number').prop('', false).val('');
                $('#tracking-hint').text('Enter tracking number to auto-fill all details');
                $('#location-row').show();
                $('#city').prop('readonly', true);
                $('#state').prop('readonly', true);
                $('#weight-quantity-row').show();
                $('#amount-container').show();
                $('#gst-checkbox-container').show();
                $('#description-container').show();
            }

            // ========== OUT REQUEST HANDLERS ==========

            // From Branch Change (OUT)
            $('#from_branch_select').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const branchId = $(this).val();
                const city = selectedOption.data('city');
                const state = selectedOption.data('state');

                if (currentRequestType === 'out' && branchId) {
                    // Auto-fill city and state
                    if (city) $('#city').val(city);
                    if (state) $('#state').val(state);

                    // Generate tracking number
                    generateTrackingNumber();

                    // Fetch rates if all required fields are selected
                    const itemType = $('input[name="item_type"]:checked').val();
                    const transportType = $('#transport_type').val();

                    if (itemType && transportType) {
                        fetchOutRates(branchId, itemType, transportType);
                    }
                }
            });

            // Item Type Change (OUT)
            $('input[name="item_type"]').on('change', function() {
                const itemType = $(this).val();

                if (currentRequestType === 'out') {
                    // Set default unit and quantity
                    setDefaultUnitAndQuantity(itemType);

                    // Fetch rates if all required fields are selected
                    const branchId = $('#from_branch_select').val();
                    const transportType = $('#transport_type').val();

                    if (branchId && transportType) {
                        fetchOutRates(branchId, itemType, transportType);
                    }
                }
            });

            // Transport Type Change (OUT)
            $('#transport_type').on('change', function() {
                const transportType = $(this).val();

                if (currentRequestType === 'out') {
                    // Fetch rates if all required fields are selected
                    const branchId = $('#from_branch_select').val();
                    const itemType = $('input[name="item_type"]:checked').val();

                    if (branchId && itemType) {
                        fetchOutRates(branchId, itemType, transportType);
                    }
                }
            });

            // Set Default Unit and Quantity based on Item Type
            function setDefaultUnitAndQuantity(itemType) {
                if (itemType === 'document') {
                    $('#unit').val('gram');
                    $('#quantity').val('100');
                } else if (itemType === 'parcel') {
                    $('#unit').val('kg');
                    $('#quantity').val('1');
                }
            }

            // Fetch OUT Rates
            function fetchOutRates(branchId, itemType, transportType) {
                // Validate all parameters
                if (!branchId || !itemType || !transportType) {
                    console.warn('Missing parameters for rate fetch');
                    return;
                }

                showNotification('info', 'Fetching rates...');

                $.ajax({
                    url: "{{ route('get.branch.rates', '') }}/" + branchId,
                    type: "GET",
                    data: {
                        item_type: itemType,
                        transport_type: transportType
                    },
                    success: function(response) {
                        console.log('Rates Response:', response);

                        if (response.success && response.data) {
                            // Auto-fill amount (rate)
                            if (response.data.rate) {
                                $('#amount').val(response.data.rate);
                            }

                            // Auto-fill description if available
                            if (response.data.description) {
                                $('#description').val(response.data.description);
                            }

                            // Handle GST fields
                            if (response.data.cgst || response.data.sgst) {
                                $('#include_gst').prop('checked', true);
                                $('#gst-fields').show();
                                $('#cgst').val(response.data.cgst || 0).attr('required', 'required');
                                $('#sgst').val(response.data.sgst || 0).attr('required', 'required');
                                calculateGST();
                            }

                            showNotification('success', 'Rates loaded successfully!');
                        } else {
                            showNotification('info', 'No rates found for this combination.');
                            $('#amount').val('');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error fetching rates:', xhr.responseText);
                        showNotification('error', 'Failed to fetch rates. Please enter manually.');
                        $('#amount').val('');
                    }
                });
            }

            // ========== IN REQUEST HANDLERS ==========

            // Tracking Number Change (IN) - AUTO-FILL EVERYTHING
            $('#tracking_number').on('blur', function() {
                const trackingNo = $(this).val().trim();

                if (currentRequestType === 'in' && trackingNo) {
                    fetchInDataByTracking(trackingNo);
                }
            });

            // Fetch IN data by tracking number (Auto-fill EVERYTHING)
            function fetchInDataByTracking(trackingNo) {
                showNotification('info', 'Fetching data from tracking number...');

                $.ajax({
                    url: "{{ route('get.request.by.tracking', '') }}/" + encodeURIComponent(trackingNo),
                    type: "GET",
                    success: function(response) {
                        console.log('Tracking Response:', response);

                        if (response.success && response.data) {
                            // Auto-fill FROM branch
                            if (response.data.from_branch_id) {
                                $('#from_branch_select').val(response.data.from_branch_id);
                            }

                            // Auto-fill TO branch (Receive Branch)
                            if (response.data.to_branch_id) {
                                $('#to_branch_id').val(response.data.to_branch_id);
                            }

                            // Auto-fill city and state
                            if (response.data.city) {
                                $('#city').val(response.data.city);
                            }
                            if (response.data.state) {
                                $('#state').val(response.data.state);
                            }

                            // Auto-fill item type
                            if (response.data.item_type) {
                                $('input[name="item_type"][value="' + response.data.item_type + '"]')
                                    .prop('checked', true);
                            }

                            // Auto-fill transport type
                            if (response.data.transport_type) {
                                $('#transport_type').val(response.data.transport_type);
                            }

                            // Auto-fill unit and quantity
                            if (response.data.unit) {
                                $('#unit').val(response.data.unit);
                            }
                            if (response.data.quantity) {
                                $('#quantity').val(response.data.quantity);
                            }

                            // Auto-fill amount
                            if (response.data.amount) {
                                $('#amount').val(response.data.amount);
                            }

                            // Auto-fill description
                            if (response.data.description) {
                                $('#description').val(response.data.description);
                            }

                            if (response.data.vehicle_no) {
                                $('#vehicle_number').val(response.data.vehicle_no);
                            }

                            // Auto-fill GST fields
                            if (response.data.cgst || response.data.sgst) {
                                $('#include_gst').prop('checked', true);
                                $('#gst-fields').show();
                                $('#cgst').val(response.data.cgst || 0).attr('required', 'required');
                                $('#sgst').val(response.data.sgst || 0).attr('required', 'required');
                                calculateGST();
                            } else {
                                $('#include_gst').prop('checked', false);
                                $('#gst-fields').hide();
                                $('#cgst, #sgst').val('').removeAttr('required');
                            }

                            showNotification('success',
                                'All data loaded successfully from tracking number!');
                        } else {
                            showNotification('warning',
                                'Tracking number not found. Please enter details manually.');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error fetching tracking data:', xhr.responseText);
                        showNotification('error',
                            'Tracking number not found. Please enter details manually.');
                    }
                });
            }

            // ========== UTILITY FUNCTIONS ==========

            // Generate Tracking Number (for OUT)
            function generateTrackingNumber() {
                const timestamp = Date.now().toString(36).toUpperCase();
                const random = Math.random().toString(36).substring(2, 6).toUpperCase();
                $('#tracking_number').val('CN-' + timestamp + random);
            }

            // GST Checkbox Toggle
            $('#include_gst').on('change', function() {
                if (this.checked) {
                    $('#gst-fields').show();
                    $('#cgst, #sgst').attr('required', 'required');
                } else {
                    $('#gst-fields').hide();
                    $('#cgst, #sgst').removeAttr('required').val('');
                    $('#total_with_gst').val('');
                }
                calculateGST();
            });

            // Calculate GST
            function calculateGST() {
                const amount = parseFloat($('#amount').val()) || 0;
                const cgst = parseFloat($('#cgst').val()) || 0;
                const sgst = parseFloat($('#sgst').val()) || 0;

                if (amount > 0 && (cgst > 0 || sgst > 0)) {
                    const totalGst = (amount * (cgst + sgst)) / 100;
                    $('#total_with_gst').val((amount + totalGst).toFixed(2));
                } else {
                    $('#total_with_gst').val('');
                }
            }

            $('#amount, #cgst, #sgst').on('input', calculateGST);

            // Reset all fields
            function resetAllFields() {
                $('#branch-row, #item-type-container, #transport-type-container, #tracking-container, #location-row, #weight-quantity-row, #amount-container, #gst-checkbox-container, #description-container')
                    .hide();
                $('#from_branch_select, #to_branch_id, #city, #state, #transport_type, #unit, #quantity, #amount, #description, #tracking_number')
                    .val('');
                $('input[name="item_type"]').prop('checked', false);
                $('#include_gst').prop('checked', false);
                $('#gst-fields').hide();
                $('#cgst, #sgst, #total_with_gst').val('');
                $('#city, #state').prop('readonly', false);
            }

            // Show notification helper
            function showNotification(type, message) {
                // You can replace this with your notification system (toastr, sweetalert, etc.)
                console.log(`[${type.toUpperCase()}] ${message}`);

                // Example with simple alert (replace with your notification plugin)
                if (type === 'error') {
                    alert('❌ ' + message);
                } else if (type === 'success') {
                    alert('✅ ' + message);
                } else if (type === 'warning') {
                    alert('⚠️ ' + message);
                }
            }

            // Show GST fields if old values exist (Laravel validation errors)
            @if (old('include_gst'))
                $('#gst-fields').show();
                $('#cgst, #sgst').attr('required', 'required');
                calculateGST();
            @endif
        });
    </script>
@endpush
