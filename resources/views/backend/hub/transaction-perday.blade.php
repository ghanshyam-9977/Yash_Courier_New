<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Parcel Transaction Ledger</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: "Courier New", monospace; font-size: 13px; margin: 30px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #000; padding: 4px; text-align: right; }
    th:first-child, td:first-child { text-align: left; }
    .heading { text-align: center; margin-bottom: 10px; line-height: 1.4; }
    .note { margin-top: 15px; font-weight: bold; }
    @media print {
      body { margin: 10px; zoom: 95%; }
      button { display: none; }
    }
  </style>
</head>
<body>

  <div class="text-center heading">
    <h5 class="mb-0">M/S YES COURIERS NETWORK SERVICE</h5>
    <div>VILLAGE HATHIKHAL, HOUSE NO 41, PO-ARJUNPUR, HALDWANI (NAINITAL), UK-263139-F</div>
    <div><b>PARCEL TRANSACTION LEDGER</b></div>
    <div><b>DATE:</b> {{ now()->format('d.m.y') }}</div>
  </div>

  <div><b>DELIVERY BOY:</b> {{ $deliveryBoyName }}</div>
  <div><b>DR NO:</b> {{ $dr_no }}</div>

  <h6 class="mt-3">Hub-wise Parcel Count</h6>
  <table class="mb-3">
    <thead>
      <tr>
        <th>Hub ID</th>
        <th>Total Parcels</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($hubCounts as $hub)
        <tr>
          <td>{{ $hub->hub_id }}</td>
          <td>{{ $hub->total }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <h6>Parcel Details</h6>
  <table>
    <thead>
      <tr>
        <th>DATE</th>
        <th>PARTICULARS</th>
        <th>DEBIT</th>
        <th>CREDIT</th>
        <th>BALANCE</th>
      </tr>
    </thead>
    <tbody>
      @php
        $debitTotal = 0;
        $creditTotal = 0;
        $balance = 0;
      @endphp

      @foreach ($parcels as $parcel)
        @php
          $debit = $parcel->price ?? 0;
          $credit = $parcel->cod_amount ?? 0;
          $balance += ($debit - $credit);
          $debitTotal += $debit;
          $creditTotal += $credit;
        @endphp
        <tr>
          <td>{{ \Carbon\Carbon::parse($parcel->created_at)->format('d.m.y') }}</td>
          <td>PARCEL #{{ $parcel->id }} (Hub: {{ $parcel->hub_id }})</td>
          <td>{{ number_format($parcel->price, 2) }}</td>
          <td>{{ number_format($credit, 2) }}</td>
          <td>{{ number_format($balance, 2) }}</td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <th colspan="2" class="text-right">TOTAL</th>
        <th>{{ number_format($debitTotal, 2) }}</th>
        <th>{{ number_format($creditTotal, 2) }}</th>
        <th>{{ number_format($balance, 2) }}</th>
      </tr>
    </tfoot>
  </table>

  <div class="note">
    <div>{{ now()->format('d.m.y') }} NETR {{ now()->addDays(5)->format('d.m.y') }}  {{ number_format($creditTotal - $debitTotal, 2) }}  NILL</div>
  </div>

  <script>
    window.print();
  </script>
</body>
</html>
