@extends('backend.partials.master')

@section('title')
    {{ __('unit.title') }} {{ __('levels.add') }}
@endsection

@section('maincontent')
<div class="container-fluid dashboard-content">
    <!-- Page Header -->
    <div class="row mb-4">
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
                            <li class="breadcrumb-item active" aria-current="page">{{ __('levels.create') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-sm-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="pageheader-title mb-4">{{ __('hub.create_unit') }}</h2>

                    <form method="POST" action="{{ route('unit.store') }}"  enctype="multipart/form-data"  id="basicform">
                        @csrf

                        <!-- Unit Name Field -->
                        <div class="form-group">
                            <label for="name" class="font-weight-bold">{{ __('levels.name') }}</label>
                            <span class="text-danger">*</span>
                            <input id="name" type="text" name="name" placeholder="Enter unit name"
                                class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                            @error('name')
                                <small class="text-danger mt-2">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Quantity Field -->
                        <div class="form-group">
                            <label for="quantity" class="font-weight-bold">{{ __('quantity') }}</label>
                            <span class="text-danger">*</span>
                            <input id="quantity" type="number" name="quantity" placeholder="Enter quantity"
                                class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}">
                            @error('quantity')
                                <small class="text-danger mt-2">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Price Field -->
                        <div class="form-group">
                            <label for="price" class="font-weight-bold">{{ __('price') }}</label>
                            <span class="text-danger">*</span>
                            <input id="price" type="number" name="price" step="0.01" placeholder="Enter unit price"
                                class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}">
                            @error('price')
                                <small class="text-danger mt-2">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Transport Type Field -->
                        <div class="form-group">
                            <label for="transport_type" class="font-weight-bold">{{ __('transport_type') }}</label>
                            <span class="text-danger">*</span>
                            <select name="transport_type" id="transport_type" class="form-control @error('transport_type') is-invalid @enderror">
                                <option value="By Road" {{ old('transport_type') == 'By Road' ? 'selected' : '' }}>By Road</option>
                                <option value="By Air" {{ old('transport_type') == 'By Air' ? 'selected' : '' }}>By Air</option>
                            </select>
                            @error('transport_type')
                                <small class="text-danger mt-2">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg">{{ __('levels.save') }}</button>
                                <a href="{{ route('unit.index') }}"
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
