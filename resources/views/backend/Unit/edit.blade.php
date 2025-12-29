@extends('backend.partials.master')

@section('title')
    Edit Unit
@endsection

@section('maincontent')
<div class="container-fluid dashboard-content">
    <div class="row">
        <div class="col-12">    
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="pageheader-title mb-4">Edit Unit</h2>

                    <form method="POST" action="{{ route('unit.update', $unit->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $unit->name }}">
                        </div>

                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" name="quantity" class="form-control" value="{{ $unit->quantity }}">
                        </div>

                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" name="price" step="0.01" class="form-control" value="{{ $unit->price }}">
                        </div>

                        <div class="form-group">
                            <label>Transport Type</label>
                            <select name="transport_type" class="form-control">
                                <option value="By Road" {{ $unit->transport_type == 'By Road' ? 'selected' : '' }}>By Road</option>
                                <option value="By Air" {{ $unit->transport_type == 'By Air' ? 'selected' : '' }}>By Air</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Unit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
