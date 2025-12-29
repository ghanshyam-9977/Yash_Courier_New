@extends('backend.partials.master')

@section('title')
    {{ __('hub_payment_request.edit') }} - {{ strtoupper($payment->request_type) }} ({{ ucfirst($payment->item_type ?? 'N/A') }})
@endsection

@section('maincontent')
<div class="container-fluid dashboard-content">
    <div class="row">
        <div class="col-12">
            <!-- Breadcrumb -->
            <div class="page-header">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">{{ __('levels.dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('hub-panel.payment-request.index') }}">{{ __('hub_payment_request.title') }}</a></li>
                        <li class="breadcrumb-item active">
                            {{ __('levels.edit') }}
                            <span class="badge badge-pill ml-2 {{ $payment->request_type == 'out' ? 'badge-success' : 'badge-info' }}">
                                {{ strtoupper($payment->request_type) }}
                            </span>
                            <span class="badge badge-pill ml-2 
                                {{ $payment->item_type == 'document' ? 'badge-primary' : 
                                   ($payment->item_type == 'parcel' ? 'badge-warning' : 'badge-danger') }}">
                                {{ ucfirst($payment->item_type ?? 'N/A') }}
                            </span>
                            @if($payment->is_cod ?? false)
                                <span class="badge badge-danger ml-2">COD ₹{{ number_format($payment->cod_amount ?? 0, 2) }}</span>
                            @endif
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        Editing Consignment Request
                        <small class="float-right">
                            Tracking: <strong>{{ $payment->tracking_number ?? 'N/A' }}</strong>
                        </small>
                    </h4>
                </div>

                <div class="card-body">
                    <!-- Top Summary Alert -->
                    <div class="alert {{ $payment->request_type == 'out' ? 'alert-success' : 'alert-info' }} mb-4">
                        <strong>
                            @if($payment->request_type == 'out')
                                <i class="fas fa-arrow-up"></i> OUTGOING Consignment – Sent from your branch
                            @else
                                <i class="fas fa-arrow-down"></i> INCOMING Consignment – Received at your branch
                            @endif
                        </strong>
                        <hr class="my-2">
                        <small>
                            Item: <strong>{{ ucfirst($payment->item_type ?? 'Not Set') }}</strong> |
                            Transport: <strong>{{ $payment->transport_type == 'by_air' ? 'By Air' : 'By Road' }}</strong> |
                            Vehicle: <strong>{{ $payment->vehicle_no ?? 'Not Provided' }}</strong> |
                            City: <strong>{{ $payment->city ?? 'N/A' }}, {{ $payment->state ?? 'N/A' }}</strong>
                        </small>
                    </div>

                    <form action="{{ route('hub-branch-payments.done', $payment->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Left: All Consignment Details (Read Only) -->
                            <div class="col-lg-6">
                                <h5 class="text-primary mb-4"><i class="fas fa-info-circle"></i> Consignment Information</h5>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>Item Type</strong></label>
                                            <input type="text" class="form-control font-weight-bold" 
                                                   value="{{ ucfirst($payment->item_type ?? 'N/A') }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>Tracking / Consignment No</strong></label>
                                            <input type="text" class="form-control" 
                                                   value="{{ $payment->tracking_number ?? 'N/A' }}" disabled>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>Vehicle No</strong></label>
                                            <input type="text" class="form-control" 
                                                   value="{{ $payment->vehicle_no ?? 'Not Provided' }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>Transport Type</strong></label>
                                            <input type="text" class="form-control" 
                                                   value="{{ $payment->transport_type == 'by_air' ? 'By Air' : 'By Road' }}" disabled>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>City</strong></label>
                                            <input type="text" class="form-control" value="{{ $payment->city ?? 'N/A' }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>State</strong></label>
                                            <input type="text" class="form-control" value="{{ $payment->state ?? 'N/A' }}" disabled>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><strong>
                                                @if($payment->request_type == 'out') From Branch (Sender) @else From Branch (Sender) @endif
                                            </strong></label>
                                            <input type="text" class="form-control" 
                                                   value="{{ $payment->fromBranch?->name ?? 'ID: '.$payment->from_branch_id }}" disabled>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><strong>
                                                @if($payment->request_type == 'out') To Branch (Receiver) @else Your Branch (Receiver) @endif
                                            </strong></label>
                                            <input type="text" class="form-control" 
                                                   value="{{ $payment->toBranch?->name ?? 'ID: '.$payment->to_branch_id }}" disabled>
                                        </div>
                                    </div>

                                    <!-- COD Full Details -->
                                    @if($payment->is_cod ?? false)
                                        <div class="col-12">
                                            <div class="alert alert-warning mt-3">
                                                <h6><i class="fas fa-money-bill-wave"></i> <strong>COD (Cash on Delivery)</strong></h6>
                                                <p class="mb-1"><strong>Amount to Collect:</strong> ₹{{ number_format($payment->cod_amount ?? 0, 2) }}</p>
                                                <p class="mb-1"><strong>Preferred Mode:</strong> {{ ucfirst($payment->cod_payment_mode ?? 'Any') }}</p>
                                                <p class="mb-0"><strong>Remarks:</strong> {{ $payment->cod_remarks ?? 'None' }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Right: Editable Fields -->
                            <div class="col-lg-6">
                                <h5 class="text-primary mb-4"><i class="fas fa-edit"></i> Editable Fields</h5>

                                <div class="form-group">
                                    <label for="transport_type"><strong>Transport Type</strong></label>
                                    <select name="transport_type" id="transport_type" class="form-control" required>
                                        <option value="by_road" {{ $payment->transport_type == 'by_road' ? 'selected' : '' }}>By Road</option>
                                        <option value="by_air" {{ $payment->transport_type == 'by_air' ? 'selected' : '' }}>By Air</option>
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="unit"><strong>Weight / Volume Unit</strong></label>
                                            <select name="unit" id="unit" class="form-control" required>
                                                <option value="kg" {{ $payment->unit == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                                <option value="gram" {{ $payment->unit == 'gram' ? 'selected' : '' }}>Gram (g)</option>
                                                <option value="liter" {{ $payment->unit == 'liter' ? 'selected' : '' }}>Liter (L)</option>
                                                <option value="ml" {{ $payment->unit == 'ml' ? 'selected' : '' }}>Milliliter (ml)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="quantity"><strong>Quantity</strong></label>
                                            <input type="number" name="quantity" id="quantity" class="form-control"
                                                   value="{{ old('quantity', $payment->quantity) }}" step="any" min="0" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="amount"><strong>Amount (₹)</strong></label>
                                    <input type="number" name="amount" id="amount" class="form-control"
                                           value="{{ old('amount', $payment->amount) }}" step="0.01" min="0" required>
                                </div>

                                <div class="form-group">
                                    <label for="description"><strong>Description / Notes</strong></label>
                                    <textarea name="description" id="description" rows="6" class="form-control">{{ old('description', $payment->description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-right mt-4">
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                <i class="fas fa-save"></i> Update Request
                            </button>
                            <a href="{{ route('hub-panel.payment-request.index') }}" class="btn btn-secondary btn-lg ml-3">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection