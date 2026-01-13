@extends('backend.partials.master')

@section('title')
    {{ __('hub_payment_request.edit') }} - {{ $hub->name ?? 'Branch' }}
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
                                    <a href="{{ route('dashboard.index') }}"
                                        class="breadcrumb-link">{{ __('levels.dashboard') }}</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('hub-panel.branch-request.index') }}"
                                        class="breadcrumb-link">{{ __('levels.branch') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('levels.edit') }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">{{ __('hub_payment_request.branch_edit_request') }} - {{ $hub->name }}</h4>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('hubs.update', $hub->id) }}" method="POST">
                            @csrf
                            @method('PUT')


                            <!-- Basic Information -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('levels.name') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $hub->name) }}" required>
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('levels.phone') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ old('phone', $hub->phone) }}" required>
                                        @error('phone')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- State & City -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>State <span class="text-danger">*</span></label>
                                        <select name="state" id="state"
                                            class="form-control @error('state') is-invalid @enderror" required>
                                            <option value="">Select State</option>
                                        </select>
                                        @error('state')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>City <span class="text-danger">*</span></label>
                                        <select name="city" id="city"
                                            class="form-control @error('city') is-invalid @enderror" required>
                                            <option value="">Select City</option>
                                        </select>
                                        @error('city')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('levels.contact_person_name') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="contact_person" class="form-control"
                                            value="{{ old('contact_person', $hub->contact_person) }}" required>
                                        @error('contact_person')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('levels.pincode') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="pincode" class="form-control"
                                            value="{{ old('pincode', $hub->pincode) }}" maxlength="6" pattern="[0-9]{6}"
                                            required>
                                        @error('pincode')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- GST & Tax Information -->
                            <div class="card mt-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">GST & Tax Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-check mt-4">
                                                <input class="form-check-input" type="checkbox" name="gst_withdrawn"
                                                    value="1" id="gst_withdrawn"
                                                    {{ old('gst_withdrawn', $hub->gst_withdrawn ?? false) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="gst_withdrawn">GST Withdrawn</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>CGST (%)</label>
                                                <input type="number" name="cgst" step="0.01" min="0"
                                                    class="form-control" value="{{ old('cgst', $hub->cgst ?? 0) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>SGST (%)</label>
                                                <input type="number" name="sgst" step="0.01" min="0"
                                                    class="form-control" value="{{ old('sgst', $hub->sgst ?? 0) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>IGST (%) (Other State)</label>
                                                <input type="number" name="igst" step="0.01" min="0"
                                                    class="form-control" value="{{ old('igst', $hub->igst ?? 0) }}">
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
                                            {{ old('rate_type', $hub->rate_type ?? 'in') == 'in' ? 'checked' : '' }}
                                            required>
                                        <label class="custom-control-label" for="rate_in">IN</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="rate_out" name="rate_type" value="out"
                                            class="custom-control-input"
                                            {{ old('rate_type', $hub->rate_type) == 'out' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="rate_out">OUT</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="rate_local" name="rate_type" value="local"
                                            class="custom-control-input"
                                            {{ old('rate_type', $hub->rate_type) == 'local' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="rate_local">Local</label>
                                    </div>
                                </div>
                                @error('rate_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Item Type + Transport + Unit -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Item Type <span class="text-danger">*</span></label>
                                        <select name="item_type" id="item_type"
                                            class="form-control @error('item_type') is-invalid @enderror" required>
                                            <option value="">Select Item Type</option>
                                            <option value="document"
                                                {{ old('item_type', $hub->item_type) == 'document' ? 'selected' : '' }}>
                                                Document</option>
                                            <option value="parcel"
                                                {{ old('item_type', $hub->item_type) == 'parcel' ? 'selected' : '' }}>
                                                Parcel</option>
                                            <option value="urgent"
                                                {{ old('item_type', $hub->item_type) == 'urgent' ? 'selected' : '' }}>
                                                Urgent</option>
                                        </select>
                                        @error('item_type')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Transport Type <span class="text-danger">*</span></label>
                                        <select name="transport_type"
                                            class="form-control @error('transport_type') is-invalid @enderror" required>
                                            <option value="">Select Transport</option>
                                            <option value="by_road"
                                                {{ old('transport_type', $hub->transport_type) == 'by_road' ? 'selected' : '' }}>
                                                By Road</option>
                                            <option value="by_air"
                                                {{ old('transport_type', $hub->transport_type) == 'by_air' ? 'selected' : '' }}>
                                                By Air</option>
                                        </select>
                                        @error('transport_type')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Weight Unit <span class="text-danger">*</span></label>
                                        <select name="weight_unit" id="weight_unit"
                                            class="form-control @error('weight_unit') is-invalid @enderror" required>
                                            <option value="">Select Unit</option>
                                            <option value="gram"
                                                {{ old('weight_unit', $hub->weight_unit ?? $hub->unit) == 'gram' ? 'selected' : '' }}>
                                                Gram</option>
                                            <option value="kg"
                                                {{ old('weight_unit', $hub->weight_unit ?? $hub->unit) == 'kg' ? 'selected' : '' }}>
                                                Kilogram</option>
                                            <option value="liter"
                                                {{ old('weight_unit', $hub->weight_unit ?? $hub->unit) == 'liter' ? 'selected' : '' }}>
                                                Liter</option>
                                            <option value="ml"
                                                {{ old('weight_unit', $hub->weight_unit ?? $hub->unit) == 'ml' ? 'selected' : '' }}>
                                                Milliliter</option>
                                        </select>
                                        @error('weight_unit')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Document Note -->
                            <div id="document-alert" class="alert alert-info mt-2" style="display: none;">
                                <i class="fa fa-info-circle"></i>
                                <strong>Note:</strong> For <strong>Document</strong> default weight is usually <strong>100
                                    grams</strong>.
                            </div>

                            <!-- Dynamic Weight Slabs -->
                            <div class="card mt-4" id="service-area-section">
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Weight Slabs & Rates <small class="text-muted">(in <span
                                                    id="unit-display">grams</span>)</small></h5>
                                        <button type="button" class="btn btn-primary btn-sm" id="add-slab">
                                            <i class="fa fa-plus"></i> Add Slab
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
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
                                                @php
                                                    $slabs = old(
                                                        'slabs',
                                                        collect($hub->rateSlabs ?? [])
                                                            ->map(function ($slab) {
                                                                return [
                                                                    'min' => $slab->min_weight,
                                                                    'max' => $slab->max_weight,
                                                                    'rate' => $slab->rate,
                                                                ];
                                                            })
                                                            ->toArray(),
                                                    );

                                                    $slabIndex = 0;
                                                @endphp


                                                @forelse($slabs as $slab)
                                                    <tr class="slab-row" data-slab-index="{{ $slabIndex }}">
                                                        <td>
                                                            <input type="number" name="slabs[{{ $slabIndex }}][min]"
                                                                class="form-control" placeholder="Min" step="0.01"
                                                                min="0" value="{{ $slab['min'] ?? '' }}" required>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="slabs[{{ $slabIndex }}][max]"
                                                                class="form-control" placeholder="Max" step="0.01"
                                                                min="0" value="{{ $slab['max'] ?? '' }}" required>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="slabs[{{ $slabIndex }}][rate]"
                                                                class="form-control" placeholder="₹ 0.00" step="0.01"
                                                                min="0" value="{{ $slab['rate'] ?? '' }}"
                                                                required>
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm remove-slab"
                                                                {{ count($slabs) <= 1 ? 'style=display:none' : '' }}>
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @php $slabIndex++; @endphp
                                                @empty
                                                    <tr class="slab-row" data-slab-index="0">
                                                        <td>
                                                            <input type="number" name="slabs[0][min]"
                                                                class="form-control" placeholder="0" step="0.01"
                                                                min="0" value="0" required>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="slabs[0][max]"
                                                                class="form-control" placeholder="100" step="0.01"
                                                                min="0" value="100" required>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="slabs[0][rate]"
                                                                class="form-control" placeholder="₹ 0.00" step="0.01"
                                                                min="0" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm remove-slab"
                                                                style="display: none;">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <small class="form-text text-muted d-block mt-3">
                                        Define weight ranges and their corresponding rates. Click "Add Slab" to add more
                                        ranges.
                                    </small>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="form-group mt-4">
                                <label>{{ __('levels.address') }} <span class="text-danger">*</span></label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3" required>{{ old('address', $hub->address) }}</textarea>
                                @error('address')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="form-group">
                                <label>{{ __('levels.status') }} <span class="text-danger">*</span></label>
                                <select name="status" class="form-control @error('status') is-invalid @enderror"
                                    required>
                                    @foreach (trans('status') as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('status', $hub->status) == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Buttons -->
                            <div class="form-group text-right mt-4">
                                <button type="submit" class="btn btn-primary px-5">Update Branch</button>
                                <a href="{{ route('hub-panel.branch-request.index') }}"
                                    class="btn btn-secondary px-5 ml-3">Cancel</a>
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
        const stateCityData = @json(json_decode(file_get_contents(storage_path('app/data/states+cities.json')), true));
        let slabIndex = {{ isset($slabIndex) ? $slabIndex : 1 }};

        document.addEventListener('DOMContentLoaded', function() {
            // Populate states
            const stateSelect = document.getElementById('state');
            Object.keys(stateCityData).sort().forEach(state => {
                const option = document.createElement('option');
                option.value = state;
                option.textContent = state;
                if (state === '{{ old('state', $hub->state ?? '') }}') option.selected = true;
                stateSelect.appendChild(option);
            });

            // City population
            const selectedState = '{{ old('state', $hub->state ?? '') }}';
            const citySelect = document.getElementById('city');

            function loadCities(state) {
                citySelect.innerHTML = '<option value="">Select City</option>';
                if (state && stateCityData[state]) {
                    stateCityData[state].sort().forEach(city => {
                        const option = document.createElement('option');
                        option.value = city;
                        option.textContent = city;
                        if (city === '{{ old('city', $hub->city ?? '') }}') option.selected = true;
                        citySelect.appendChild(option);
                    });
                }
            }

            loadCities(selectedState);
            stateSelect.addEventListener('change', (e) => loadCities(e.target.value));

            // Document alert
            const itemType = document.getElementById('item_type');
            const alert = document.getElementById('document-alert');

            function toggleDocumentAlert() {
                alert.style.display = itemType.value === 'document' ? 'block' : 'none';
            }

            itemType.addEventListener('change', toggleDocumentAlert);
            toggleDocumentAlert();

            // Slab management
            const addSlabBtn = document.getElementById('add-slab');
            const slabsContainer = document.getElementById('slabs-container');

            addSlabBtn.addEventListener('click', function(e) {
                e.preventDefault();
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
                slabsContainer.insertAdjacentHTML('beforeend', newRow);
                slabIndex++;
                updateRemoveButtons();
            });

            slabsContainer.addEventListener('click', function(e) {
                if (e.target.closest('.remove-slab')) {
                    e.preventDefault();
                    e.target.closest('.slab-row').remove();
                    updateRemoveButtons();
                }
            });

            function updateRemoveButtons() {
                const rows = document.querySelectorAll('.slab-row').length;
                document.querySelectorAll('.remove-slab').forEach(btn => {
                    btn.style.display = rows > 1 ? 'block' : 'none';
                });
            }

            // Unit display update
            const weightUnit = document.getElementById('weight_unit');

            function updateUnitDisplay() {
                const unit = weightUnit.value || 'gram';
                const display = unit === 'kg' ? 'kg' : unit === 'liter' ? 'liter' : unit === 'ml' ? 'ml' : 'grams';
                document.getElementById('unit-display').textContent = display;
            }

            weightUnit.addEventListener('change', updateUnitDisplay);
            updateUnitDisplay();

            updateRemoveButtons();
        });
    </script>
@endpush
