@extends('backend.partials.master')

@section('title', 'Fast Booking List')

@push('styles')
    <style>
        .custom-card {
            border-radius: 12px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
            background: #fff;
            border: none;
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #eee;
            padding: 16px 20px;
        }

        .table thead th {
            background: #f8f9fc;
            font-size: 12px;
            text-transform: uppercase;
            color: #6b7280;
            border-bottom: 2px solid #e5e7eb;
        }

        .table td {
            vertical-align: middle;
            font-size: 14px;
        }

        .booking-badge {
            background: #eef2ff;
            padding: 4px 8px;
            border-radius: 6px;
            font-family: monospace;
            font-weight: 600;
        }
    </style>
@endpush

@section('maincontent')
    <div class="container-fluid py-4">
        <div class="custom-card">

            {{-- HEADER --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0 text-primary">Fast Booking List</h4>

                <div class="d-flex gap-2">


                    <!-- Print Options Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="printOptionsDropdown"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Print Options
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="printOptionsDropdown">
                            <a class="dropdown-item" href="#" onclick="printShipper()">Print Shipper</a>
                            <a class="dropdown-item" href="#" onclick="printSticker()">Print Sticker</a>
                        </div>
                    </div>

                    <a href="{{ route('fast_bookings.create') }}" class="btn btn-primary">
                        New Booking
                    </a>
                </div>
            </div>


            {{-- TABLE --}}
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                {{-- <th>Booking No</th> --}}
                                <th>From</th>
                                {{-- <th>To</th> --}}
                                {{-- <th>Payment</th> --}}
                                <th class="text-center">Pcs</th>
                                <th class="text-center">Weight</th>
                                <th class="text-right">Amount</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fastBookings as $key => $booking)
                                <tr>
                                    <td>{{ $fastBookings->firstItem() + $key }}</td>

                                    {{-- <td>
                                        <span class="booking-badge">{{ $booking->booking_no }}</span>
                                    </td> --}}

                                    <td>{{ $booking->sourceHub->name ?? '-' }}</td>
                                    {{-- <td>{{ $booking->destinationHub->name ?? '-' }}</td> --}}


                                    {{-- <td>{{ $booking->payment_type }}</td> --}}

                                    <td class="text-center">{{ $booking->total_pcs }}</td>

                                    <td class="text-center">{{ $booking->total_weight }}</td>

                                    <td class="text-right">{{ number_format($booking->total_amount, 2) }}</td>

                                    {{-- ACTION --}}
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm border dropdown-toggle" type="button"
                                                data-toggle="dropdown">
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item"
                                                    href="{{ route('fast_bookings.edit', $booking->id) }}">
                                                    Edit
                                                </a>
                                                {{-- <a class="dropdown-item"
                                                    href="{{ route('fast_bookings.show', $booking->id) }}">
                                                    View
                                                </a> --}}
                                                <div class="dropdown-divider"></div>
                                                <button class="dropdown-item text-danger"
                                                    onclick="confirmDelete(event, {{ $booking->id }})">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>

                                        <form id="delete-form-{{ $booking->id }}"
                                            action="{{ route('fast_bookings.destroy', $booking->id) }}" method="POST"
                                            class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        No records found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="card-footer bg-white">
                {{ $fastBookings->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmDelete(event, id) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
    <script>
        function printShipper() {
            // Example: redirect to a route that handles Shipper print for fast bookings
            // You can modify this to pass necessary parameters or open a modal etc.
            window.location.href = "{{ route('fast_bookings.print_shipper') }}";
        }

        function printSticker() {
            // Example: redirect to a route that handles Sticker print for fast bookings
            window.location.href = "{{ route('fast_bookings.print_sticker') }}";
        }
    </script>
@endpush
