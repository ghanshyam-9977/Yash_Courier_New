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
                                        <a href="" class="breadcrumb-link">
                                            {{ __('levels.branch') }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item"><a href=""
                                            class="breadcrumb-link active">{{ __('levels.edit') }}</a></li>

                                </ol>
                            </nav>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">{{ __('hub_payment_request.branch_edit_request') }}</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('hub-branch-request-update.done', $hub->id) }}" method="POST">
                                @csrf
                                {{-- Name --}}
                                <div class="form-group">
                                    <label>{{ __('levels.name') }}</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $hub->name) }}" required>
                                </div>

                                {{-- Phone --}}
                                <div class="form-group">
                                    <label>{{ __('levels.phone') }}</label>
                                    <input type="text" name="phone" class="form-control"
                                        value="{{ old('phone', $hub->phone) }}" required>
                                </div>

                                {{-- City --}}
                                <div class="form-group">
                                    <label>{{ __('levels.city') }}</label>
                                    <input type="text" name="city" class="form-control"
                                        value="{{ old('city', $hub->city) }}" required>
                                </div>

                                {{-- Contact Person --}}
                                <div class="form-group">
                                    <label>{{ __('levels.contact_person_name') }}</label>
                                    <input type="text" name="contact_person" class="form-control"
                                        value="{{ old('contact_person', $hub->contact_person) }}" required>
                                </div>

                                {{-- Pincode --}}
                                <div class="form-group">
                                    <label>{{ __('levels.pincode') }}</label>
                                    <input type="text" name="pincode" class="form-control"
                                        value="{{ old('pincode', $hub->pincode) }}" required>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('levels.address') }}</label>
                                    <input type="text" name="address" class="form-control"
                                        value="{{ old('address', $hub->address) }}" required>
                                </div>


                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-primary">{{ __('levels.update') }}</button>
                                    <a href="{{ route('hub-panel.branch-request.index') }}"
                                        class="btn btn-secondary">{{ __('levels.cancel') }}</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
