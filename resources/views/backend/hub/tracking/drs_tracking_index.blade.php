@extends('backend.partials.master')
@section('title', 'DRS Tracking')

@section('maincontent')
    <style>
        body {
            margin: 0;
            font-family: system-ui, -apple-system, sans-serif;
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
        h1 { text-align: center; margin-bottom: 5px; }
        .subtitle { text-align: center; color: #64748b; margin-bottom: 25px; }
        .search-box {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .search-box input {
            flex: 1;
            padding: 14px 16px;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            font-size: 15px;
        }
        .search-box button {
            padding: 14px 28px;
            border: none;
            border-radius: 10px;
            background: #4f46e5;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .search-box button:hover { background: #4338ca; }
        .result { display: none; margin-top: 25px; }
        .summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            background: #f1f5f9;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
        }
        .summary span { font-size: 12px; color: #64748b; }
        .summary strong { font-size: 16px; display: block; margin-top: 4px; }
        .extra-info {
            margin-bottom: 20px;
            background: #f1f5f9;
            padding: 20px;
            border-radius: 12px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 16px;
        }
        .extra-info div { font-size: 14px; color: #334155; }
        .extra-info span { font-weight: 600; }
        .cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 25px;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #4f46e5;
        }
        .progress-box { margin: 30px 0; }
        .progress {
            height: 10px;
            background: #e5e7eb;
            border-radius: 6px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(to right, #4f46e5, #7c3aed);
            transition: width 0.8s ease;
        }
        .steps {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            color: #64748b;
            margin-top: 8px;
        }
        .timeline { margin-top: 30px; }
        .timeline h3 { margin-bottom: 16px; color: #1e293b; }
        .timeline ul { list-style: none; padding: 0; }
        .timeline li {
            background: white;
            padding: 16px;
            border-radius: 10px;
            border-left: 4px solid #4f46e5;
            margin-bottom: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        .message {
            margin: 15px 0;
            padding: 12px 16px;
            border-radius: 8px;
            font-weight: 500;
            text-align: center;
        }
        .error   { background: #fee2e2; color: #991b1b; }
        .warning { background: #fef3c7; color: #92400e; border: 1px solid #fbbf24; }
        @media (max-width: 640px) {
            .summary, .cards, .extra-info { grid-template-columns: 1fr; }
            .search-box { flex-direction: column; }
            .search-box button { width: 100%; }
        }
    </style>

    <div class="wrapper">
        <h1>📦 DRS Tracking</h1>
        <p class="subtitle">Track your consignment / DRS status</p>

        <div class="search-box">
            <input id="trackingInput" placeholder="Enter Consignment No or DRS No" autocomplete="off">
            <button onclick="track()">Track</button>
        </div>

        <div id="multiple-hint"></div>
        <div id="message" class="message"></div>

        <div class="result" id="result">
            <div class="summary">
                <div><span>Tracking No</span><strong id="trackingNo"></strong></div>
                <div><span>Status</span><strong id="status"></strong></div>
                <div><span>Service</span><strong id="service"></strong></div>
            </div>

            <div class="extra-info">
                <div><span>Receiver:</span> <span id="receiver"></span></div>
                <div><span>Weight:</span> <span id="weight"></span> kg</div>
                <div><span>Pieces:</span> <span id="pcs"></span></div>
                <div><span>DRS No:</span> <span id="drsNo"></span></div>
                <div><span>DRS Date:</span> <span id="drsDate"></span></div>
            </div>

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

            <div class="progress-box">
                <div class="progress">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
                <div class="steps">
                    <span>Picked Up</span>
                    <span>In Transit</span>
                    <span>Delivered</span>
                </div>
            </div>

            <div class="timeline">
                <h3>📜 Tracking History</h3>
                <ul id="timeline"></ul>
            </div>
        </div>
    </div>

    <script>
        let currentConsignments = [];  // ← Global storage for all fetched data

        function displayConsignment(item) {
            const resultEl = document.getElementById('result');
            resultEl.style.display = 'block';

            document.getElementById('trackingNo').innerText = item.trackingNo || 'N/A';
            document.getElementById('status').innerText     = item.status     || 'N/A';
            document.getElementById('service').innerText    = item.serviceType || item.service || 'N/A';

            document.getElementById('receiver').innerText = item.receiver || 'N/A';
            document.getElementById('weight').innerText   = item.weight   || 'N/A';
            document.getElementById('pcs').innerText      = item.pcs      || 'N/A';
            document.getElementById('drsNo').innerText    = item.drsNo    || 'N/A';

            // Format DRS Date
            let drsDateStr = 'N/A';
            if (item.drsDate) {
                try {
                    drsDateStr = new Date(item.drsDate).toLocaleDateString('en-GB', {
                        day: '2-digit', month: 'short', year: 'numeric'
                    });
                } catch (e) {}
            }
            document.getElementById('drsDate').innerText = drsDateStr;

            document.getElementById('origin').innerText      = item.area || item.origin || item.pickupLocation || 'N/A';
            document.getElementById('destination').innerText = item.address || item.destination || item.deliveryAddress || 'N/A';

            // Progress
            document.getElementById('progressFill').style.width = (item.progress ?? 0) + '%';

            // Timeline
            const timelineEl = document.getElementById('timeline');
            let html = '';

            if (item.updates && Array.isArray(item.updates) && item.updates.length > 0) {
                item.updates.forEach(update => {
                    html += `
                        <li>
                            <strong>${update.location && update.location !== '-' ? update.location : ''}</strong><br>
                            ${update.remarks || update.status || ''}<br>
                            <small style="color:#64748b;">${update.time || ''}</small>
                        </li>
                    `;
                });
            } else {
                html = '<li style="color:#64748b; font-style:italic; text-align:center; padding: 20px 0;">No tracking updates available yet</li>';
            }
            timelineEl.innerHTML = html;
        }

        function showMultipleSelector(consignments) {
            const hintEl = document.getElementById('multiple-hint');

            const options = consignments.map((item, idx) =>
                `<option value="${idx}">${item.trackingNo || 'N/A'} – ${item.receiver || 'Unknown'} (${item.weight || '?'} kg)</option>`
            ).join('');

            hintEl.innerHTML = `
                <div class="message warning" style="margin-bottom: 20px;">
                    <strong>Multiple consignments found (${consignments.length}) under DRS: ${consignments[0]?.drsNo || '—'}</strong><br>
                    Select any consignment to view details:
                </div>
                <div style="margin: 20px 0; font-weight: 600;">
                    Consignment:
                    <select id="consignmentSelect" style="padding: 10px 14px; font-size: 15px; border-radius: 8px; border: 1px solid #cbd5e1; min-width: 320px; background: white;">
                        ${options}
                    </select>
                </div>
            `;

            document.getElementById('consignmentSelect').addEventListener('change', (e) => {
                const index = parseInt(e.target.value);
                displayConsignment(consignments[index]);
            });
        }

        function track() {
            const input = document.getElementById('trackingInput');
            const number = input.value.trim();
            const messageEl = document.getElementById('message');
            const hintEl = document.getElementById('multiple-hint');
            const resultEl = document.getElementById('result');

            if (!number) {
                messageEl.className = 'message error';
                messageEl.innerText = 'Please enter a tracking number or DRS number';
                resultEl.style.display = 'none';
                hintEl.innerHTML = '';
                return;
            }

            messageEl.innerText = '';
            hintEl.innerHTML = '';
            resultEl.style.display = 'none';

            fetch(`{{ url('/admin/drs-track') }}?tracking_no=${encodeURIComponent(number)}`)
                .then(res => res.json())
                .then(res => {
                    if (!res.success) {
                        messageEl.className = 'message error';
                        messageEl.innerText = res.message || 'No record found';
                        return;
                    }

                    let data = res.data;

                    if (Array.isArray(data)) {
                        if (data.length === 0) {
                            messageEl.className = 'message error';
                            messageEl.innerText = 'No consignments found under this DRS';
                            return;
                        }

                        currentConsignments = data;  // Store globally

                        if (data.length === 1) {
                            displayConsignment(data[0]);
                        } else {
                            showMultipleSelector(data);
                            displayConsignment(data[0]); // Show first by default
                        }
                    } else {
                        currentConsignments = [data];
                        displayConsignment(data);
                    }
                })
                .catch(err => {
                    console.error(err);
                    messageEl.className = 'message error';
                    messageEl.innerText = 'Something went wrong. Please try again later.';
                    resultEl.style.display = 'none';
                    hintEl.innerHTML = '';
                });
        }
    </script>
@endsection
