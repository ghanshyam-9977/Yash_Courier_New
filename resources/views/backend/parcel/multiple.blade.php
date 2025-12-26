@extends('backend.partials.master')
@section('title')
{{ __('parcel.title') }} {{ __('levels.add') }}
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
                                    {{ __('parcel.dashboard') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('parcel.index') }}" class="breadcrumb-link">
                                    {{ __('parcel.title') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                {{ __('levels.create') }}
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h2 class="pageheader-title">{{ __('parcel.create_parcel') }}</h2>

                    <form action="{{ route('parcel.multiple') }}" method="POST" enctype="multipart/form-data"
                        id="basicform">
                        @csrf
                        <div class="row">
                            <div class="form-group col-12 col-md-6">
                                <label for="merchant_id">{{ __('merchant.title') }}</label> <span
                                    class="text-danger">*</span>
                                <select style="width: 100%" id="merchant_id" name="merchant_id"
                                    class="form-control @error('merchant_id') is-invalid @enderror"
                                    data-url="{{ route('parcel.merchant.shops') }}" required="">
                                    <option value="">{{ __('menus.select') }} {{ __('merchant.title') }}
                                    </option>

                                </select>
                                {{-- cod charge calculation --}}
                                <input type="hidden" id="merchanturl" data-url="{{ route('get.merchant.cod') }}" />
                                <input type="hidden" id="inside_city" value="0" />
                                <input type="hidden" id="sub_city" value="0" />
                                <input type="hidden" id="outside_city" value="0" />
                                @error('merchant_id')
                                <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="invoice_no">{{ __('parcel.invoice') }}</label>
                                <input id="invoice_no" type="text" name="invoice_no"
                                    placeholder="{{ __('parcel.enter_invoice_number') }}" class="form-control"
                                    autocomplete="off"
                                    value="{{ old('invoice_no', 'INV-' . mt_rand(100000, 999999)) }}">
                                @error('invoice_no')
                                <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>



                        <div id="parcelFieldsContainer" class="parcel-fields-container">
                            <!-- Initial Parcel Entry Rendered by JS -->
                        </div>

                        <div class="row mt-2">
                            <div class="col-12 d-flex justify-content-between">
                                <button type="button" id="addParcelBtn" class="btn btn-success btn-sm">
                                    + {{ __('levels.add') }} Parcel
                                </button>
                                <div>
                                    <a href="{{ route('parcel.index') }}" class="btn btn-space btn-secondary">
                                        {{ __('levels.cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-space btn-primary">
                                        {{ __('levels.save') }}
                                    </button>
                                </div>
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script>
    // async function fetchPrice(index) {
    //     const entry = $(`.parcel-entry[data-parcel-id="${index}"]`);
    //     const fromId = $('#merchant_id').val(); // or other default origin branch
    //     const toId = entry.find(`select[name="parcels[${index}][to_branch_id]"]`).val();
    //     const transport = entry.find(`select[name="parcels[${index}][transport_type]"]`).val();

    //     if (!fromId || !toId || !transport) return;

    //     try {
    //         const res = await $.get('{{ route('parcel.transport.price') }}', {
    //             from_branch_id: fromId,
    //             to_branch_id: toId,
    //             transport_type: transport
    //         });
    //         if (res.status === 'success') {
    //             entry.find(`input[name="parcels[${index}][amount]"]`)
    //                 .val(res.amount);
    //         } else {
    //             console.warn(res.message);
    //         }
    //     } catch (e) {
    //         console.warn('Price fetch failed', e);
    //     }
    // }

    // $('#parcelFieldsContainer')
    //     .on('change', '.parcel-entry select[name$="[to_branch_id]"], select[name$="[transport_type]"]',
    //         function () {
    //             const idx = $(this).closest('.parcel-entry').data('parcel-id');
    //             fetchPrice(idx);
    //         }
    //     );

    $('#merchant_id').on('change', function () {
        // Optionally refetch for all existing entries
        $('.parcel-entry').each((i, el) => fetchPrice($(el).data('parcel-id')));
    });

    let parcelIndex = 0;

    function generateParcelEntry(index) {
        return `
                    <div class="parcel-entry card p-3 mb-3" data-parcel-id="${index}">
                        <div class="row">
                         <div class="form-group col-md-3">
                                <label for="to_branch_id_${index}">To branch</label>
                                <select id="to_branch_id_${index}" name="parcels[${index}][to_branch_id]" class="form-control select2" required>
                                    <option value="">{{ __('menus.select') }}  branch </option>
                                    @foreach (\App\Models\Backend\Hub::all() as $hub)
                                        <option value="{{ $hub->id }}">{{ $hub->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                    <label for="barcode_${index}">{{ __('parcel.barcode') }}</label>
                                    <input type="text" id="barcode_${index}" name="parcels[${index}][barcode]" 
                                        class="form-control" placeholder="Enter Barcode" required>
                            </div>

                               <div class="form-group  col-md-3">
                                    <label>Transport<span class="text-danger"></span></label>
                                    <select name="parcels[${index}][transport_type]" class="form-control" required>
                                        <option value="">Select Transport Type</option>
                                        <option value="by_road">By Road</option>
                                        <option value="by_air">By Air</option>
                                    </select>
                                </div>
                                 <div class="form-group col-md-3">
                                <label for="quantity_${index}">Quantity</label>
                                <input type="number" min="1" id="quantity_${index}" name="parcels[${index}][quantity]" class="form-control" required>
                            </div>
                                 <div class="form-group col-md-3">
                                <label for="unit_${index}">Unit</label>
                                <select id="unit_${index}" name="parcels[${index}][unit]" class="form-control" required>
                                    <option value="">{{ __('menus.select') }} Unit</option>
                                    <option value="kg">Kg</option>
                                    <option value="liter">Liter</option>
                                    <option value="pcs">Pieces</option>
                                </select>
                            </div>
                           
                             <div class="form-group col-md-3">
                                <label for="amount_${index}">{{ __('levels.amount') }}</label>
                                <input type="number" step="0.01" id="amount_${index}" name="parcels[${index}][amount]" class="form-control" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="customer_name_${index}">{{ __('levels.customer_name') }}</label>
                                <input type="text" id="customer_name_${index}" name="parcels[${index}][customer_name]" class="form-control" required>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="phone_${index}">{{ __('levels.phone') }}</label>
                                <input type="text" id="phone_${index}" name="parcels[${index}][phone]" class="form-control" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="address_${index}">{{ __('levels.address') }}</label>
                                <input type="text" id="address_${index}" name="parcels[${index}][address]" class="form-control" required>
                            </div>




                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-danger btn-sm remove-parcel">Remove</button>
                        </div>
                    </div>`;
    }

    $(document).ready(function () {
        $('#parcelFieldsContainer').append(generateParcelEntry(parcelIndex));

        $('#addParcelBtn').on('click', function () {
            parcelIndex++;
            $('#parcelFieldsContainer').append(generateParcelEntry(parcelIndex));
        });

        $('#parcelFieldsContainer').on('click', '.remove-parcel', function () {
            $(this).closest('.parcel-entry').remove();
        });
    });
</script>
<script type="text/javascript" src="{{ static_asset('backend/js/parcel/map-current.js') }}"></script>
<script async
    src="https://maps.googleapis.com/maps/api/js?key={{ googleMapSettingKey() }}&libraries=places&callback=initMap"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    var deliverChargeUrl = '{{ route('parcel.deliveryCharge.get') }}';
    var merchantUrl = '{{ route('parcel.merchant.get') }}';

    $(document).on('change', 'select[name$="[transport_type]"], select[name$="[to_branch_id]"], select[name$="[unit]"], input[name$="[quantity]"]', function () {
        const entry = $(this).closest('.parcel-entry');
        const from_branch_id = $('#merchant_id').val(); // optional agar merchant ke branch se calculate hota hai
        const to_branch_id = entry.find('select[name$="[to_branch_id]"]').val();
        const transport_type = entry.find('select[name$="[transport_type]"]').val();
        const unit = entry.find('select[name$="[unit]"]').val();
        const quantityInput = entry.find('input[name$="[quantity]"]');
        const rateInput = entry.find('input[name$="[amount]"]');

        let quantity = parseFloat(quantityInput.val()) || 0;
        quantity = Math.ceil(quantity);

        if (from_branch_id && to_branch_id && transport_type && unit) {
            $.ajax({
                url: '{{ route("parcel.getBranchRatemultiple") }}',
                type: 'GET',
                data: {
                    from_branch_id: from_branch_id,
                    to_branch_id: to_branch_id,
                    transport_type: transport_type,
                    unit: unit
                },
                success: function (response) {
                    console.log("Response:", response);

                    if (response.success) {
                        const rate = parseFloat(response.amount) || 0;
                        const total = rate * quantity;
                        console.log("Rate:", rate, "Quantity:", quantity, "Total:", total);
                        rateInput.val(total.toFixed(2));
                    } else {
                        rateInput.val('');
                        alert(response.message);
                    }
                },
                error: function (xhr) {
                    console.error("Error:", xhr);
                    alert('Something went wrong while fetching amount');
                }
            });
        } else {
            rateInput.val('');
        }
    });


</script>
<script src="{{ static_asset('backend/js/parcel/create.js') }}"></script>
@endpush