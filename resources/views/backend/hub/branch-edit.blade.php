@extends('backend.partials.master')

@section('title')
    {{ __('hub_payment_request.edit') }}
@endsection

@section('maincontent')
    <div class="container-fluid dashboard-content">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard.index') }}" class="breadcrumb-link">
                                        {{ __('levels.dashboard') }}
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="" class="breadcrumb-link">
                                        {{ __('levels.branch') }}
                                    </a>
                                </li>
                                <li class="breadcrumb-item"><a href=""
                                        class="breadcrumb-link active">{{ __('levels.edit') }}</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">{{ __('hub_payment_request.branch_edit_request') }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('hub-branch-request-update.done', $hub->id) }}" method="POST">
                            @csrf
                            
                            {{-- Name --}}
                            <div class="form-group">
                                <label>{{ __('levels.name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $hub->name) }}" required>
                                @error('name')
                                    <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div class="form-group">
                                <label>{{ __('levels.phone') }} <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control"
                                    value="{{ old('phone', $hub->phone) }}" required>
                                @error('phone')
                                    <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- State --}}
                            <div class="form-group">
                                <label for="state">{{ __('State') }} <span class="text-danger">*</span></label>
                                <select id="state" name="state" class="form-control @error('state') is-invalid @enderror" required>
                                    <option value="">Select State</option>
                                </select>
                                @error('state')
                                    <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- City --}}
                            <div class="form-group" id="city-container" style="display:none;">
                                <label for="city">{{ __('City') }} <span class="text-danger">*</span></label>
                                <select id="city" name="city" class="form-control @error('city') is-invalid @enderror" required>
                                    <option value="">Select City</option>
                                </select>
                                @error('city')
                                    <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Contact Person --}}
                            <div class="form-group">
                                <label>{{ __('levels.contact_person_name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="contact_person" class="form-control"
                                    value="{{ old('contact_person', $hub->contact_person) }}" required>
                                @error('contact_person')
                                    <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Pincode --}}
                            <div class="form-group">
                                <label>{{ __('levels.pincode') }} <span class="text-danger">*</span></label>
                                <input type="text" name="pincode" class="form-control"
                                    value="{{ old('pincode', $hub->pincode) }}" maxlength="6" pattern="[0-9]{6}" required>
                                @error('pincode')
                                    <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Item Type --}}
                            <div class="form-group">
                                <label for="item_type">{{ __('Item Type') }} <span class="text-danger">*</span></label>
                                <select name="item_type" id="item_type" class="form-control @error('item_type') is-invalid @enderror" required>
                                    <option value="">Select Item Type</option>
                                    <option value="document" {{ old('item_type', $hub->item_type) == 'document' ? 'selected' : '' }}>Document</option>
                                    <option value="parcel" {{ old('item_type', $hub->item_type) == 'parcel' ? 'selected' : '' }}>Parcel</option>
                                    <option value="urgent" {{ old('item_type', $hub->item_type) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                                @error('item_type')
                                    <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Document Info Alert --}}
                            <div id="document-alert" class="alert alert-info mt-3" style="display: none;">
                                <i class="fa fa-info-circle"></i>
                                <strong>Note:</strong> For Document, default quantity is <strong>100 grams</strong> and you can set a base rate below.
                                During booking, if weight > 100g, it will be charged as Parcel.
                            </div>

                            {{-- Transport Type --}}
                            <div class="form-group">
                                <label for="transport_type">{{ __('Transport Type') }} <span class="text-danger">*</span></label>
                                <select name="transport_type" id="transport_type" class="form-control @error('transport_type') is-invalid @enderror" required>
                                    <option value="">Select Transport Type</option>
                                    <option value="by_road" {{ old('transport_type', $hub->transport_type) == 'by_road' ? 'selected' : '' }}>By Road</option>
                                    <option value="by_air" {{ old('transport_type', $hub->transport_type) == 'by_air' ? 'selected' : '' }}>By Air</option>
                                </select>
                                @error('transport_type')
                                    <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Weight Unit --}}
                            <div class="form-group">
                                <label for="unit">{{ __('Weight Unit') }} <span class="text-danger">*</span></label>
                                <select id="unit" name="unit" class="form-control @error('unit') is-invalid @enderror" required>
                                    <option value="">Select Unit</option>
                                    <option value="kg" {{ old('unit', $hub->unit) == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                    <option value="gram" {{ old('unit', $hub->unit) == 'gram' ? 'selected' : '' }}>Gram (g)</option>
                                    <option value="liter" {{ old('unit', $hub->unit) == 'liter' ? 'selected' : '' }}>Liter (L)</option>
                                    <option value="ml" {{ old('unit', $hub->unit) == 'ml' ? 'selected' : '' }}>Milliliter (ml)</option>
                                </select>
                                @error('unit')
                                    <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Default Quantity --}}
                            <div class="form-group">
                                <label for="quantity">{{ __('Default Quantity') }} <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" id="quantity" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                                    placeholder="e.g. 100 for document" value="{{ old('quantity', $hub->quantity) }}" required>
                                <small class="text-muted">Default assumed weight/quantity (in selected unit)</small>
                                @error('quantity')
                                    <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Default Rate --}}
                            <div class="form-group">
                                <label for="rate">{{ __('Rate (₹)') }} <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" id="rate" name="rate" class="form-control @error('rate') is-invalid @enderror"
                                    placeholder="Enter Rate" value="{{ old('rate', $hub->rate) }}" required>
                                <small class="text-muted">Base shipping rate for this hub (per default quantity)</small>
                                @error('rate')
                                    <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- GST Configuration --}}
                            <div class="form-group">
                                <label>{{ __('GST Configuration') }}</label>
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input" id="include_gst" name="include_gst" 
                                        {{ old('include_gst', $hub->include_gst) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="include_gst">
                                        Include GST (CGST & SGST & IGST)
                                    </label>
                                </div>
                            </div>

                            <div id="gst-fields" style="display: {{ old('include_gst', $hub->include_gst) ? 'block' : 'none' }};">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="cgst">CGST (%)</label>
                                        <input type="number" step="0.01" id="cgst" name="cgst" class="form-control @error('cgst') is-invalid @enderror"
                                            placeholder="Enter CGST %" value="{{ old('cgst', $hub->cgst ?? '0') }}">
                                        @error('cgst')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="sgst">SGST (%)</label>
                                        <input type="number" step="0.01" id="sgst" name="sgst" class="form-control @error('sgst') is-invalid @enderror"
                                            placeholder="Enter SGST %" value="{{ old('sgst', $hub->sgst ?? '0') }}">
                                        @error('sgst')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="igst">IGST (%)</label>
                                        <input type="number" step="0.01" id="igst" name="igst" class="form-control @error('igst') is-invalid @enderror"
                                            placeholder="Enter IGST %" value="{{ old('igst', $hub->igst ?? '0') }}">
                                        @error('igst')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Description --}}
                            <div class="form-group">
                                <label for="description">{{ __('Description') }}</label>
                                <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror"
                                    placeholder="Enter description or additional notes">{{ old('description', $hub->description) }}</textarea>
                                @error('description')
                                    <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Address --}}
                            <div class="form-group">
                                <label>{{ __('levels.address') }} <span class="text-danger">*</span></label>
                                <input type="text" name="address" class="form-control"
                                    value="{{ old('address', $hub->address) }}" required>
                                @error('address')
                                    <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Status --}}
                            <div class="form-group">
                                <label for="status">{{ __('levels.status') }} <span class="text-danger">*</span></label>
                                <select name="status" class="form-control @error('status') is-invalid @enderror">
                                    @foreach (trans('status') as $key => $status)
                                        <option value="{{ $key }}" {{ old('status', $hub->status) == $key ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary">{{ __('levels.update') }}</button>
                                <a href="{{ route('hub-panel.branch-request.index') }}" class="btn btn-secondary">{{ __('levels.cancel') }}</a>
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
    document.addEventListener('DOMContentLoaded', function() {
        const stateCityData = @json(json_decode(file_get_contents(storage_path('app/data/states+cities.json')), true));
        
        // Load States
        $('#state').append('<option value="">Select State</option>');
        Object.keys(stateCityData).forEach(function(state) {
            const selected = '{{ old("state", $hub->state ?? "") }}' === state ? 'selected' : '';
            $('#state').append(`<option value="${state}" ${selected}>${state}</option>`);
        });

        // Pre-select city if state is already set
        const selectedState = '{{ old("state", $hub->state ?? "") }}';
        if (selectedState && stateCityData[selectedState]) {
            stateCityData[selectedState].forEach(function(city) {
                const selected = '{{ old("city", $hub->city ?? "") }}' === city ? 'selected' : '';
                $('#city').append(`<option value="${city}" ${selected}>${city}</option>`);
            });
            $('#city-container').show();
        }

        // State → City
        $('#state').on('change', function() {
            let state = $(this).val();
            $('#city').empty().append('<option value="">Select City</option>');
            $('#city-container').hide();

            if (stateCityData[state]) {
                stateCityData[state].forEach(function(city) {
                    $('#city').append(`<option value="${city}">${city}</option>`);
                });
                $('#city-container').show();
            }
        });

        const itemTypeSelect = document.getElementById('item_type');
        const unitSelect = document.getElementById('unit');
        const quantityInput = document.getElementById('quantity');
        const documentAlert = document.getElementById('document-alert');

        function updateFormForDocument() {
            if (itemTypeSelect.value === 'document') {
                documentAlert.style.display = 'block';
            } else {
                documentAlert.style.display = 'none';
            }
        }

        // On load
        updateFormForDocument();

        // On change
        itemTypeSelect.addEventListener('change', updateFormForDocument);

        // GST Toggle
        document.getElementById('include_gst').addEventListener('change', function() {
            const gstFields = document.getElementById('gst-fields');
            if (this.checked) {
                gstFields.style.display = 'block';
            } else {
                gstFields.style.display = 'none';
            }
        });
    });
</script>
@endpush