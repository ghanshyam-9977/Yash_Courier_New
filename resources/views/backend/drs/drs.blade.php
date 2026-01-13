@extends('backend.partials.master')

@section('title', 'DRS List')

@section('maincontent')
<div class="container-fluid py-4">

    {{-- FILTER CARD --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('drs.index') }}" class="row align-items-end">

                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Search DRS No / Area / Delivery Boy"
                           value="{{ request('search') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">From Date</label>
                    <input type="date"
                           name="from_date"
                           class="form-control"
                           value="{{ request('from_date') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">To Date</label>
                    <input type="date"
                           name="to_date"
                           class="form-control"
                           value="{{ request('to_date') }}">
                </div>

                <div class="col-md-4 d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-filter"></i> Filter
                    </button>

                    <a href="{{ route('drs.index') }}" class="btn btn-outline-secondary">
                        Clear
                    </a>
                </div>

            </form>
        </div>
    </div>

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">DRS Entry List</h4>

        <div class="d-flex gap-2">
            <a href="{{ route('drs.estimate') }}" class="btn btn-info">
                Generate Estimate
            </a>

            <a href="{{ route('drs.create') }}" class="btn btn-danger rounded-circle">
                <i class="fas fa-plus"></i>
            </a>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>DRS No</th>
                        <th>Area</th>
                        <th>Date</th>
                        <th>Delivery Man</th>
                        <th>Shipments</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($drsEntries as $key => $drs)
                        <tr>
                            <td>{{ $drsEntries->firstItem() + $key }}</td>
                            <td>{{ $drs->drs_no }}</td>
                            <td>{{ $drs->area_name }}</td>
                            <td>{{ $drs->drs_date->format('d M Y') }}</td>
                            <td>{{ $drs->deliveryMan->user->name ?? 'N/A' }}</td>
                            <td>{{ $drs->shipments->count() }}</td>

                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-danger btn-sm dropdown-toggle"
                                            type="button"
                                            data-toggle="dropdown">
                                        Actions
                                    </button>

                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item"
                                           href="{{ route('drs.edit', $drs->id) }}">
                                            Update DRS
                                        </a>

                                        <div class="dropdown-divider"></div>

                                        <a class="dropdown-item text-danger"
                                           href="javascript:void(0)"
                                           onclick="confirmDelete(event, {{ $drs->id }})">
                                            Delete
                                        </a>
                                    </div>
                                </div>

                                <form id="delete-form-{{ $drs->id }}"
                                      action="{{ route('drs.destroy', $drs->id) }}"
                                      method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                No DRS Found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-between align-items-center mt-3">
        <span class="text-muted">
            Showing {{ $drsEntries->firstItem() ?? 0 }}
            to {{ $drsEntries->lastItem() ?? 0 }}
            of {{ $drsEntries->total() }} entries
        </span>

        {{ $drsEntries->appends(request()->query())->links() }}
    </div>

</div>
@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmDelete(event, id) {
        event.preventDefault();

        Swal.fire({
            title: 'Delete DRS?',
            text: 'Are you sure you want to delete this DRS?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush
