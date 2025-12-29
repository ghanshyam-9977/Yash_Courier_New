<!-- resources/views/backend/parcel/print.blade.php -->
@extends('backend.partials.master')
@section('title')
{{ __('parcel.title') }}
@endsection
@section('maincontent')
<div class="container-fluid dashboard-content">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}" class="breadcrumb-link">{{ __('parcel.dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('parcel.index') }}" class="breadcrumb-link">{{ __('parcel.title') }}</a></li>
                            <li class="breadcrumb-item"><a href="" class="breadcrumb-link active">Print</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="invoice" id="printablediv">
                <div class="row mt-3">
                    <div class="col-sm-6">
                        <h3>
                            {{ __('invoice.invoice') }} # <small>{{ $parcel->invoice_no }}</small>
                        </h3>
                    </div>
                    <div class="col-sm-6">
                        <h5 class="float-sm-right">Date: {{ dateFormat($parcel->created_at) }}</h5>
                    </div>
                </div>
                <hr>
                <div class="row invoice-info">
                    <div class="col-sm-4 invoice-col" style="float:left;">
                        From <address class="font-weight-light">
                            <strong>{{ $parcel->merchant->business_name }}</strong><br>
                            {{ $parcel->merchant->merchant_unique_id }}<br>
                            Phone: {{ $parcel->merchant->user->mobile }}<br>
                            Email: {{ $parcel->merchant->user->email }}
                        </address>
                    </div>
                    <div class="col-sm-4 invoice-col" style="float:left;">
                        To
                        <address class="font-weight-light">
                            <strong>{{ $parcel->customer_name }}</strong><br>
                            Phone: {{ $parcel->customer_phone }}<br>
                        </address>
                    </div>
                    <div class="col-sm-4 font-weight-light" style="float:right;">
                        <b>Track ID:</b> {{ $parcel->tracking_id }}<br>
                        <b>Delivery Type:</b> {{ $parcel->delivery_type_name }}<br>
                        <b>Number of Parcels:</b> {{ $parcel->number_of_parcels }}
                    </div>
                </div>

                <!-- Parcel Details Table -->
                <!-- <div class="row" style="margin-top: 20px">
                    <div class="col-sm-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>

                                    <th>Total Parcels</th>

                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-label="#">1</td>

                                    <td data-label="Total parcels">{{ $parcel->number_of_parcels }}</td>

                                    <td data-label="Total">{{ $parcel->cash_collection }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div> -->

                <!-- Parcel Items Table -->
                <div class="row" style="margin-top: 20px">
                    <div class="col-sm-12 table-responsive">
                        <h4>Parcel Items</h4>
                        @if ($parcel->items->isEmpty())
                        <p>No items found for this parcel.</p>
                        @else
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Barcode</th>
                                    <th>Customer Name</th>
                                    <!-- <th>Hub ID</th> -->
                                    <th>Hub Name</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Parcel ID</th>
                                      <th>Transport Type</th>
                                    <th>Created At</th>
                                    <th>Price</th>
                                    <th>Weight</th>
                                    <th> Unit</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($parcel->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->barcode }}</td>
                                     <td>{{ $item->customer_name }}</td>
                                    <!-- <td>{{ $item->hub_id }}</td> -->
                                    <td>{{ $item->hub_name ?? 'N/A' }}</td>
                                    <td>{{ $item->phone ?? 'N/A' }}</td>
                                    <td>{{ $item->address ?? 'N/A' }}</td>
                                    <td>{{ $item->parcel_id }}</td>
                                        <td>{{ $item->transport_type }}</td>
                                    <td>{{ dateFormat($item->created_at) }}</td>
                                    <td>{{ number_format($item->price, 2) }}</td>
                                    <td>{{ number_format($item->Weight) }}</td>
                                    <td>{{ $item->category }}</td>

                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="9"><span class="pull-right"><b>Total Item Price</b></span></td>
                                    <td><b>{{ number_format($parcel->items->sum('price'), 2) }}</b></td>
                                </tr>
                            </tfoot>
                        </table>
                        @endif
                    </div>
                </div>
                <hr>
            </div>
            <div class="row no-print">
                <div class="col-sm-12">
                    <div class="float-sm-right">
                        <button class="btn btn-primary m-1" onclick="printDiv('printablediv')"><i class="fa fa-download"></i> Print</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ static_asset('backend/js/parcel/print.js') }}"></script>
@endpush
