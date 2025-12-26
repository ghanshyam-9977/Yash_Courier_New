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
                                <a href="{{ route('hub-panel.payment-request.index') }}" class="breadcrumb-link">
                                    {{ __('hub_payment_request.title') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ __('levels.edit') }}
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ __('hub_payment_request.title') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('hub-branch-payments.done', $payment->id) }}" method="POST">
                        @csrf





                <div class="form-group">
                            <label for="from_branch_id">{{ __('From Branch') }}</label>
                            <input type="text" name="from_branch_id" class="form-control" value="{{ $payment->from_branch_id }}" required>
                        </div>

               <div class="form-group">
                            <label for="to_branch_id">{{ __('To Branch') }}</label>
                            <input type="text" name="to_branch_id" class="form-control" value="{{ $payment->to_branch_id }}" required>
                        </div>

                        <div class="form-group">
                            <label for="transport_type">{{ __('Transport Type') }}</label>
                            <select name="transport_type" class="form-control" required>
                                <option value="by_road" {{ $payment->transport_type == 'by_road' ? 'selected' : '' }}>{{ __('By Road') }}</option>
                                <option value="by_air" {{ $payment->transport_type == 'by_air' ? 'selected' : '' }}>{{ __('By Air') }}</option>
                            </select>
                            @error('transport_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">{{ __('Description') }}</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $payment->description) }}</textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="amount">{{ __('Amount') }}</label>
                            <input type="number" name="amount" class="form-control" value="{{ old('amount', $payment->amount) }}" step="0.01" min="0" required>
                            @error('amount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="quantity">{{ __('Quantity') }}</label>
                            <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $payment->quantity) }}" step="0.01" min="0" required>
                            @error('quantity')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                <div class="form-group">
    <label for="unit">{{ __('Unit') }}</label>
    <select name="unit" class="form-control" required>
        <option value="kg" {{ $payment->unit == 'kg' ? 'selected' : '' }}>{{ __('kg') }}</option>
        <option value="gram" {{ $payment->unit == 'gram' ? 'selected' : '' }}>{{ __('gram') }}</option>
        <option value="liter" {{ $payment->unit == 'liter' ? 'selected' : '' }}>{{ __('liter') }}</option>
        <option value="ml" {{ $payment->unit == 'ml' ? 'selected' : '' }}>{{ __('ml') }}</option>
    </select>
    @error('unit')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">{{ __('levels.update') }}</button>
                            <a href="{{ route('hub-panel.payment-request.index') }}" class="btn btn-secondary">{{ __('levels.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
