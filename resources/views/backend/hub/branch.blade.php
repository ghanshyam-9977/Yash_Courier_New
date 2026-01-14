@extends('backend.partials.master')

@section('title')
    {{ __('hub_payment_request.title') }} {{ __('levels.list') }}
@endsection

@section('maincontent')
    <!-- wrapper -->
    <div class="container-fluid dashboard-content">


        <!-- pageheader -->
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
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
                                    {{ __('levels.list') }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <!-- end pageheader -->

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('branch.filter') }}" method="GET"> @csrf
                            <div class="row">
                                <!-- Branch filter -->
                                <div class="form-group col-12 col-lg-4 col-md-4">
                                    <label for="from_branch">{{ __('Branch') }}</label>
                                    <select name="from_branch" id="from_branch" class="form-control">
                                        <option value="">{{ __('Select Branch') }}</option>
                                        @foreach ($allBranches ?? [] as $branch)
                                            <option value="{{ $branch->id }}"
                                                {{ old('from_branch', $request->from_branch) == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Transport Type filter -->
                                <div class="form-group col-12 col-lg-4 col-md-4">
                                    <label for="transport_type">{{ __('Transport Type') }}</label>
                                    <select name="transport_type" id="transport_type" class="form-control">
                                        <option value="">{{ __('Select Transport') }}</option>
                                        <option value="by_road"
                                            {{ old('transport_type', $request->transport_type) == 'by_road' ? 'selected' : '' }}>
                                            By Road</option>
                                        <option value="by_air"
                                            {{ old('transport_type', $request->transport_type) == 'by_air' ? 'selected' : '' }}>
                                            By Air</option>
                                    </select>
                                </div>

                                <!-- Submit button -->
                                <div class="form-group col-12 col-lg-4 col-md-4  pt-4">
                                    <div class="d-flex">
                                        <a href="{{ route('hub-panel.payment-request.index') }}"
                                            class="btn btn-space btn-secondary"><i class="fa fa-eraser"></i>
                                            {{ __('levels.clear') }}</a>
                                        <button type="submit" class="btn btn-space btn-primary"><i
                                                class="fa fa-filter"></i>
                                            {{ __('levels.filter') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
                <div class="card">
                    <div class="row pl-4 pr-4 pt-4">
                        <div class="col-6">
                            <p class="h3">{{ __('Branch Payment') }} {{ __('levels.list') }}</p>
                        </div>

                        <div class="col-6">
                            <div class="float-right">
                                <a href="{{ route('branch.estimate', request()->all()) }}" class="btn btn-info btn-sm mr-2"
                                    data-toggle="tooltip" data-placement="top" title="Generate Estimate">
                                    <i class="fa fa-file-text-o"></i> Generate Estimate
                                </a>
                                <a href="{{ route('ledger.index') }}" class="btn btn-success btn-sm" data-toggle="tooltip"
                                    data-placement="top" title="View Ledger">
                                    <i class="fa fa-book"></i> Ledger
                                </a>
                                {{-- <a href="{{ route('branch.estimate-perday', request()->all()) }}"
                                    class="btn btn-dark btn-sm mr-2" data-toggle="tooltip" data-placement="top"
                                    title="Generate Estimate ParDay">
                                    <i class="fa fa-file-text-o"></i> Generate Estimate Per Day
                                </a> --}}

                                <a href="{{ route('hub-panel.payment-request.create') }}" class="btn btn-primary btn-sm"
                                    data-toggle="tooltip" data-placement="top" title="{{ __('levels.add') }}">
                                    <i class="fa fa-plus"></i>
                                </a>


                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('levels.id') }}</th>
                                        <th>{{ __('Tracking No') }}</th>
                                        <th>{{ __('Branch to Branch') }}</th>
                                        <th>{{ __('Request Type') }}</th>
                                        <th>{{ __('Item Type') }}</th>
                                        <th>{{ __('Transport Type') }}</th>
                                        <th>{{ __('hub_payment.description') }}</th>
                                        <th>{{ __('Wait') }}</th>


                                        <th>{{ __('hub_payment.amount') }}</th>
                                        <th>{{ __('hub_payment_request.request_date') }}</th>
                                        @if (hasPermission('hub_payment_request_update') || hasPermission('hub_payment_request_delete'))
                                            <th>{{ __('levels.actions') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payments as $payment)
                                        <tr>


                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $payment->tracking_number ?? '' }}</td>

                                            <td>
                                                {{ $hubs[$payment->from_branch_id]->name ?? $payment->from_branch_id }} →
                                                {{ $hubs[$payment->to_branch_id]->name ?? 'N/A' }}
                                            </td>
                                            <td>{{ $payment->request_type ?? '' }}</td>
                                            <td>{{ $payment->item_type ?? '' }}</td>
                                            <td>{{ $payment->transport_type ?? '' }}</td>
                                            <td>{{ $payment->description ?? '' }}</td>

                                            <td>
                                                {{ $payment->quantity ?? 0 }} {{ $payment->unit ?? '' }}
                                            </td>



                                            <td>{{ $payment->amount ?? 0 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y') }}</td>
                                            @if (hasPermission('hub_payment_request_update') || hasPermission('hub_payment_request_delete'))
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-primary btn-sm dropdown-toggle"
                                                            type="button" id="dropdownMenuButton{{ $payment->id }}"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            {{ __('levels.actions') }}
                                                        </button>
                                                        <div class="dropdown-menu"
                                                            aria-labelledby="dropdownMenuButton{{ $payment->id }}">
                                                            @if (hasPermission('hub_payment_request_update'))
                                                                <a class="dropdown-item"
                                                                    href="{{ route('hub-branch-payments.edit', $payment->id) }}">
                                                                    <i class="fas fa-edit"></i> {{ __('levels.edit') }}
                                                                </a>
                                                            @endif

                                                            @if (hasPermission('hub_payment_request_delete'))
                                                                <a class="dropdown-item" href="#"
                                                                    onclick="confirmDelete(event, {{ $payment->id }})">
                                                                    <i class="fa fa-trash"></i> {{ __('levels.delete') }}
                                                                </a>
                                                                <form id="delete-form-{{ $payment->id }}"
                                                                    action="{{ route('hub-branch-payments.destroy', $payment->id) }}"
                                                                    method="POST" style="display: none;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end wrapper -->
@endsection

@push('scripts')
    <script>
        function confirmDelete(event, paymentId) {
            event.preventDefault();
            if (confirm('{{ __('Are you sure you want to delete this payment ? ') }}')) {
                document.getElementById('delete-form-' + paymentId).submit();
            }
        }
    </script>
@endpush
