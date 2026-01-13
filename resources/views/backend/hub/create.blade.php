<!-- resources/views/backend/hubs/create.blade.php -->
@extends('backend.partials.master')

@section('title')
    {{ __('hub.title') }} {{ __('levels.add') }}
@endsection

@section('maincontent')
    <div class="container-fluid dashboard-content">
        <!-- Page Header -->
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
                                <li class="breadcrumb-item active" aria-current="page">{{ __('levels.create') }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-10 col-lg-10 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-body">
                        <h2 class="pageheader-title">{{ __('hub.create_hub') }}</h2>

                        <form action="{{ route('hubs.store') }}" method="POST" enctype="multipart/form-data"
                            id="basicform">
                            @csrf

                            <!-- Name -->
                            <div class="form-group">
                                <label for="name">{{ __('levels.name') }} <span class="text-danger">*</span></label>
                                <input id="name" type="text" name="name"
                                    placeholder="{{ __('placeholder.Enter_name') }}"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="form-group">
                                <label for="phone">{{ __('levels.phone') }} <span class="text-danger">*</span></label>
                                <input id="phone" type="text" name="phone"
                                    placeholder="{{ __('placeholder.Enter_phone') }}"
                                    class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}"
                                    required>
                                @error('phone')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Hub Location - State & City -->
                            <div class="form-group">
                                <label for="state">State <span class="text-danger">*</span></label>
                                <select id="state" name="state" class="form-control" required>
                                    <option value="">Select State</option>
                                </select>
                                @error('state')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="city">City <span class="text-danger">*</span></label>
                                <select id="city" name="city" class="form-control" required>
                                    <option value="">Select City</option>
                                </select>
                                @error('city')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Contact Person -->
                            <div class="form-group">
                                <label for="contact_person">{{ __('levels.contact_person_name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="contact_person" id="contact_person"
                                    class="form-control @error('contact_person') is-invalid @enderror"
                                    placeholder="Enter contact person name" value="{{ old('contact_person') }}" required>
                                @error('contact_person')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Pincode -->
                            <div class="form-group">
                                <label for="pincode">{{ __('levels.pincode') }} <span class="text-danger">*</span></label>
                                <input type="text" name="pincode" id="pincode" class="form-control"
                                    placeholder="{{ __('placeholder.Enter_pincode') }}" value="{{ old('pincode') }}"
                                    maxlength="6" pattern="[0-9]{6}" required>
                                @error('pincode')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- GST & Tax Fields -->
                            <div class="card mt-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">GST & Tax Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="gst_withdrawn"
                                                        value="1" id="gst_withdrawn"
                                                        {{ old('gst_withdrawn') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="gst_withdrawn">GST
                                                        Withdrawn</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>CGST (%)</label>
                                                <input type="number" name="cgst" class="form-control" step="0.01"
                                                    min="0" value="{{ old('cgst') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>SGST (%)</label>
                                                <input type="number" name="sgst" class="form-control" step="0.01"
                                                    min="0" value="{{ old('sgst') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>IGST (%) (Other State)</label>
                                                <input type="number" name="igst" class="form-control" step="0.01"
                                                    min="0" value="{{ old('igst') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Rate Type -->
                            <div class="form-group mt-4">
                                <label>Rate Type <span class="text-danger">*</span></label>
                                <div class="d-flex flex-wrap gap-4">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="rate_in" name="rate_type" value="in"
                                            class="custom-control-input"
                                            {{ old('rate_type', 'in') == 'in' ? 'checked' : '' }} required>
                                        <label class="custom-control-label" for="rate_in">IN</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="rate_out" name="rate_type" value="out"
                                            class="custom-control-input" {{ old('rate_type') == 'out' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="rate_out">OUT</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="rate_local" name="rate_type" value="local"
                                            class="custom-control-input"
                                            {{ old('rate_type') == 'local' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="rate_local">Local</label>
                                    </div>
                                </div>
                                @error('rate_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Item Type -->
                            <div class="form-group">
                                <label for="item_type">Item Type <span class="text-danger">*</span></label>
                                <select name="item_type" id="item_type" class="form-control" required>
                                    <option value="">Select Item Type</option>
                                    <option value="document" {{ old('item_type') == 'document' ? 'selected' : '' }}>
                                        Document</option>
                                    <option value="parcel" {{ old('item_type') == 'parcel' ? 'selected' : '' }}>Parcel
                                    </option>
                                    <option value="urgent" {{ old('item_type') == 'urgent' ? 'selected' : '' }}>Urgent
                                    </option>
                                </select>
                                @error('item_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Transport Type -->
                            <div class="form-group">
                                <label>Transport Type <span class="text-danger">*</span></label>
                                <div class="d-flex flex-wrap gap-4">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="by_road" name="transport_type" value="by_road"
                                            class="custom-control-input"
                                            {{ old('transport_type', 'by_road') == 'by_road' ? 'checked' : '' }} required>
                                        <label class="custom-control-label" for="by_road">By Road</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="by_air" name="transport_type" value="by_air"
                                            class="custom-control-input"
                                            {{ old('transport_type') == 'by_air' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="by_air">By Air</label>
                                    </div>
                                </div>
                                @error('transport_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Weight Unit -->
                            <div class="form-group">
                                <label for="weight_unit">Weight Unit <span class="text-danger">*</span></label>
                                <select name="weight_unit" id="weight_unit" class="form-control" required>
                                    <option value="">Select Unit</option>
                                    <option value="gram" {{ old('weight_unit') == 'gram' ? 'selected' : '' }}>Gram
                                    </option>
                                    <option value="kg" {{ old('weight_unit') == 'kg' ? 'selected' : '' }}>Kilogram
                                        (kg)</option>
                                    <option value="liter" {{ old('weight_unit') == 'liter' ? 'selected' : '' }}>Liter
                                    </option>
                                    <option value="ml" {{ old('weight_unit') == 'ml' ? 'selected' : '' }}>Milliliter
                                        (ml)</option>
                                </select>
                                @error('weight_unit')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Document Alert -->
                            <div id="document-alert" class="alert alert-info mt-3" style="display: none;">
                                <i class="fa fa-info-circle"></i>
                                <strong>Note:</strong> For Document, default weight is <strong>100 grams</strong>.
                            </div>

                            <!-- SERVICE AREA & RATES -->
                            <div class="card mt-4 mb-4" id="service-area-section" style="display: none;">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fa fa-map-marker"></i> Service Area & Custom Rates</h5>
                                </div>
                                <div class="card-body">
                                    <small class="form-text text-muted d-block mb-4">
                                        Select states and cities where this hub provides service. Enter your custom rates
                                        below.
                                    </small>

                                    <!-- Dynamic Weight Slabs -->
                                    <div class="mb-5 p-4 border rounded" style="background:#f8f9fa;">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h6 class="mb-0">
                                                <strong>Weight Slabs & Rates <small class="text-muted">(in <span
                                                            id="unit-display">grams</span>)</small></strong>
                                            </h6>
                                            <button type="button" class="btn btn-primary btn-sm" id="add-slab">
                                                <i class="fa fa-plus"></i> Add Slab
                                            </button>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th width="25%">Min Weight</th>
                                                        <th width="25%">Max Weight</th>
                                                        <th width="35%">Rate (₹)</th>
                                                        <th width="15%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="slabs-container">
                                                    <!-- First slab by default -->
                                                    <tr class="slab-row" data-slab-index="0">
                                                        <td>
                                                            <input type="number" name="slabs[0][min]"
                                                                class="form-control" placeholder="0" step="0.01"
                                                                min="0" value="{{ old('slabs.0.min', 0) }}"
                                                                required>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="slabs[0][max]"
                                                                class="form-control" placeholder="100" step="0.01"
                                                                min="0" value="{{ old('slabs.0.max', 100) }}"
                                                                required>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="slabs[0][rate]"
                                                                class="form-control" placeholder="₹ 0.00" step="0.01"
                                                                min="0" value="{{ old('slabs.0.rate') }}"
                                                                required>
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm remove-slab"
                                                                style="display: none;">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <small class="form-text text-muted d-block mt-3">
                                            Define weight ranges and their corresponding rates. Click "Add Slab" to add more
                                            ranges.
                                        </small>
                                    </div>

                                    <!-- Service Area Selection -->
                                    <div class="form-group">
                                        <label>Select Service States <span class="text-danger">*</span></label>
                                        <select id="service-state" class="form-control">
                                            <option value="">Add Service State</option>
                                        </select>
                                    </div>

                                    <div id="service-areas-list" class="border rounded p-3"
                                        style="background: #f8f9fa; min-height: 100px; max-height: 600px; overflow-y: auto;">
                                        <!-- Dynamic states & cities -->
                                    </div>
                                </div>
                            </div>

                            <!-- Address & Map -->
                            <div class="form-group mt-4">
                                <label for="address">{{ __('levels.address') }} <span
                                        class="text-danger">*</span></label>
                                <input type="hidden" id="hub_lat" name="hub_lat">
                                <input type="hidden" id="hub_long" name="hub_long">
                                <input type="hidden" id="address" name="address">

                                <div class="input-group mb-3">
                                    <input id="autocomplete-input" type="text" class="form-control"
                                        placeholder="Search address...">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" id="locationIcon">
                                            <i class="fa fa-crosshairs"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <div id="googleMap"
                                        style="width:100%; height:400px; border-radius:8px; border:1px solid #ddd;"></div>
                                </div>
                                @error('address')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="form-group">
                                <label for="status">{{ __('levels.status') }} <span
                                        class="text-danger">*</span></label>
                                <select name="status" class="form-control" required>
                                    @foreach (trans('status') as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('status', 1) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Buttons -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <a href="{{ route('hubs.index') }}" class="btn btn-secondary ml-2">Cancel</a>
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
        .custom-control-inline {
            margin-right: 1.5rem;
        }

        #service-areas-list .service-area-item {
            background: white;
            border-left: 4px solid #007bff;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const stateCityData = @json(json_decode(file_get_contents(storage_path('app/data/states+cities.json')), true));
        let serviceAreaIndex = 0;
        let map, marker, autocomplete;

        function initMap() {
            const indiaCenter = {
                lat: 20.5937,
                lng: 78.9629
            };
            map = new google.maps.Map(document.getElementById('googleMap'), {
                zoom: 5,
                center: indiaCenter
            });

            marker = new google.maps.Marker({
                map: map,
                draggable: true
            });

            const input = document.getElementById('autocomplete-input');
            autocomplete = new google.maps.places.Autocomplete(input, {
                componentRestrictions: {
                    country: 'in'
                }
            });
            autocomplete.bindTo('bounds', map);

            autocomplete.addListener('place_changed', () => {
                const place = autocomplete.getPlace();
                if (!place.geometry) return;

                const loc = place.geometry.location;
                updateMapAndFields(loc, place.formatted_address);
            });

            marker.addListener('dragend', () => updatePosition(marker.getPosition()));
            document.getElementById('locationIcon').addEventListener('click', getCurrentLocation);
            getCurrentLocation();
        }

        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(pos => {
                    const latLng = new google.maps.LatLng(pos.coords.latitude, pos.coords.longitude);
                    updateMapAndFields(latLng);
                });
            }
        }

        function updateMapAndFields(latLng, address = null) {
            map.setCenter(latLng);
            map.setZoom(17);
            marker.setPosition(latLng);

            document.getElementById('hub_lat').value = latLng.lat();
            document.getElementById('hub_long').value = latLng.lng();

            if (address) {
                document.getElementById('autocomplete-input').value = address;
                document.getElementById('address').value = address;
            } else {
                new google.maps.Geocoder().geocode({
                    location: latLng
                }, (results, status) => {
                    if (status === 'OK' && results[0]) {
                        const addr = results[0].formatted_address;
                        document.getElementById('autocomplete-input').value = addr;
                        document.getElementById('address').value = addr;
                    }
                });
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Populate states
            ['#state', '#service-state'].forEach(sel => {
                $(sel).append('<option value="">Select State</option>');
                Object.keys(stateCityData).sort().forEach(state => $(sel).append(
                    `<option value="${state}">${state}</option>`));
            });

            // Hub state → city
            $('#state').on('change', function() {
                const state = $(this).val();
                $('#city').empty().append('<option value="">Select City</option>');
                if (state && stateCityData[state]) {
                    stateCityData[state].sort().forEach(city => $('#city').append(
                        `<option value="${city}">${city}</option>`));
                }
            });

            // Service area addition
            $('#service-state').on('change', function() {
                const state = $(this).val();
                if (!state || $(`[data-service-state="${state}"]`).length) {
                    $(this).val('');
                    return;
                }

                const cities = stateCityData[state] || [];
                let html = `<div class="service-area-item" data-service-state="${state}">
                <input type="hidden" name="service_states[${serviceAreaIndex}]" value="${state}">
                <div class="d-flex justify-content-between mb-3">
                    <h6><strong>${state}</strong></h6>
                    <button type="button" class="btn btn-danger btn-sm remove-service-area">Remove</button>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input select-all-cities" type="checkbox" id="all-${serviceAreaIndex}">
                    <label class="form-check-label font-weight-bold" for="all-${serviceAreaIndex}">Select All Cities</label>
                </div>
                <div class="row">`;

                cities.sort().forEach(city => {
                    const id = `city-${serviceAreaIndex}-${city.replace(/\s+/g, '-')}`;
                    html += `<div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input city-checkbox" type="checkbox" name="service_cities[${serviceAreaIndex}][]" value="${city}" id="${id}">
                        <label class="form-check-label" for="${id}">${city}</label>
                    </div>
                </div>`;
                });

                html += `</div></div>`;
                $('#service-areas-list').append(html);
                serviceAreaIndex++;
                $(this).val('');
                $('#service-area-section').show();
            });

            // Remove state & select all
            $(document).on('click', '.remove-service-area', function() {
                $(this).closest('.service-area-item').remove();
                if ($('.service-area-item').length === 0) $('#service-area-section').hide();
            });

            $(document).on('change', '.select-all-cities', function() {
                $(this).closest('.service-area-item').find('.city-checkbox').prop('checked', this.checked);
            });

            $(document).on('change', '.city-checkbox', function() {
                const parent = $(this).closest('.service-area-item');
                const allChecked = parent.find('.city-checkbox').length === parent.find(
                    '.city-checkbox:checked').length;
                parent.find('.select-all-cities').prop('checked', allChecked);
            });

            // Slab management
            let slabIndex = 1;

            $('#add-slab').on('click', function() {
                const newRow = `
                <tr class="slab-row" data-slab-index="${slabIndex}">
                    <td>
                        <input type="number" name="slabs[${slabIndex}][min]" class="form-control" placeholder="Min" step="0.01" min="0" required>
                    </td>
                    <td>
                        <input type="number" name="slabs[${slabIndex}][max]" class="form-control" placeholder="Max" step="0.01" min="0" required>
                    </td>
                    <td>
                        <input type="number" name="slabs[${slabIndex}][rate]" class="form-control" placeholder="₹ 0.00" step="0.01" min="0" required>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm remove-slab">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
                $('#slabs-container').append(newRow);
                slabIndex++;
                updateRemoveButtons();
            });

            $(document).on('click', '.remove-slab', function(e) {
                e.preventDefault();
                $(this).closest('.slab-row').remove();
                updateRemoveButtons();
            });

            function updateRemoveButtons() {
                const rows = $('#slabs-container .slab-row').length;
                $('.remove-slab').toggle(rows > 1);
            }

            // Item type handling
            function handleItemTypeChange() {
                const type = $('#item_type').val();
                if (type === 'document') {
                    $('#document-alert').show();
                    $('#weight_unit').val('gram').prop('disabled', true);
                } else {
                    $('#document-alert').hide();
                    $('#weight_unit').prop('disabled', false);
                }
                $('#service-area-section').toggle(!!type);
                updateUnitDisplay();
            }

            function updateUnitDisplay() {
                const unit = $('#weight_unit').val() || 'gram';
                const display = unit === 'kg' ? 'kg' : unit === 'liter' ? 'liter' : unit === 'ml' ? 'ml' : 'grams';
                $('#unit-display').text(display);
            }

            $('#item_type').on('change', handleItemTypeChange);
            $('#weight_unit').on('change', updateUnitDisplay);
            handleItemTypeChange();
        });

        $('#basicform').on('submit', function() {
            $('#weight_unit').prop('disabled', false);
        });
    </script>

    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAepBinSy2JxyEvbidFz_AnFYFsFlFqQo4&libraries=places&callback=initMap">
    </script>
@endpush
