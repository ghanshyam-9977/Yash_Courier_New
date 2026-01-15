@extends('backend.partials.master')

@section('title')
    DRS Update
@endsection

@section('maincontent')

<style>
    /* MASTER CSS RADIO FIX */
    input[type="radio"] {
        display: inline-block !important;
        visibility: visible !important;
        opacity: 1 !important;
        position: static !important;
        width: 18px !important;
        height: 18px !important;
        margin-right: 6px;
    }
    .radio-row {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 6px;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>DRS Update</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('drs.update', $drs->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- CURRENT DRS INFO --}}
                        <div class="alert alert-info mb-3">
                            <strong>Current DRS:</strong>
                            {{ \Carbon\Carbon::parse($drs->drs_date)->format('d M Y') }}
                            | {{ $drs->drs_time }}
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">DRS No</label>
                                <input type="text" class="form-control"
                                    name="drs_no"
                                    value="{{ old('drs_no', $drs->drs_no) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Date</label>
                                <input type="date" class="form-control"
                                    name="delivery_date"
                                    value="{{ old('date_delivered', $drs->date_delivered) }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Area</label>
                                <input type="text" class="form-control"
                                    name="area"
                                    value="{{ old('area', $drs->area_name) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Employee</label>
                                <input type="text" class="form-control"
                                    name="employee"
                                    value="{{ old('employee', $drs->deliveryMan->user->name ?? '') }}">
                            </div>
                        </div>

                        {{-- DELIVERY STATUS (FILLED) --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Delivery Status</label>

                            <div class="radio-row">
                                <input type="radio" name="drs_status" value="out_for_delivery"
                                    {{ old('delivery_status', $drs->delivery_status) == 'out_for_delivery' ? 'checked' : '' }}>
                                <span>Out for Delivery</span>
                            </div>

                            <div class="radio-row">
                                <input type="radio" name="drs_status" value="delivered"
                                    {{ old('delivery_status', $drs->delivery_status) == 'delivered' ? 'checked' : '' }}>
                                <span>Delivered</span>
                            </div>

                            <div class="radio-row">
                                <input type="radio" name="drs_status" value="undelivered"
                                    {{ old('delivery_status', $drs->delivery_status) == 'undelivered' ? 'checked' : '' }}>
                                <span>Un Delivered</span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Reason</label>
                                <input type="text" class="form-control"
                                    name="remarks"
                                    value="{{ old('remarks', $drs->remarks) }}">
                            </div>

                            <div class="col-md-8">
                                <label class="form-label">Scan Tracking No</label>
                                <input type="text" class="form-control"
                                    name="scan_tracking_no"
                                    value="{{ old('scan_tracking_no', $drs->scan_tracking_no) }}">
                            </div>
                        </div>

                        <button class="btn btn-primary w-100">Update</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
