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
                        <form action="{{ route('hub-panel.payment-request.store_branch') }}" method="POST"
                            id="branch-payment-form">
                            @csrf

                            <!-- Request Type Selection -->
                            <div class="form-group">
                                <label>{{ __('Request Type') }} <span class="text-danger">*</span></label>
                                <div class="mt-2">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="type_out" name="request_type" value="out"
                                            class="custom-control-input" {{ old('request_type') == 'out' ? 'checked' : '' }}
                                            required>
                                        <label class="custom-control-label" for="type_out">OUT</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="type_in" name="request_type" value="in"
                                            class="custom-control-input" {{ old('request_type') == 'in' ? 'checked' : '' }}
                                            required>
                                        <label class="custom-control-label" for="type_in">IN</label>
                                    </div>
                                </div>
                                @error('request_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Manifest No Field -->
                            <div class="form-group" id="manifest-container" style="display:none;">
                                <label for="manifest_no">Manifest No <span class="text-danger">*</span></label>
                                <input type="text" id="manifest_no" name="manifest_no" class="form-control"
                                    placeholder="Enter manifest number" value="{{ old('manifest_no') }}" >
                                <small class="form-text text-muted">Enter the manifest number for this request</small>
                                @error('manifest_no')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>



                            <div class="form-group" id="vehicle-container" style="display:none;">
                                <label for="vehicle_number" id="vehicle-label">Vehicle No<span
                                        class="text-danger">*</span></label>
                                <input type="text" id="vehicle_number" name="vehicle_no" class="form-control"
                                    placeholder="Enter vehicle number" value="{{ old('vehicle_no') }}">
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
                                            class="custom-control-input"
                                            {{ old('item_type') == 'document' ? 'checked' : '' }} >
                                        <label class="custom-control-label" for="item_document">Document</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="item_parcel" name="item_type" value="parcel"
                                            class="custom-control-input"
                                            {{ old('item_type') == 'parcel' ? 'checked' : '' }} >
                                        <label class="custom-control-label" for="item_parcel">Parcel</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="item_urgent" name="item_type" value="urgent"
                                            class="custom-control-input"
                                            {{ old('item_type') == 'urgent' ? 'checked' : '' }} >
                                        <label class="custom-control-label" for="item_urgent">Urgent</label>
                                    </div>
                                </div>
                                @error('item_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>


                            <div class="form-group" id="return-container" style="display:none;">
                                <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input" id="is_return" name="is_return"
                                        {{ old('is_return') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_return">
                                        return
                                    </label>
                                </div>


                                <div id="return-fields" style="display: none;">
                                    <div class="form-group">
                                        <label for="return_reason">Return Reason <span
                                                class="text-danger">*</span></label>
                                        <select id="return_reason" name="return_reason"
                                            class="form-control @error('return_reason') is-invalid @enderror">
                                            <option value="">Select Return Reason</option>
                                            <option value="customer_request"
                                                {{ old('return_reason') == 'customer_request' ? 'selected' : '' }}>
                                                Customer Request
                                            </option>
                                            <option value="damaged"
                                                {{ old('return_reason') == 'damaged' ? 'selected' : '' }}>
                                                Damaged in Transit
                                            </option>
                                            <option value="incorrect_item"
                                                {{ old('return_reason') == 'incorrect_item' ? 'selected' : '' }}>
                                                Incorrect Item
                                            </option>
                                            <option value="refused"
                                                {{ old('return_reason') == 'refused' ? 'selected' : '' }}>
                                                Customer Refused
                                            </option>
                                            <option value="address_issue"
                                                {{ old('return_reason') == 'address_issue' ? 'selected' : '' }}>
                                                Address Issue
                                            </option>
                                            <option value="other"
                                                {{ old('return_reason') == 'other' ? 'selected' : '' }}>
                                                Other
                                            </option>
                                        </select>
                                        @error('return_reason')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="return_remarks">Return Remarks (Optional)</label>
                                        <textarea id="return_remarks" name="return_remarks" rows="2" class="form-control"
                                            placeholder="e.g. Item damaged on left side, customer rejected...">{{ old('return_remarks') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Transport Type -->
                            <div class="form-group" id="transport-type-container" style="display:none;">
                                <label for="transport_type">{{ __('Transport Type') }} <span
                                        class="text-danger">*</span></label>
                                <select name="transport_type" id="transport_type"
                                    class="form-control @error('transport_type') is-invalid @enderror">
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
                                    placeholder="Enter or auto-generated tracking number"
                                    value="{{ old('tracking_number') }}" required>
                                <small class="form-text text-muted" id="tracking-hint"></small>
                            </div>

                            <!-- City & State Row -->
                            <!-- City & State Row -->
                            <div class="row" id="location-row" style="display:none;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" id="city-autocomplete"
                                            class="form-control @error('city') is-invalid @enderror"
                                            placeholder="Type city name (e.g. Indore, Delhi...)" autocomplete="off"
                                            value="{{ old('city') }}" required>

                                        <input type="hidden" name="city" id="city-hidden"
                                            value="{{ old('city') }}">
                                        <input type="hidden" name="state" id="state-hidden"
                                            value="{{ old('state') }}">

                                        <small class="form-text text-muted">Start typing to see suggestions...</small>

                                        @error('city')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        @error('state')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="state-display">State <span class="text-danger">*</span></label>
                                        <input type="text" id="state-display" class="form-control" readonly>
                                        <input type="hidden" name="state" id="state-hidden-value"
                                            value="{{ old('state') }}">
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
                                            <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>
                                                Kilogram
                                                (kg)</option>
                                            <option value="gram" {{ old('unit') == 'gram' ? 'selected' : '' }}>Gram
                                                (g)
                                            </option>
                                            <option value="liter" {{ old('unit') == 'liter' ? 'selected' : '' }}>
                                                Liter
                                                (L)</option>
                                            <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>
                                                Milliliter
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
                                    placeholder="{{ __('Enter Amount') }}" value="{{ old('amount') }}">
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
                                        Include GST (CGST & SGST & IGST)
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

                                <div class="form-group col-md-4">
                                    <label for="igst">IGST (%) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" id="igst" name="igst"
                                        class="form-control @error('igst') is-invalid @enderror"
                                        placeholder="Enter IGST %" value="{{ old('igst') }}">
                                    @error('igst')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
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

                            <!-- COD Section (Only for OUT) -->
                            <div class="form-group" id="cod-container" style="display:none;">
                                <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input" id="is_cod" name="is_cod"
                                        {{ old('is_cod') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_cod">
                                        This is a COD (Cash on Delivery) Consignment
                                    </label>
                                </div>

                                <div id="cod-fields" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="cod_amount">COD Amount to Collect <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" step="0.01" id="cod_amount" name="cod_amount"
                                                    class="form-control @error('cod_amount') is-invalid @enderror"
                                                    placeholder="e.g. 2500.00" value="{{ old('cod_amount') }}">
                                                @error('cod_amount')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="cod_payment_mode">Preferred Payment Mode (Optional)</label>
                                                <select id="cod_payment_mode" name="cod_payment_mode"
                                                    class="form-control">
                                                    <option value="">Any Mode</option>
                                                    <option value="cash"
                                                        {{ old('cod_payment_mode') == 'cash' ? 'selected' : '' }}>Cash
                                                        Only
                                                    </option>
                                                    <option value="upi"
                                                        {{ old('cod_payment_mode') == 'upi' ? 'selected' : '' }}>UPI
                                                    </option>
                                                    <option value="card"
                                                        {{ old('cod_payment_mode') == 'card' ? 'selected' : '' }}>Card
                                                    </option>
                                                    <option value="online"
                                                        {{ old('cod_payment_mode') == 'online' ? 'selected' : '' }}>
                                                        Online
                                                        Transfer</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="cod_remarks">COD Remarks (Optional)</label>
                                        <textarea id="cod_remarks" name="cod_remarks" rows="2" class="form-control"
                                            placeholder="e.g. Collect from Mr. Rajesh Kumar, Mobile: 98xxxxxxxx">{{ old('cod_remarks') }}</textarea>
                                    </div>
                                </div>
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

            // COD Checkbox Toggle
            $('#is_cod').on('change', function() {
                if (this.checked) {
                    $('#cod-fields').slideDown();
                    $('#cod_amount').attr('required', 'required');
                } else {
                    $('#cod-fields').slideUp();
                    $('#cod_amount').removeAttr('required').val('');
                    $('#cod_payment_mode').val('');
                    $('#cod_remarks').val('');
                }
                updateDescriptionWithCOD(); // Description update
            });

            // Update description when COD fields change
            $('#cod_amount, #cod_payment_mode, #cod_remarks').on('input change', updateDescriptionWithCOD);

            // Function to auto-append COD details in description
            function updateDescriptionWithCOD() {
                if (!$('#is_cod').is(':checked')) {
                    // Remove COD line if checkbox unchecked
                    let desc = $('#description').val().replace(/\n?COD Amount:.*$/i, '').trim();
                    $('#description').val(desc);
                    return;
                }

                const codAmount = $('#cod_amount').val().trim();
                if (!codAmount) return;

                let codText = `COD Amount: ₹${parseFloat(codAmount).toFixed(2)}`;

                const mode = $('#cod_payment_mode').val();
                if (mode && mode !== '') {
                    const modeText = mode === 'cash' ? 'Cash' : mode.toUpperCase();
                    codText += ` | Mode: ${modeText}`;
                }

                const remarks = $('#cod_remarks').val().trim();
                if (remarks) {
                    codText += ` | Remarks: ${remarks}`;
                }

                let currentDesc = $('#description').val().trim();
                // Remove previous COD line if exists
                currentDesc = currentDesc.replace(/\n?COD Amount:.*$/i, '').trim();

                // Add new COD line
                if (currentDesc) {
                    currentDesc += '\n' + codText;
                } else {
                    currentDesc = codText;
                }

                $('#description').val(currentDesc);
            }
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
                $('#manifest-container').show();
                $('#branch-row').show();
                $('#item-type-container').show();
                $('#transport-type-container').show();
                $('#tracking-container').show();
                $('#vehicle-container').show();
                $('#return-container').show();
                $('#tracking-label').html('Tracking / Consignment No <span class="text-danger">*</span>');
                $('#vehicle-label').html('Vehicle No <span class="text-danger">*</span>');
                $('#tracking-hint').text('Auto-generated tracking number');
                $('#location-row').show();
                $('#weight-quantity-row').show();
                $('#amount-container').show();
                $('#gst-checkbox-container').show();
                $('#description-container').show();

                $('#city').prop('readonly', false).val('{{ old('city') }}');
                $('#state').prop('readonly', true).val('{{ old('state') }}');
                $('#cod-container').show(); // <-- यह लाइन जोड़ें
            }

            // IN Request Flow
            function handleInRequest() {
                $('#from-branch-label').html('From Branch <span class="text-danger">*</span>');
                $('#to-branch-label').html('Receive Branch <span class="text-danger">*</span>');
                $('#manifest-container').show();
                $('#branch-row').show();
                $('#item-type-container').show();
                $('#transport-type-container').show();
                $('#tracking-container').show();
                $('#vehicle-container').show();
                $('#return-container').show();
                $('#tracking-label').html('Tracking / Consignment No <span class="text-danger">*</span>');
                $('#vehicle-label').html('Vehicle No <span class="text-danger">*</span>');
                $('#tracking_number').prop('', false).val('');
                $('#vehicle_number').prop('', false).val('');
                $('#tracking-hint').text('Enter tracking number to auto-fill all details');
                $('#location-row').show();
                $('#city').prop('readonly', true);
                $('#state').prop('readonly', true);
                $('#weight-quantity-row').show();
                $('#amount-container').show();
                $('#gst-checkbox-container').show();
                $('#description-container').show();
                $('#cod-container').show();
            }


            // City input par state fetch karne ke liye
            // City input handler - Updated for multiple states
            let cityTimeout;
            $('#city').on('input blur', function() {
                clearTimeout(cityTimeout);
                const cityName = $(this).val().trim();

                if (cityName.length < 3) {
                    convertStateToInput();
                    $('#state').val('');
                    return;
                }

                cityTimeout = setTimeout(function() {
                    $.ajax({
                        url: "{{ route('get.state.by.city') }}",
                        type: "GET",
                        data: {
                            city: cityName
                        },
                        success: function(response) {
                            if (response.success && response.state) {
                                convertStateToInput();
                                $('#state').val(response.state);
                                // showNotification('success', 'State auto-filled: ' +
                                //     response.state);
                            } else {
                                convertStateToInput();
                                $('#state').val('');
                                showNotification('warning',
                                    'No state found for this city.');
                            }
                        },
                        error: function() {
                            convertStateToInput();
                            $('#state').val('');
                            showNotification('error', 'Failed to fetch state.');
                        }
                    });
                }, 800);
            });

            // Dropdown functions hata do ya comment kar do (kyunki ab need nahi)

            // Convert state field to dropdown
            function convertStateToDropdown(states, cityName) {
                const $stateGroup = $('#state').parent();

                // Remove existing dropdown if any
                $('#state-dropdown').remove();

                let options = '<option value="">-- Select State --</option>';
                states.forEach(function(state) {
                    options += `<option value="${state}">${state}</option>`;
                });

                const dropdown = `
        <select id="state-dropdown" name="state" class="form-control" required>
            ${options}
        </select>
        <small class="form-text text-muted">Multiple states possible for ${cityName}</small>
    `;

                // Replace the input with dropdown
                $('#state').hide();
                $stateGroup.append(dropdown);
            }

            // Convert back to normal readonly input (or editable if needed)
            function convertStateToInput() {
                $('#state-dropdown').remove();
                $('#state').show();
            }

            // On page load - ensure correct state (for old input or validation error)
            $(document).ready(function() {
                // If old state exists and city exists, try to match
                @if (old('city') && old('state'))
                    $('#city').val('{{ old('city') }}');
                    $('#state').val('{{ old('state') }}');
                @endif
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
                            if (response.data.is_cod) {
                                $('#is_cod').prop('checked', true);
                                $('#cod-fields').show();
                                $('#cod_amount').val(response.data.cod_amount || '').attr('',
                                    'required');
                                $('#cod_payment_mode').val(response.data.cod_payment_mode || '');
                                $('#cod_remarks').val(response.data.cod_remarks || '');
                                updateDescriptionWithCOD();
                            } else {
                                $('#is_cod').prop('checked', false);
                                $('#cod-fields').hide();
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
            // function generateTrackingNumber() {
            //     const timestamp = Date.now().toString(36).toUpperCase();
            //     const random = Math.random().toString(36).substring(2, 6).toUpperCase();
            //     $('#tracking_number').val('CN-' + timestamp + random);
            // }

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
                $('#manifest-container, #branch-row, #item-type-container, #transport-type-container, #tracking-container, #location-row, #weight-quantity-row, #amount-container, #gst-checkbox-container, #description-container')
                    .hide();
                $('#manifest_no, #from_branch_select, #to_branch_id, #city, #state, #transport_type, #unit, #quantity, #amount, #description, #tracking_number')
                    .val('');
                $('input[name="item_type"]').prop('checked', false);
                $('#include_gst').prop('checked', false);
                $('#gst-fields').hide();
                $('#cgst, #sgst, #total_with_gst').val('');
                $('#city, #state').prop('readonly', false);
                $('#cod-container').hide();
                $('#is_cod').prop('checked', false);
                $('#cod-fields').hide();
                $('#cod_amount, #cod_remarks').val('');
                $('#cod_payment_mode').val('');
                $('#cod_amount').removeAttr('');
            }

            // Show notification helper
            function showNotification(type, message) {
                // Use toastr if available, otherwise alert
                if (typeof toastr !== 'undefined') {
                    if (type === 'success') {
                        toastr.success(message);
                    } else if (type === 'error') {
                        toastr.error(message);
                    } else if (type === 'warning') {
                        toastr.warning(message);
                    } else {
                        toastr.info(message);
                    }
                } else {
                    // Fallback to alert
                    const icon = type === 'success' ? '✅' : type === 'error' ? '❌' : type === 'warning' ? '⚠️' :
                        'ℹ️';
                    alert(icon + ' ' + message);
                }
            }

            // Show GST fields if old values exist (Laravel validation errors)
            @if (old('include_gst'))
                $('#gst-fields').show();
                $('#cgst, #sgst').attr('required', 'required');
                calculateGST();
            @endif

            // AJAX Form Submission
            $('#branch-payment-form').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                // Get form data
                const formData = new FormData(this);

                // Disable submit button to prevent double submission
                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.text();
                submitBtn.prop('disabled', true).text('Submitting...');

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            showNotification('success', response.message ||
                                'Request submitted successfully!');
                            // Form data remains filled, no reset
                        } else {
                            showNotification('error', response.message || 'An error occurred.');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred while submitting the form.';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Handle validation errors
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join('\n');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showNotification('error', errorMessage);
                    },
                    complete: function() {
                        // Re-enable submit button
                        submitBtn.prop('disabled', false).text(originalText);
                    }
                });
            });
        });
    </script>
@endpush
@push('styles')
    <style>
        .city-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ced4da;
            border-top: none;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .city-suggestion-item {
            padding: 8px 12px;
            cursor: pointer;
        }

        .city-suggestion-item:hover,
        .city-suggestion-item.active {
            background: #007bff;
            color: white;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            let suggestionIndex = -1;
            let suggestions = [];

            const $input = $('#city-autocomplete');
            const $suggestionsBox = $('<div class="city-suggestions"></div>');
            $input.parent().css('position', 'relative');
            $input.after($suggestionsBox);

            $input.on('input keydown', function(e) {
                const query = $(this).val().trim();

                // Arrow keys
                if (e.key === 'ArrowDown') {
                    suggestionIndex = Math.min(suggestionIndex + 1, suggestions.length - 1);
                    updateActive();
                    return;
                }
                if (e.key === 'ArrowUp') {
                    suggestionIndex = Math.max(suggestionIndex - 1, -1);
                    updateActive();
                    return;
                }
                if (e.key === 'Enter') {
                    if (suggestionIndex >= 0) {
                        selectSuggestion(suggestions[suggestionIndex]);
                    }
                    $suggestionsBox.hide();
                    return;
                }

                if (query.length < 2) {
                    $suggestionsBox.hide();
                    return;
                }

                $.get("{{ route('get.cities.suggestions') }}", {
                        q: query
                    })
                    .done(function(response) {
                        suggestions = response.results;
                        suggestionIndex = -1;

                        if (suggestions.length === 0) {
                            $suggestionsBox.hide();
                            return;
                        }

                        $suggestionsBox.empty();
                        suggestions.forEach(function(item, index) {
                            const div = $('<div class="city-suggestion-item">' + item.label +
                                '</div>');
                            div.on('click', function() {
                                selectSuggestion(item);
                            });
                            $suggestionsBox.append(div);
                        });

                        $suggestionsBox.show();
                        updateActive();
                    });
            });

            function updateActive() {
                $suggestionsBox.find('.city-suggestion-item').removeClass('active');
                if (suggestionIndex >= 0) {
                    $suggestionsBox.find('.city-suggestion-item').eq(suggestionIndex).addClass('active');
                }
            }

            function selectSuggestion(item) {
                $('#city-autocomplete').val(item.city);
                $('#city-hidden').val(item.city);
                $('#state-display').val(item.state);
                $('#state-hidden-value').val(item.state);
                $suggestionsBox.hide();
            }

            // Click outside to hide
            $(document).on('click', function(e) {
                if (!$(e.target).closest($input.parent()).length) {
                    $suggestionsBox.hide();
                }
            });

            // On page load - if old values exist
            @if (old('city') && old('state'))
                $('#city-autocomplete').val('{{ old('city') }}');
                $('#state-display').val('{{ old('state') }}');
                $('#city-hidden').val('{{ old('city') }}');
                $('#state-hidden-value').val('{{ old('state') }}');
            @endif
        });


        $(document).ready(function() {

            // Return Checkbox Toggle
            $('#is_return').on('change', function() {
                if (this.checked) {
                    $('#return-fields').slideDown();
                    $('#return_reason').attr('required', 'required');

                    // Amount को 0 करें (या hidden करें)
                    $('#amount').val('0').prop('readonly', true).css('opacity', '0.6');

                    // COD को disable करें
                    $('#is_cod').prop('checked', false).prop('disabled', true);
                    $('#cod-container').slideUp();
                    $('#cod-fields').hide();
                    $('#cod_amount').removeAttr('required').val('');

                    // GST को disable करें
                    $('#include_gst').prop('checked', false).prop('disabled', true);
                    $('#gst-fields').hide();
                    $('#gst-checkbox-container').slideUp();

                    updateDescriptionWithReturn();
                } else {
                    $('#return-fields').slideUp();
                    $('#return_reason').removeAttr('required').val('');
                    $('#return_remarks').val('');

                    // Amount को normal करें
                    $('#amount').val('').prop('readonly', false).css('opacity', '1');

                    // COD को enable करें
                    $('#is_cod').prop('disabled', false);
                    $('#cod-container').slideDown();
                    $('#gst-checkbox-container').slideDown();

                    let desc = $('#description').val().replace(/\n?Return Reason:.*$/i, '').trim();
                    $('#description').val(desc);
                }
            });

            // Return details को description में add करें
            $('#return_reason, #return_remarks').on('input change', function() {
                if ($('#is_return').is(':checked')) {
                    updateDescriptionWithReturn();
                }
            });

            function updateDescriptionWithReturn() {
                const reason = $('#return_reason').val();
                if (!reason) return;

                let returnText = `Return Reason: ${getReasonLabel(reason)}`;

                const remarks = $('#return_remarks').val().trim();
                if (remarks) {
                    returnText += ` | Remarks: ${remarks}`;
                }

                let currentDesc = $('#description').val().trim();
                currentDesc = currentDesc.replace(/\n?Return Reason:.*$/i, '').trim();

                if (currentDesc) {
                    currentDesc += '\n' + returnText;
                } else {
                    currentDesc = returnText;
                }

                $('#description').val(currentDesc);
            }

            function getReasonLabel(value) {
                const reasons = {
                    'customer_request': 'Customer Request',
                    'damaged': 'Damaged in Transit',
                    'incorrect_item': 'Incorrect Item',
                    'refused': 'Customer Refused',
                    'address_issue': 'Address Issue',
                    'other': 'Other'
                };
                return reasons[value] || value;
            }

            // Out और In request handlers में return-container add करें
            // handleOutRequest function में यह line add करें:
            // $('#return-container').show();

            // handleInRequest function में यह line add करें:
            // $('#return-container').show();

            // Page load पर if old value exists
            @if (old('is_return'))
                $('#return-container').show();
                $('#return-fields').show();
                $('#is_return').prop('checked', true);
                $('#return_reason').attr('required', 'required');
                $('#amount').val('0').prop('readonly', true).css('opacity', '0.6');
                $('#is_cod').prop('disabled', true);
                $('#include_gst').prop('disabled', true);
            @endif
        });
    </script>
@endpush
