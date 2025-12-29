@extends('backend.partials.master')
@section('title')
    {{ __('hub.title') }} {{ __('levels.add') }}
@endsection
@section('maincontent')
    <div class="container-fluid dashboard-content">
        <!-- pageheader -->
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}"
                                        class="breadcrumb-link">{{ __('levels.dashboard') }}</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hubs.index') }}"
                                        class="breadcrumb-link">{{ __('hub.title') }}</a></li>
                                <li class="breadcrumb-item"><a href=""
                                        class="breadcrumb-link active">{{ __('levels.create') }}</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <!-- end pageheader -->

        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-body">
                        <h2 class="pageheader-title">{{ __('hub.create_hub') }}</h2>

                        <form action="{{ route('hubs.store') }}" method="POST" enctype="multipart/form-data"
                            id="basicform">
                            @csrf

                            <div class="form-group">
                                <label for="name">{{ __('levels.name') }}</label> <span class="text-danger">*</span>
                                <input id="name" type="text" name="name"
                                    placeholder="{{ __('placeholder.Enter_name') }}" autocomplete="off"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    required>
                                @error('name')
                                    <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone">{{ __('levels.phone') }}</label> <span class="text-danger">*</span>
                                <input id="phone" type="number" name="phone"
                                    placeholder="{{ __('placeholder.Enter_phone') }}" autocomplete="off"
                                    class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}"
                                    required>
                                @error('phone')
                                    <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="state">State <span class="text-danger">*</span></label>
                                <select id="state" name="state" class="form-control" required>
                                    <option value="">Select State</option>
                                </select>
                            </div>


                            <div class="form-group" id="city-container" style="display:none;">
                                <label for="city">City <span class="text-danger">*</span></label>
                                <select id="city" name="city" class="form-control" required>
                                    <option value="">Select City</option>
                                </select>
                            </div>


                            <div class="form-group">
                                <label for="contact_person">{{ __('levels.contact_person_name') }}</label> <span
                                    class="text-danger">*</span>
                                <input type="text" name="contact_person" id="contact_person" class="form-control"
                                    placeholder="{{ __('placeholder.Enter_contact_persion') }}"
                                    value="{{ old('contact_person') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="pincode">{{ __('levels.pincode') }}</label> <span class="text-danger">*</span>
                                <input type="text" name="pincode" id="pincode" class="form-control"
                                    placeholder="{{ __('placeholder.Enter_pincode') }}" value="{{ old('pincode') }}"
                                    maxlength="6" pattern="[0-9]{6}" required>
                            </div>

                            <!-- Item Type -->
                            <div class="form-group">
                                <label for="item_type">{{ __('Item Type') }}</label> <span class="text-danger">*</span>
                                <select name="item_type" id="item_type"
                                    class="form-control @error('item_type') is-invalid @enderror" required>
                                    <option value="">Select Item Type</option>
                                    <option value="document" {{ old('item_type') == 'document' ? 'selected' : '' }}>
                                        Document</option>
                                    <option value="parcel" {{ old('item_type') == 'parcel' ? 'selected' : '' }}>Parcel
                                    </option>
                                    <option value="urgent" {{ old('item_type') == 'urgent' ? 'selected' : '' }}>Urgent
                                    </option>
                                </select>
                                @error('item_type')
                                    <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Document Info Alert -->
                            <div id="document-alert" class="alert alert-info mt-3" style="display: none;">
                                <i class="fa fa-info-circle"></i>
                                <strong>Note:</strong> For Document, default quantity is <strong>100 grams</strong> and you
                                can set a base rate below.
                                During booking, if weight > 100g, it will be charged as Parcel.
                            </div>

                            <!-- Transport Type -->
                            <div class="form-group">
                                <label for="transport_type">{{ __('Transport Type') }}</label> <span
                                    class="text-danger">*</span>
                                <select name="transport_type" id="transport_type"
                                    class="form-control @error('transport_type') is-invalid @enderror" required>
                                    <option value="">Select Transport Type</option>
                                    <option value="by_road" {{ old('transport_type') == 'by_road' ? 'selected' : '' }}>By
                                        Road</option>
                                    <option value="by_air" {{ old('transport_type') == 'by_air' ? 'selected' : '' }}>By
                                        Air</option>
                                </select>
                                @error('transport_type')
                                    <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Weight Unit -->
                            <div class="form-group">
                                <label for="unit">{{ __('Weight Unit') }}</label> <span class="text-danger">*</span>
                                <select id="unit" name="unit"
                                    class="form-control @error('unit') is-invalid @enderror" required>
                                    <option value="">Select Unit</option>
                                    <option value="kg">Kilogram (kg)</option>
                                    <option value="gram">Gram (g)</option>
                                    <option value="liter">Liter (L)</option>
                                    <option value="ml">Milliliter (ml)</option>
                                </select>
                                @error('unit')
                                    <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Default Quantity -->
                            <div class="form-group">
                                <label for="quantity">{{ __('Default Quantity') }}</label> <span
                                    class="text-danger">*</span>
                                <input type="number" step="0.01" id="quantity" name="quantity"
                                    class="form-control @error('quantity') is-invalid @enderror"
                                    placeholder="e.g. 100 for document" value="{{ old('quantity') }}" required>
                                <small class="text-muted">Default assumed weight/quantity (in selected unit)</small>
                                @error('quantity')
                                    <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Default Rate -->
                            <div class="form-group">
                                <label for="rate">{{ __('Rate (₹)') }}</label> <span class="text-danger">*</span>
                                <input type="number" step="0.01" id="rate" name="rate"
                                    class="form-control @error('rate') is-invalid @enderror" placeholder="Enter Rate"
                                    value="{{ old('rate') }}" required>
                                <small class="text-muted">Base shipping rate for this hub (per default quantity)</small>
                                @error('rate')
                                    <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- GST Configuration -->
                            <div class="form-group">
                                <label>{{ __('GST Configuration') }}</label>
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input" id="include_gst"
                                        name="include_gst" {{ old('include_gst') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="include_gst">
                                        Include GST (CGST & SGST & IGST)
                                    </label>
                                </div>
                            </div>

                            <div id="gst-fields" style="display: {{ old('include_gst') ? 'block' : 'none' }};">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="cgst">CGST (%)</label>
                                        <input type="number" step="0.01" id="cgst" name="cgst"
                                            class="form-control @error('cgst') is-invalid @enderror"
                                            placeholder="Enter CGST %" value="{{ old('cgst', '0') }}">
                                        @error('cgst')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="sgst">SGST (%)</label>
                                        <input type="number" step="0.01" id="sgst" name="sgst"
                                            class="form-control @error('sgst') is-invalid @enderror"
                                            placeholder="Enter SGST %" value="{{ old('sgst', '0') }}">
                                        @error('sgst')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="igst">IGST (%) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" id="igst" name="igst"
                                            class="form-control @error('igst') is-invalid @enderror"
                                            placeholder="Enter IGST %" value="{{ old('igst','0') }}">
                                        @error('igst')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="form-group">
                                <label for="description">{{ __('Description') }}</label>
                                <textarea name="description" id="description" rows="3"
                                    class="form-control @error('description') is-invalid @enderror"
                                    placeholder="Enter description or additional notes">{{ old('description') }}</textarea>
                                @error('description')
                                    <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Address with Map -->
                            <div class="form-group">
                                <label for="address">{{ __('levels.address') }}</label> <span
                                    class="text-danger">*</span>
                                <input type="hidden" id="lat" name="lat" value="">
                                <input type="hidden" id="long" name="long" value="">
                                <div class="main-search-input-item location location-search">
                                    <div id="autocomplete-container" class="form-group random-search">
                                        <input id="autocomplete-input" type="text" name="address"
                                            class="recipe-search2 form-control" placeholder="Location Here!" required>
                                        <a href="javascript:void(0)" class="submit-btn btn current-location"
                                            id="locationIcon" onclick="getLocation()">
                                            <i class="fa fa-crosshairs"></i>
                                        </a>
                                        @error('address')
                                            <small class="text-danger mt-2">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="">
                                    <div id="googleMap" class="custom-map"></div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="form-group">
                                <label for="status">{{ __('levels.status') }}</label> <span
                                    class="text-danger">*</span>
                                <select name="status" class="form-control @error('status') is-invalid @enderror">
                                    @foreach (trans('status') as $key => $status)
                                        <option value="{{ $key }}"
                                            {{ old('status', \App\Enums\Status::ACTIVE) == $key ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <button type="submit"
                                        class="btn btn-space btn-primary">{{ __('levels.save') }}</button>
                                    <a href="{{ route('hubs.index') }}"
                                        class="btn btn-space btn-secondary">{{ __('levels.cancel') }}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .custom-map {
            width: 100%;
            height: 17rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        var mapLat = '';
        var mapLong = '';
    </script>
@endpush

<script type="text/javascript" src="{{ static_asset('backend/js/map/map-current.js') }}"></script>
<script async
    src="https://maps.googleapis.com/maps/api/js?key={{ googleMapSettingKey() }}&libraries=places&callback=initMap">
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Load States
        $('#state').append('<option value="">Select State</option>');
        Object.keys(stateCityData).forEach(function(state) {
            $('#state').append(`<option value="${state}">${state}</option>`);
        });

        // State → City
        $('#state').on('change', function() {

            let state = $(this).val();
            $('#city').empty().append('<option value="">Select City</option>');
            $('#city-container').hide();
            $('#branch-container').hide();

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
        const rateInput = document.getElementById('rate');
        const documentAlert = document.getElementById('document-alert');

        function updateFormForDocument() {
            if (itemTypeSelect.value === 'document') {
                unitSelect.value = 'gram';
                if (!quantityInput.value) {
                    quantityInput.value = '100';
                }
                if (!rateInput.value) {
                    rateInput.value = ''; // Optional: default rate for document
                }
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
                document.getElementById('cgst').setAttribute('required', 'required');
                document.getElementById('sgst').setAttribute('required', 'required');
            } else {
                gstFields.style.display = 'none';
                document.getElementById('cgst').removeAttribute('required');
                document.getElementById('sgst').removeAttribute('required');
            }
        });
    });


    const stateCityData = @json(json_decode(file_get_contents(storage_path('app/data/states+cities.json')), true));
</script>
