<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Yes Couriers Invoice</title>
  <style>
    body {
      font-family: Courier, monospace;
      font-size: 12px;
      margin: 25px;
      color: #000;
    }

    h2,
    h3,
    p {
      text-align: center;
      margin: 0;
    }

    h3 {
      font-size: 16px;
      text-transform: uppercase;
    }

    h4 {
      font-size: 14px;
      text-transform: uppercase;
      margin: 20px 0 10px 0;
      background-color: #f4f4f4;
      padding: 8px;
      border: 1px solid #000;
    }

    hr {
      border: 1px solid #000;
      margin: 10px 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th,
    td {
      border: 1px solid #000;
      padding: 5px 6px;
      text-align: right;
    }

    th:first-child,
    td:first-child {
      text-align: left;
    }

    th {
      background-color: #f4f4f4;
      font-weight: bold;
    }

    .header-info {
      text-align: center;
      margin-top: 5px;
      line-height: 1.5;
    }

    .invoice-details {
      text-align: left;
      margin-top: 15px;
      font-size: 12px;
      line-height: 1.5;
    }

    .section-break {
      margin: 30px 0;
      page-break-inside: avoid;
    }

    .totals {
      width: 40%;
      float: right;
      margin-top: 15px;
      border-collapse: collapse;
      font-weight: bold;
    }

    .totals td {
      border: 1px solid #000;
      padding: 6px;
    }

    .sign {
      clear: both;
      margin-top: 50px;
      text-align: right;
      font-weight: bold;
    }

    .note {
      font-size: 11px;
      margin-top: 10px;
    }

    .grand-total {
      width: 40%;
      float: right;
      margin-top: 30px;
      border-collapse: collapse;
      font-weight: bold;
      background-color: #e0e0e0;
    }

    .grand-total td {
      border: 2px solid #000;
      padding: 8px;
      font-size: 14px;
    }

    @media print {
      body {
        margin: 10mm;
      }

      .no-print {
        display: none;
      }
    }
  </style>
</head>

<body>
  <h3>YES COURIERS NETWORK SERVICE</h3>
  <p><b>ORIGINAL FOR RECIPIENT</b></p>
  <p class="header-info">
    SAC CODE: 996812<br>
    Regd. Office: PANT KEDAR DUTT GOPARAO, LALKUAN, NAINITAL - 263001<br>
    PAN NO: ECDP2967L | GSTIN NO: 05ECDP2967L2Z4<br>
    Email: dayakishanpant@gmail.com | Ph: 885914224, 9759531783
  </p>

  <hr>

  <div class="invoice-details">
    <b>Bill No:</b> {{ $invoice->bill_no ?? '198' }}<br>
    <b>Date:</b> {{ \Carbon\Carbon::parse($invoice->date ?? now())->format('d.m.Y') }}<br>
    <b>To:</b> M/S RKT WBO HDFC BANK LTD, Ranikhet, Uttarakhand
  </div>

  <!-- OUT RECORDS SECTION -->
  <div class="section-break">
    <h4>📤 OUTGOING PAYMENTS (FROM YOUR BRANCH)</h4>
    <table>
      <thead>
        <tr>
          <th>SN</th>
          <th>Tracking No</th>
          <th>Date</th>
          <th>From → To</th>
          <th>Request Type</th>
          <th>Item Type</th>
          <th>Transport Type</th>
          <th>Weight</th>
          <th>Unit</th>
          <th>Amount</th>
        </tr>
      </thead>
      <tbody>
        @php $sn = 1; $outTotal = 0; @endphp
        @forelse($outRecords as $record)
        @php $outTotal += $record->amount; @endphp
        <tr>
          <td>{{ $sn++ }}</td>
          <td>{{ $record->tracking_number ?? '' }}</td>
          <td>{{ \Carbon\Carbon::parse($record->created_at)->format('d.m.y') }}</td>
          <td>
            {{ $hubs[$record->from_branch_id]->name ?? $record->from_branch_id }} →
            {{ $hubs[$record->to_branch_id]->name ?? 'N/A' }}
          </td>
          <td>{{ $record->request_type ?? '' }}</td>
          <td>{{ $record->item_type ?? '' }}</td>
          <td>{{ strtoupper(str_replace('_', ' ', $record->transport_type)) }}</td>
          <td>{{ number_format($record->quantity) }}</td>
          <td>{{ $record->unit }}</td>
          <td>{{ number_format($record->amount, 2) }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="10" style="text-align: center;">No outgoing records found</td>
        </tr>
        @endforelse
      </tbody>
    </table>

    <table class="totals">
      <tr>
        <td>OUT Total Amount</td>
        <td style="text-align:right;">₹ {{ number_format($outTotal, 2) }}</td>
      </tr>
    </table>
  </div>

  <div style="clear: both; margin: 40px 0;"></div>

  <!-- IN RECORDS SECTION -->
  <div class="section-break">
    <h4>📥 INCOMING PAYMENTS (TO YOUR BRANCH)</h4>
    <table>
      <thead>
        <tr>
          <th>SN</th>
          <th>Tracking No</th>
          <th>Date</th>
          <th>From → Recive</th>
          <th>Request Type</th>
          <th>Item Type</th>
          <th>Transport Type</th>
          <th>Weight</th>
          <th>Unit</th>
          <th>Amount</th>
        </tr>
      </thead>
      <tbody>
        @php $sn = 1; $inTotal = 0; @endphp
        @forelse($inRecords as $record)
        @php $inTotal += $record->amount; @endphp
        <tr>
          <td>{{ $sn++ }}</td>
          <td>{{ $record->tracking_number ?? '' }}</td>
          <td>{{ \Carbon\Carbon::parse($record->created_at)->format('d.m.y') }}</td>
          <td>
            {{ $hubs[$record->from_branch_id]->name ?? $record->from_branch_id }} →
            {{ $hubs[$record->to_branch_id]->name ?? 'N/A' }}
          </td>
          <td>{{ $record->request_type ?? '' }}</td>
          <td>{{ $record->item_type ?? '' }}</td>
          <td>{{ strtoupper(str_replace('_', ' ', $record->transport_type)) }}</td>
          <td>{{ number_format($record->quantity) }}</td>
          <td>{{ $record->unit }}</td>
          <td>{{ number_format($record->amount, 2) }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="10" style="text-align: center;">No incoming records found</td>
        </tr>
        @endforelse
      </tbody>
    </table>

    <table class="totals">
      <tr>
        <td>IN Total Amount</td>
        <td style="text-align:right;">₹ {{ number_format($inTotal, 2) }}</td>
      </tr>
    </table>
  </div>

  <div style="clear: both;"></div>

  <!-- GRAND TOTAL -->
  <table class="grand-total">
    <tr>
      <td>GRAND TOTAL (IN)</td>
      <td style="text-align:right;">₹ {{ number_format($inTotal, 2) }}</td>
    </tr>
  </table>

  <div class="sign">
    For YES COURIERS NETWORK SERVICE<br>
    (Authorised Signatory)
  </div>

  <p class="note">
    * Subject to Nainital Jurisdiction.<br>
  </p>

  <script>
    window.print();
  </script>
</body>

</html>