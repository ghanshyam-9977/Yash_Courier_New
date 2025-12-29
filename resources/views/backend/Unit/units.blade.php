@extends('backend.partials.master')

@section('title')
{{ __('Units') }} {{ __('levels.list') }}
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
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ __('Units') }}
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
                <div class="row pl-4 pr-4 pt-4">
                    <div class="col-6">
                        <p class="h3">{{ __('Units') }}</p>
                    </div>

                    <div class="col-6">
                        <a href="{{ route('unit.create') }}"
                            class="btn btn-primary btn-sm float-right"
                            data-toggle="tooltip"
                            data-placement="top"
                            title="{{ __('levels.add') }}">
                            <i class="fa fa-plus"></i>  {{ __('          Add Unit') }}
                        </a>
                    </div>

                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>{{ __('levels.id') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('QTY') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th>{{ __('Transport') }}</th>
                                    <th>{{ __('Created At') }}</th>

                                    <th>{{ __('levels.actions') }}</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($units as $unit)
                                <tr>
                                    <td>{{ $unit->id }}</td>
                                    <td>{{ $unit->name }}</td>
                                    <td>{{ $unit->quantity }}</td>
                                    <td>{{ $unit->price }}</td>
                                    <td>{{ $unit->transport_type }}</td>
                                    <td>{{ $unit->created_at->format('Y-m-d') }}</td>



                                    <td>



                                        <!-- <form action="{{ route('unit.destroy', $unit->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form> -->
                                        <form action="{{ route('unit.destroy', $unit->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this Unit?') }}');">
                                            @csrf
                                            @method('DELETE')


                                            <button tabindex="-1" data-toggle="dropdown" type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split"><span class="sr-only">Toggle Dropdown</span></button>
                                            <div class="dropdown-menu">

                                                <button type="submit" class="dropdown-item">
                                                    <i class="fa fa-trash" aria-hidden="true"></i> {{ __('levels.delete') }}
                                                </button>


                                                <div style="text-align: center;">
                                                    <a href="{{ route('unit.edit', $unit->id) }}" class="dropdown-item" style="background: none; border: none;  color: inherit; cursor: pointer; display: inline-block;">
                                                        <i class="fa fa-edit" aria-hidden="true"></i> {{ __('levels.edit') }}
                                                    </a>
                                                </div>







                                        </form>


                                    </td>


                                </tr>
                                @endforeach
                            </tbody

                                </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
