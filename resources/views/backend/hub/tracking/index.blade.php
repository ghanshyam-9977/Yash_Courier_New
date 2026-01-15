@extends('frontend.layouts.master')
@section('title')
    {{ __('levels.parcel_tracking') }} | {{ @settings()->name }}
@endsection
@section('content')
    <!DOCTYPE html>
    <html lang="en">


    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* body {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    min-height: 100vh;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    padding: 40px 0;
                } */

        .container-main {
            max-width: 900px;
            margin: 0 auto;
            margin-top: 10px
            /* margin-top: 10px; */
        }

        .header-section {
            text-align: center;
            color: white;
            margin-bottom: 40px;
            animation: slideDown 0.6s ease-out;
        }

        .header-section h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .header-section p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .search-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            margin-bottom: 40px;
            animation: slideUp 0.6s ease-out;
        }

        .search-form {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .search-form input {
            flex: 1;
            padding: 14px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-form input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-form button {
            padding: 14px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .search-form button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .result-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: fadeIn 0.6s ease-out;
        }

        .tracking-number-badge {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            margin-bottom: 30px;
            font-size: 0.9rem;
        }

        .status-section {
            margin-bottom: 35px;
            padding: 25px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 12px;
            border-left: 5px solid #667eea;
        }

        .status-badge {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            margin-bottom: 12px;
            font-size: 0.9rem;
        }

        .status-text {
            font-size: 1.4rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .status-time {
            color: #718096;
            font-size: 0.9rem;
        }

        .status-location {
            margin-top: 12px;
            color: #2d3748;
            font-weight: 500;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-box {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
        }

        .info-box:hover {
            background: #f0f3ff;
            transform: translateX(5px);
        }

        .info-label {
            color: #667eea;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-value {
            color: #2d3748;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .cod-box {
            padding: 20px;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            border-radius: 10px;
            color: white;
            text-align: center;
        }

        .cod-label {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 8px;
        }

        .cod-amount {
            font-size: 2rem;
            font-weight: 700;
        }

        .timeline {
            position: relative;
            padding: 20px 0;
        }

        .timeline-item {
            padding-left: 40px;
            padding-bottom: 30px;
            position: relative;
            border-left: 2px solid #e0e0e0;
        }

        .timeline-item:last-child {
            border-left-color: transparent;
        }

        .timeline-dot {
            width: 16px;
            height: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            position: absolute;
            left: -9px;
            top: 5px;
            box-shadow: 0 0 0 4px white, 0 0 0 8px #e0e0e0;
        }

        .timeline-item:first-child .timeline-dot {
            width: 20px;
            height: 20px;
            left: -11px;
            box-shadow: 0 0 0 4px white, 0 0 0 8px #667eea;
        }

        .timeline-status {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 5px;
            font-size: 1rem;
        }

        .timeline-location {
            color: #667eea;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .timeline-time {
            color: #718096;
            font-size: 0.85rem;
        }

        .timeline-remarks {
            color: #718096;
            font-size: 0.9rem;
            margin-top: 5px;
            font-style: italic;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: #667eea;
        }

        .divider {
            height: 2px;
            background: linear-gradient(90deg, #e0e0e0 0%, transparent 100%);
            margin: 30px 0;
        }

        .alert-box {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-danger {
            background: #ffe0e0;
            color: #c53030;
            border-left: 4px solid #c53030;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .search-form {
                flex-direction: column;
            }

            .header-section h1 {
                font-size: 1.8rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .search-card,
            .result-card {
                padding: 20px;
            }
        }
    </style>
    </head>

    <div class="container-main">
        <!-- Header -->
        <div class="header-section">
            <h1><i class="fas fa-box"></i> Tracking</h1>
            <p>Real-time tracking for your consignments</p>
        </div>

        <!-- Search Form -->
        <div class="search-card">
            <form action="{{ route('hub.track.search') }}" method="GET" class="search-form">
                <input type="text" name="tracking_number" placeholder="Enter Tracking Number (e.g. CN202601001)"
                    value="{{ old('tracking_number') ?? request('tracking_number') }}" required autofocus>
                <button type="submit">
                    <i class="fas fa-search"></i> Track
                </button>
            </form>
        </div>

        <!-- Errors -->
        @error('tracking_number')
            <div class="alert-box alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $message }}</span>
            </div>
        @enderror

        @if (session('error'))
            <div class="alert-box alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Results -->
        @if (isset($consignment) && isset($currentStatus))
            <div class="result-card">
                <!-- Tracking Number Badge -->
                <div class="tracking-number-badge">
                    <i class="fas fa-barcode"></i> {{ $consignment->tracking_number }}
                </div>

                <!-- Current Status -->
                <div class="status-section">
                    <div class="status-badge">
                        <i class="fas fa-check-circle"></i> Current Status
                    </div>
                    <div class="status-text">
                        {{ ucfirst(str_replace('_', ' ', $currentStatus->status)) }}
                    </div>
                    <div class="status-time">
                        <i class="fas fa-clock"></i> {{ $currentStatus->created_at->format('d M Y, h:i A') }}
                    </div>
                    @if ($currentStatus->location)
                        <div class="status-location">
                            <i class="fas fa-map-marker-alt"></i> {{ $currentStatus->location }}
                        </div>
                    @endif
                    @if ($currentStatus->remarks)
                        <div class="status-location" style="margin-top: 12px; color: #718096; font-weight: normal;">
                            {{ $currentStatus->remarks }}
                        </div>
                    @endif
                </div>

                <!-- Info Grid -->
                <div class="info-grid">
                    <!-- From Branch -->
                    <div class="info-box">
                        <div class="info-label">
                            <i class="fas fa-arrow-right"></i> From
                        </div>
                        <div class="info-value">
                            {{ $consignment->fromBranch->name ?? $consignment->from_branch_id }}
                        </div>
                    </div>

                    <!-- To Branch -->
                    <div class="info-box">
                        <div class="info-label">
                            <i class="fas fa-arrow-right"></i> To
                        </div>
                        <div class="info-value">
                            {{ $consignment->toBranch->name ?? $consignment->to_branch_id }}
                        </div>
                    </div>

                    <!-- Manifest -->
                    <div class="info-box">
                        <div class="info-label">
                            <i class="fas fa-file-alt"></i> Manifest
                        </div>
                        <div class="info-value">
                            {{ $consignment->manifest_no ?? 'N/A' }}
                        </div>
                    </div>

                    <!-- Vehicle -->
                    <div class="info-box">
                        <div class="info-label">
                            <i class="fas fa-truck"></i> Transport
                        </div>
                        <div class="info-value">
                            {{ $consignment->vehicle_no ?? 'N/A' }}<br>
                            <span style="font-size: 0.85rem; color: #667eea;">
                                {{ ucfirst($consignment->transport_type) }}
                            </span>
                        </div>
                    </div>

                    <!-- COD -->
                    @if ($consignment->is_cod)
                        <div class="cod-box">
                            <div class="cod-label">COD Amount</div>
                            <div class="cod-amount">
                                ₹{{ number_format($consignment->cod_amount ?? 0, 2) }}
                            </div>
                        </div>
                    @endif
                </div>

                <div class="divider"></div>

                <!-- Tracking History Timeline -->
                <div class="section-title">
                    <i class="fas fa-history"></i> Tracking History
                </div>

                <div class="timeline">
                    @foreach ($trackingHistory as $row)
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-status">
                                {{ ucfirst(str_replace('_', ' ', $row->status)) }}
                            </div>
                            @if ($row->location)
                                <div class="timeline-location">
                                    <i class="fas fa-map-pin"></i> {{ $row->location }}
                                </div>
                            @endif
                            <div class="timeline-time">
                                {{ $row->created_at->format('d M Y, h:i A') }}
                            </div>
                            @if ($row->remarks)
                                <div class="timeline-remarks">
                                    {{ $row->remarks }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    </html>

@endsection
@push('styles')
    <link rel="stylesheet" href="{{ static_asset('frontend/css/timeline.css') }}" />
@endpush
