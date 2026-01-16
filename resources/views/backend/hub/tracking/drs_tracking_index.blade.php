@extends('backend.partials.master')

@section('title', 'DRS Tracking')

@section('maincontent')
    <style>
        body {
            margin: 0;
            font-family: system-ui, sans-serif;
            background: linear-gradient(135deg, #eef2ff, #f8fafc);
        }

        .wrapper {
            max-width: 900px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .15);
        }

        h1 {
            text-align: center;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            color: #64748b;
            margin-bottom: 30px;
        }

        .search-box {
            display: flex;
            gap: 10px;
        }

        .search-box input {
            flex: 1;
            padding: 14px;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            font-size: 15px;
        }

        .search-box button {
            padding: 14px 24px;
            border: none;
            border-radius: 10px;
            background: #4f46e5;
            color: white;
            font-weight: 600;
            cursor: pointer;
        }

        .result {
            display: none;
            margin-top: 30px;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            background: #f1f5f9;
            padding: 20px;
            border-radius: 12px;
        }

        .summary span {
            font-size: 12px;
            color: #64748b;
        }

        .summary strong {
            font-size: 16px;
            display: block;
            margin-top: 5px;
        }

        .extra-info {
            margin-top: 20px;
            background: #f1f5f9;
            padding: 20px;
            border-radius: 12px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 15px;
        }

        .extra-info div {
            font-size: 14px;
            color: #334155;
        }

        .extra-info span {
            font-weight: 600;
        }

        .cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 20px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #4f46e5;
        }

        .progress-box {
            margin-top: 30px;
        }

        .progress {
            height: 10px;
            background: #e5e7eb;
            border-radius: 6px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(to right, #4f46e5, #9333ea);
            transition: width .6s;
        }

        .steps {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            color: #64748b;
            margin-top: 6px;
        }

        .timeline {
            margin-top: 35px;
        }

        .timeline h3 {
            margin-bottom: 15px;
        }

        .timeline ul {
            list-style: none;
            padding: 0;
        }

        .timeline li {
            background: white;
            padding: 15px;
            border-radius: 10px;
            border-left: 4px solid #4f46e5;
            margin-bottom: 10px;
        }

        .error {
            margin-top: 20px;
            color: red;
            text-align: center;
        }

        @media(max-width:640px) {
            .summary,
            .cards,
            .extra-info {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="wrapper">
        <h1>📦 DRS Tracking</h1>
        <p class="subtitle">Track your consignment status</p>

        <div class="search-box">
            <input id="trackingInput" placeholder="Enter Consignment / DRS No">
            <button onclick="track()">Track</button>
        </div>

        <div id="error" class="error"></div>

        <div class="result" id="result">
            <!-- SUMMARY -->
            <div class="summary">
                <div>
                    <span>Tracking No</span>
                    <strong id="trackingNo"></strong>
                </div>
                <div>
                    <span>Status</span>
                    <strong id="status"></strong>
                </div>
                <div>
                    <span>Service</span>
                    <strong id="service"></strong>
                </div>
            </div>

            <!-- EXTRA INFO -->
            <div class="extra-info">
                <div><span>Receiver:</span> <span id="receiver"></span></div>
                <div><span>Weight:</span> <span id="weight"></span> kg</div>
                <div><span>Pieces:</span> <span id="pcs"></span></div>
                <div><span>DRS No:</span> <span id="drsNo"></span></div>
                <div><span>DRS Date:</span> <span id="drsDate"></span></div>
            </div>

            <!-- LOCATIONS -->
            <div class="cards">
                <div class="card">
                    <h3>📍 Pickup</h3>
                    <p id="origin"></p>
                </div>
                <div class="card">
                    <h3>🏁 Delivery</h3>
                    <p id="destination"></p>
                </div>
            </div>

            <!-- PROGRESS -->
            <div class="progress-box">
                <div class="progress">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
                <div class="steps">
                    <span>Picked</span>
                    <span>In Transit</span>
                    <span>Delivered</span>
                </div>
            </div>

            <!-- TIMELINE -->
            <div class="timeline">
                <h3>📜 Tracking History</h3>
                <ul id="timeline"></ul>
            </div>
        </div>
    </div>

    <script>
        function track() {
            const number = document.getElementById('trackingInput').value.trim();
            const error = document.getElementById('error');

            if (!number) {
                error.innerText = 'Please enter tracking number';
                return;
            }

            error.innerText = '';
            fetch(`{{ url('/admin/drs-track') }}?tracking_no=${number}`)
                .then(res => res.json())
                .then(res => {
                    if (!res.success) {
                        document.getElementById('result').style.display = 'none';
                        error.innerText = res.message;
                        return;
                    }

                    const d = res.data;
                    document.getElementById('result').style.display = 'block';

                    document.getElementById('trackingNo').innerText = d.trackingNo || 'N/A';
                    document.getElementById('status').innerText = d.status || 'N/A';
                    document.getElementById('service').innerText = d.serviceType || 'N/A';

                    // Extra info
                    document.getElementById('receiver').innerText = d.receiver || 'N/A';
                    document.getElementById('weight').innerText = d.weight || 'N/A';
                    document.getElementById('pcs').innerText = d.pcs || 'N/A';
                    document.getElementById('drsNo').innerText = d.drsNo || 'N/A';

                    // Format date to readable form or fallback
                    let drsDate = 'N/A';
                    if (d.drsDate) {
                        drsDate = new Date(d.drsDate).toLocaleDateString('en-GB', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric',
                        });
                    }
                    document.getElementById('drsDate').innerText = drsDate;

                    // Locations
                    document.getElementById('origin').innerText = d.area || 'N/A';
                    document.getElementById('destination').innerText = d.address || 'N/A';

                    document.getElementById('progressFill').style.width = (d.progress ?? 0) + '%';

                    let html = '';
                    d.updates.forEach(u => {
                        html += `
                            <li>
                                <strong>${u.location === '-' ? '' : u.location}</strong><br>
                                ${u.remarks || ''}<br>
                                <small>${u.time || ''}</small>
                            </li>
                        `;
                    });

                    document.getElementById('timeline').innerHTML = html;
                })
                .catch(() => {
                    document.getElementById('result').style.display = 'none';
                    error.innerText = 'Server error. Try again later';
                });
        }
    </script>
@endsection
