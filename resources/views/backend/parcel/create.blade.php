@extends('backend.partials.master')
@section('title')
{{ __('parcel.title') }} {{ __('levels.add') }}
@endsection
@section('maincontent')
<div class="container-fluid  dashboard-content">
    <!-- pageheader -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}"
                                    class="breadcrumb-link">{{ __('parcel.dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('parcel.index') }}"
                                    class="breadcrumb-link">{{ __('parcel.title') }}</a></li>
                            <li class="breadcrumb-item"><a href=""
                                    class="breadcrumb-link active">{{ __('levels.create') }}</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- <div class="col-md-12 col-lg-12 col-xl-8"> -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <h2 class="pageheader-title">{{ __('parcel.create_parcel') }}</h2>
                    <form action="{{ route('parcel.store') }}" method="POST" enctype="multipart/form-data"
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



                            <!-- <select name="merchant_shop_id" required>
        @foreach($merchantShops as $shop)
            <option value="{{ $shop->id }}">{{ $shop->name }}</option>
        @endforeach
    </select> -->


                            @csrf
                            <div class="form-group col-12 col-md-6">
                                <label for="hub_id">{{ __('To branch') }}</label> <span class="text-danger">*</span>
                                <select style="width: 100%" id="hub_id" class="form-control select2" name="hub_id" required>
                                    <option value="">{{ __('menus.select') }} {{ __('hub.title') }}</option>
                                    @foreach (\App\Models\Backend\Hub::all() as $hub)
                                    <option value="{{ $hub->id }}" {{ old('hub_id') == $hub->id ? 'selected' : '' }}>
                                        {{ $hub->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('hub_id')
                                <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group col-12 col-md-6">
                                <label for="pickup_phone">{{ __('parcel.pickup_phone') }}</label>
                                <input id="pickup_phone" type="text" name="pickup_phone"
                                    data-parsley-trigger="change"
                                    placeholder="{{ __('levels.pickup') }} {{ __('levels.phone') }}"
                                    autocomplete="off" class="form-control" value="{{ old('pickup_phone') }}"
                                    required="">
                                @error('pickup_phone')
                                <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="pickup_address">{{ __('parcel.pickup_address') }}</label>
                                <input id="pickup_address" type="text" name="pickup_address"
                                    data-parsley-trigger="change"
                                    placeholder="{{ __('levels.pickup') }} {{ __('levels.address') }}"
                                    autocomplete="off" class="form-control" value="{{ old('pickup_address') }}"
                                    required="">

                                <input type="hidden" id="pickup_lat" name="pickup_lat" value="">
                                <input type="hidden" id="pickup_long" name="pickup_long" value="">

                                @error('pickup_address')
                                <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="cash_collection">{{ __('parcel.cash_collection') }} </label> <span
                                    class="text-danger">*</span>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control cash-collection" id="cash_collection"
                                        value="{{ old('cash_collection') }}" name="cash_collection"
                                        placeholder="{{ __('parcel.Cash_amount_including_delivery_charge') }}"
                                        required="">
                                    @error('cash_collection')
                                    <small class="text-danger mt-2">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="selling_price">{{ __('parcel.selling_price') }} </label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control cash-collection" id="selling_price"
                                        value="{{ old('selling_price') }}" name="selling_price"
                                        placeholder="{{ __('parcel.Selling_price_of_parcel') }}">
                                    @error('selling_price')
                                    <small class="text-danger mt-2">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <!-- <div class="form-group col-12 col-md-6">
                                    <label for="opening_balance">{{ __('parcel.invoice') }}</label>
                                    <input id="invoice_no" type="text" name="invoice_no"
                                        data-parsley-trigger="change"
                                        placeholder="{{ __('parcel.enter_invoice_number') }}" autocomplete="off"
                                        class="form-control" value="{{ old('invoice_no') }}">
                                    @error('invoice_no')
                                        <small class="text-danger mt-2">{{ $message }}</small>
                                    @enderror
                                </div> -->

                            <div class="form-group col-12 col-md-6">
                                <label for="opening_balance">{{ __('parcel.invoice') }}</label>
                                <input id="invoice_no" type="text" name="invoice_no"
                                    data-parsley-trigger="change"
                                    placeholder="{{ __('parcel.enter_invoice_number') }}"
                                    autocomplete="off"
                                    class="form-control"
                                    value="{{ old('invoice_no', 'INV-' . mt_rand(100000, 999999)) }}">
                                @error('invoice_no')
                                <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>





                            <div class="form-group col-12 col-md-6">
                                <label for="merchant">{{ __('parcel.category') }}</label> <span
                                    class="text-danger">*</span>
                                <select style="width: 100%" id="category_id" class="form-control select2"
                                    name="category_id" class="form-control @error('category_id') is-invalid @enderror"
                                    data-url="{{ route('parcel.deliveryCategory.deliveryWeight') }}">
                                    <option value=""> {{ __('menus.select') }} {{ __('levels.category') }}
                                    </option>
                                    @foreach ($deliveryCharges as $deliverycharge)
                                    <option value="{{ $deliveryCategories[$deliverycharge]->id }}"
                                        {{ old('category_id') == $deliveryCategories[$deliverycharge]->id ? 'selected' : '' }}>
                                        {{ $deliveryCategories[$deliverycharge]->title }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>




                            <div class="form-group col-12 col-md-6">
                                <label for="delivery_type_id">{{ __('parcel.delivery_type') }}</label> <span
                                    class="text-danger">*</span>
                                <select style="width: 100%" class="form-control select2" id="delivery_type_id"
                                    name="delivery_type_id" required="">
                                    <option value=""> {{ __('menus.select') }} {{ __('menus.delivery_type') }}
                                    </option>
                                    @foreach ($deliveryTypes as $key => $status)
                                    <option
                                        @if ($status->key == 'same_day') value="1" @elseif($status->key == 'next_day') value="2" @elseif($status->key == 'sub_city') value="3" @elseif($status->key == 'outside_City') value="4" @endif>
                                        {{ __('deliveryType.' . $status->key) }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('delivery_type_id')
                                <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>


                            <div class="form-group col-12 col-md-6">
                                <label for="customer_name">{{ __('parcel.customer_name') }}</label> <span
                                    class="text-danger">*</span>
                                <input id="customer_name" type="text" name="customer_name"
                                    data-parsley-trigger="change" placeholder="{{ __('levels.customer_name') }}"
                                    autocomplete="off" class="form-control" value="{{ old('customer_name') }}"
                                    required="">
                                @error('customer_name')
                                <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="phone">{{ __('parcel.customer_phone') }}</label> <span
                                    class="text-danger">*</span>
                                <input id="phone" type="text" name="customer_phone"
                                    data-parsley-trigger="change" placeholder="{{ __('levels.customer_phone') }}"
                                    autocomplete="off" class="form-control" value="{{ old('customer_phone') }}"
                                    required="">
                                @error('customer_phone')
                                <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            @if (SettingHelper('fragile_liquid_status') == \App\Enums\Status::ACTIVE)
                            <div class="col-md-6 mt-2">
                                <label class="form-label"
                                    for="fv-full-name">{{ __('parcel.liquid_check_label') }}</label>
                                <div class="row pt-1">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <div class="preview-block">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="fragileLiquid"
                                                        data-amount="{{ SettingHelper('fragile_liquid_charge') }}"
                                                        name="fragileLiquid" onclick="processCheck(this);">
                                                    <label class="custom-control-label"
                                                        for="fragileLiquid">{{ __('parcel.liquid_fragile') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="form-group col-md-6" id="PackagingID">
                                <label for="packaging_id">{{ __('parcel.packaging') }}</label>
                                <select id="packaging_id" class="form-control" name="packaging_id">
                                    <option value=""> {{ __('menus.select') }} {{ __('menus.packaging') }}
                                    </option>
                                    @foreach ($packagings as $packaging)
                                    <option data-packagingamount="{{ $packaging->price }}"
                                        value="{{ $packaging->id }}"
                                        {{ old('packaging_id') == $packaging->id ? 'selected' : '' }}>
                                        {{ $packaging->name }} ( {{ number_format($packaging->price, 2) }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('packaging_id')
                                <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group col-md-6" id="priority">
                                <label for="priority_id">{{ __('parcel.priority') }}</label>
                                <select id="priority_id" class="form-control" name="priority_id">
                                    <option value="2" {{ old('priority_id') == 2 ? 'selected' : '' }}>
                                        {{ __('parcel.normal') }}
                                    </option>
                                    <option value="1" {{ old('priority_id') == 1 ? 'selected' : '' }}>
                                        {{ __('parcel.high') }}
                                    </option>
                                </select>
                                @error('priority_id')
                                <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>
                            <input type="hidden" id="merchantVat" name="vat_tex" value="0" />
                            <input type="hidden" id="merchantCodCharge" name="cod_charge" value="0" />
                            <input type="hidden" id="chargeDetails" name="chargeDetails" value="" />
                        </div>



                        @error('parcel_payment_method')

                        @enderror
                        <div class="row">
                            <div class="col-md-2 col-6">
                                <input class="methodInput" type="radio" name="parcel_payment_method" id="cod" value="{{ App\Enums\ParcelPaymentMethod::COD }}" checked />


                                <div class="mt-2">{{ __('ParcelPaymentMethod.'.App\Enums\ParcelPaymentMethod::COD) }}</div>
                                </label>
                            </div>
                        </div>


                        <div class="form-group mb-3">
                            <label for="number_of_parcels">Number of Parcels</label>
                            <input type="number" class="form-control" id="number_of_parcels" name="number_of_parcels" min="1" placeholder="Enter number of parcels">
                        </div>

                        <!-- Container for generated fields -->
                        <div id="parcelFieldsContainer" class="parcel-fields-container">
                            <!-- Dynamic parcel entry blocks will be inserted here -->
                        </div>

                        <div class="row mt-2">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12  d-flex justify-content-end">
                                <a href="{{ route('parcel.index') }}"
                                    class="btn btn-space btn-secondary">{{ __('levels.cancel') }}</a>
                                <button type="submit"
                                    class="btn btn-space btn-primary">{{ __('levels.save') }}</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <h2 class="pageheader-title">{{ __('parcel.charge_details') }}</h2>

                    <ul class="list-group">
                        <li class="list-group-item profile-list-group-item">
                            <span class="float-left font-weight-bold">{{ __('levels.title') }}</span>
                            <span class="float-right">{{ __('levels.amount') }}</span>
                        </li>
                        <li class="list-group-item profile-list-group-item">
                            <span class="float-left font-weight-bold">{{ __('parcel.Cash_Collection') }}</span>
                            <span class="float-right" id="totalCashCollection">{{ __('0.00') }}</span>
                        </li>
                        <li class="list-group-item profile-list-group-item">
                            <span class="float-left font-weight-bold">{{ __('parcel.Delivery_Charge') }}</span>
                            <span class="float-right" id="deliveryChargeAmount">{{ __('0.00') }}</span>
                        </li>
                        <li class="list-group-item profile-list-group-item">
                            <span class="float-left font-weight-bold">{{ __('reports.COD_Charge') }}</span>
                            <span class="float-right" id="codChargeAmount">{{ __('0.00') }}</span>
                        </li>


                        <li class="list-group-item profile-list-group-item hideShowLiquidFragile">
                            <span class="float-left font-weight-bold">{{ __('parcel.Liquid/Fragile_Charge') }}</span>
                            <span class="float-right" id="liquidFragileAmount">{{ __('0.00') }}</span>
                        </li>
                        <li class="list-group-item profile-list-group-item" id="packagingShow">
                            <span class="float-left font-weight-bold">{{ __('reports.P.Charge') }}</span>
                            <span class="float-right" id="packagingAmount">{{ __('0.00') }}</span>
                        </li>

                        <li class="list-group-item profile-list-group-item">
                            <span class="float-left font-weight-bold">{{ __('parcel.Total_Charge') }}</span>
                            <span class="float-right" id="totalDeliveryChargeAmount">{{ __('0.00') }}</span>
                        </li>

                        <li class="list-group-item profile-list-group-item">
                            <span class="float-left font-weight-bold">{{ __('parcel.Vat') }}</span>
                            <span class="float-right" id="VatAmount">{{ __('0.00') }}</span>
                        </li>

                        <li class="list-group-item profile-list-group-item">
                            <span class="float-left font-weight-bold">{{ __('parcel.Net_Payable') }}</span>
                            <span class="float-right" id="netPayable">{{ __('0.00') }}</span>
                        </li>

                        <li class="list-group-item profile-list-group-item">
                            <span class="float-left font-weight-bold">{{ __('parcel.Current_payable') }}</span>
                            <span class="float-right" id="currentPayable">{{ __('0.00') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>



<style>
    .parcel-fields-container {
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 5px;
    }

    .parcel-entry {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 10px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }

    .parcel-entry>div {
        flex: 1;
    }

    .parcel-entry label {
        display: block;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .parcel-entry input {
        width: 100%;
        padding: 6px 8px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    .parcel-entry .action-btn {
        flex: 0 0 60px;
        display: flex;
        justify-content: center;
    }

    .action-btn button {
        background: #dc3545;
        border: none;
        color: white;
        padding: 5px 10px;
        border-radius: 3px;
        cursor: pointer;
    }
</style>


<script>
    function rebuildIndexes() {
        const container = document.getElementById('parcelFieldsContainer');
        const entries = container.querySelectorAll('.parcel-entry');

        entries.forEach((entry, idx) => {
            const serialNo = entry.querySelector('.serial-no');
            if (serialNo) serialNo.value = idx + 1;





            const customer_name = entry.querySelector('[name$="[customer_name]"]');
            if (customer_name) customer_name.name = `parcels[${idx}][customer_name]`;

            const barcodeInput = entry.querySelector('[name$="[barcode]"]');
            if (barcodeInput) barcodeInput.name = `parcels[${idx}][barcode]`;

            const amountInput = entry.querySelector('[name$="[amount]"]');
            if (amountInput) amountInput.name = `parcels[${idx}][amount]`;

            const phoneInput = entry.querySelector('[name$="[phone]"]');
            if (phoneInput) phoneInput.name = `parcels[${idx}][phone]`;

            const addressInput = entry.querySelector('[name$="[address]"]');
            if (addressInput) addressInput.name = `parcels[${idx}][address]`;

            const quantityInput = entry.querySelector('[name$="[quantity]"]');
            if (quantityInput) quantityInput.name = `parcels[${idx}][quantity]`;

            const categorySelect = entry.querySelector('[name$="[unit]"]');
            if (categorySelect) categorySelect.name = `parcels[${idx}][unit]`;


                   const to_branch_id = entry.querySelector('[name$="[to_branch_id]"]');
            if (to_branch_id) to_branch_id.name = `parcels[${idx}][to_branch_id]`;

            const hubSelect = entry.querySelector('select[name$="[hub_id]"]');
            if (hubSelect) hubSelect.name = `parcels[${idx}][hub_id]`;

            const transportTypeSelect = entry.querySelector('select[name$="[transport_type]"]');
            if (transportTypeSelect) transportTypeSelect.name = `parcels[${idx}][transport_type]`;
        });

        const numberOfParcelsInput = document.getElementById('number_of_parcels');
        if (numberOfParcelsInput) numberOfParcelsInput.value = entries.length;
    }

    document.getElementById('number_of_parcels').addEventListener('input', function() {
        const container = document.getElementById('parcelFieldsContainer');
        const count = parseInt(this.value) || 1;
        container.innerHTML = '';

        for (let i = 0; i < count; i++) {
            container.insertAdjacentHTML('beforeend', `
            <div class="parcel-entry mb-2 p-2 border rounded">
                <div>
                    <label>S.No</label>
                    <input class="serial-no form-control-plaintext" readonly value="${i + 1}">
                </div>
   <div>
                    <label>customer name</label>
                    <input type="text" class="form-control" name="parcels[${i}][customer_name]" required>
                </div>




                <div><label>Barcode</label>
                <input 
                type="text" 
                class="form-control barcode-input"  
                name="parcels[${i}][barcode]" 
                placeholder="Enter barcode " 
                required>


                </div>
                <div>
                    <label>Phone</label>
                    <input type="text" class="form-control" name="parcels[${i}][phone]" required>
                </div>
                <div>
                    <label>Address</label>
                    <input type="text" class="form-control" name="parcels[${i}][address]" required>
                </div>
                <div>
                    <label>To Branch</label>
                    <select name="parcels[${i}][hub_id]" class="form-control select2" required>
                        <option value="">Select Branch</option>
                        @foreach (\App\Models\Backend\Hub::all() as $hub)
                            <option value="{{ $hub->id }}">{{ $hub->name }}</option>
                        @endforeach
                    </select>
                </div>


                     <div class="form-group">
        <label>Transport<span class="text-danger"></span></label>
        <select name="parcels[${i}][transport_type]" class="form-control" required>
            <option value="">Select Transport Type</option>
            <option value="by_road">By Road</option>
            <option value="by_air">By Air</option>
        </select>
    </div>


                    <div>
                    <label>Unit</label>
                    <select name="parcels[${i}][unit]" class="form-control" required>
                        <option value="">Select Category</option>
                        <option value="Kg">Kg</option>
                        <option value="Liter">Liter</option>
                        <option value="Pound">Pound</option>
                    </select>
                </div>

                <div>
                    <label>quantity</label>
                    <input type="text" class="form-control" name="parcels[${i}][quantity]" required>
                </div>




                             <div>
                    <label>to_branch_id</label>

   <select name="parcels[${i}][to_branch_id]" class="form-control">
                        <option value="">Select Branch</option>
                        @foreach ($payment as $pay_item)
                            <option value="{{ $pay_item->id }}">{{ $pay_item->to_branch_id }}</option>
                        @endforeach
                    </select>


                </div>



                   <div>
                    <label>Price</label>
                    <input type="text" class="form-control" name="parcels[${i}][amount]" id="rate" readonly>
                </div>
                <button type="button" class="btn btn-sm btn-danger mt-2"
                        onclick="if (confirm('Are you sure you want to delete this parcel entry?')) { this.closest('.parcel-entry').remove(); rebuildIndexes(); }">Delete</button>
            </div>
        `);
        }

        $('.select2').select2('destroy').select2();
    });

    $(document).ready(function() {
        $('.select2').select2();
    });


    function generateBarcode() {
        // Generate a unique barcode with timestamp and random number
        const timestamp = Date.now();
        const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
        return 'INV-' + timestamp + '-' + random;
    }

    function regenerateBarcode(button) {
        // Find the input field in the same parcel entry
        const barcodeInput = button.closest('.parcel-entry').querySelector('.barcode-input');
        if (barcodeInput) {
            barcodeInput.value = generateBarcode();
        }
    }

</script>



@endsection()
@push('styles')
<style>
    .main-search-input-item {
        flex: 1;
        margin-top: 3px;
        position: relative;
    }

    #autocomplete-container,
    #autocomplete-input {
        position: relative;
        z-index: 101;
    }

    .main-search-input input,
    .main-search-input input:focus {
        font-size: 16px;
        border: none;
        background: #fff;
        margin: 0;
        padding: 0;
        height: 44px;
        line-height: 44px;
        box-shadow: none;
    }

    .input-with-icon i,
    .main-search-input-item.location a {
        padding: 5px 10px;
        z-index: 101;
    }

    .main-search-input-item.location a {
        position: absolute;
        right: -50px;
        top: 40%;
        transform: translateY(-50%);
        color: #999;
        padding: 10px;
    }

    .current-location {
        margin-right: 50px;
        margin-top: 5px;
        color: #FFCC00 !important;
    }

    .custom-map {
        width: 100%;
        height: 17rem;
    }

    .pac-container {
        width: 295px;
        position: absolute;
        left: 0px !important;
        top: 28px !important;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush



@push('scripts')
<script>
    var mapLat = '';
    var mapLong = '';
</script>
<script type="text/javascript" src="{{ static_asset('backend/js/parcel/map-current.js') }}"></script>
<script async src="https://maps.googleapis.com/maps/api/js?key={{ googleMapSettingKey() }}&libraries=places&callback=initMap"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    var deliverChargeUrl = '{{ route('parcel.deliveryCharge.get') }}';
    var merchantUrl = '{{ route('parcel.merchant.get') }}';

    $(document).on('change', 'select[name$="[hub_id]"], select[name$="[transport_type]"], select[name$="[to_branch_id]"], select[name$="[unit]"], input[name$="[quantity]"]', function () {
    const entry = $(this).closest('.parcel-entry');

    // 👇 values fetch from current entry
    const hub_id = entry.find('select[name$="[hub_id]"]').val();              // from branch
    const branch_id  = entry.find('select[name$="[to_branch_id]"]').val();    // to branch
    const transport_type = entry.find('select[name$="[transport_type]"]').val(); 
    const unit = entry.find('select[name$="[unit]"]').val();
    let quantity = parseFloat(entry.find('input[name$="[quantity]"]').val()) || 0; // 🆕 quantity
    quantity = Math.ceil(quantity); // ✅ round up (1.2 -> 2)

    const rateInput = entry.find('input[name$="[amount]"]'); // price field

    if (hub_id && branch_id && transport_type && unit) {
        console.log("AJAX call started...");

        $.ajax({
            url: '{{ route("parcel.getBranchRate") }}',
            type: 'GET',
            data: {
                from_branch_id: hub_id,
                branch_id: branch_id,
                transport_type: transport_type,
                unit: unit
            },
            success: function (response) {
                console.log("Response:", response);

                if (response.success) {
                    let rate = parseFloat(response.amount) || 0;
                    let total = rate * quantity; // 🧮 multiply with rounded qty
                    rateInput.val(total.toFixed(2)); // ✅ Final amount
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
        rateInput.val(''); // clear amount when any field empty
    }
});

</script>
<script src="{{ static_asset('backend/js/parcel/create.js') }}"></script>

@endpush


