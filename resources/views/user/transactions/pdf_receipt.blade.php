<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Masref Receipt — {{ $t->reference }}</title>
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      color: #111;
      background: #fff;
      margin: 25px;
      font-size: 13px;
      line-height: 1.6;
    }

    h1, h2, h3 {
      margin: 0;
      color: #1e3a8a;
    }

    /* Header */
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 2px solid #1e3a8a;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }

    .header .brand {
      font-size: 20px;
      font-weight: bold;
      color: #1e3a8a;
    }

    .header .ref {
      text-align: right;
      font-size: 12px;
      color: #555;
    }

    /* Table styling */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th, td {
      padding: 8px 10px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background: #f1f5f9;
      font-weight: 600;
      color: #1e3a8a;
      font-size: 12px;
    }

    /* Highlight panels */
    .section {
      margin-bottom: 25px;
    }

    .label {
      color: #555;
      font-weight: 600;
    }

    .value {
      font-weight: 700;
    }

    .summary {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
      gap: 15px;
      margin-bottom: 20px;
    }

    .box {
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 10px 12px;
    }

    .total {
      margin-top: 15px;
      text-align: right;
      font-weight: bold;
      font-size: 15px;
      color: #1e3a8a;
    }

    /* Footer */
    .footer {
      border-top: 1px solid #ccc;
      margin-top: 30px;
      padding-top: 10px;
      font-size: 11px;
      text-align: center;
      color: #555;
    }

    .badge {
      display: inline-block;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
      text-transform: capitalize;
    }

    .b-completed { background: #dcfce7; color: #166534; }
    .b-failed { background: #fee2e2; color: #991b1b; }
    .b-processing { background: #fef3c7; color: #92400e; }

  </style>
</head>
<body>

  {{-- HEADER --}}
  <div class="header">
    <div class="brand">Masref</div>
    <div class="ref">
      <div><strong>Payment Receipt</strong></div>
      <div>Reference: {{ $t->reference }}</div>
      <div>Date: {{ $t->created_at->format('Y-m-d H:i') }}</div>
    </div>
  </div>

  {{-- SUMMARY --}}
  <div class="summary">
    <div class="box">
      <div class="label">Status</div>
      <div>
        <span class="badge
          {{ $t->status === 'completed' ? 'b-completed' : ($t->status === 'failed' ? 'b-failed' : 'b-processing') }}">
          {{ ucfirst($t->status) }}
        </span>
      </div>
    </div>
    <div class="box">
      <div class="label">Method</div>
      <div class="value">{{ ucfirst($t->method) }}</div>
    </div>
    <div class="box">
      <div class="label">Currency</div>
      <div class="value">{{ strtoupper($t->src_currency) }}</div>
    </div>
    <div class="box">
      <div class="label">Service</div>
      <div class="value">{{ $t->service->name ?? '—' }}</div>
    </div>
  </div>

  {{-- DETAILS --}}
  <div class="section">
    <table>
      <tbody>
        <tr>
          <th>Source</th>
          <td>{{ $t->source }}</td>
        </tr>
        <tr>
          <th>Destination</th>
          <td>{{ $t->destination }}</td>
        </tr>
        <tr>
          <th>FX Rate</th>
          <td>{{ number_format($t->fx_rate, 6) }}</td>
        </tr>
        <tr>
          <th>Amount</th>
          <td>{{ strtoupper($t->src_currency) }} {{ number_format($t->amount_dst, 2) }}</td>
        </tr>
        <tr>
          <th>Fee</th>
          <td>{{ strtoupper($t->src_currency) }} {{ number_format($t->fee, 2) }}</td>
        </tr>
        <tr>
          <th>Total</th>
          <td><strong>{{ strtoupper($t->src_currency) }} {{ number_format($t->amount_dst + $t->fee, 2) }}</strong></td>
        </tr>
      </tbody>
    </table>
  </div>

  {{-- TIMELINE --}}
  @if($t->events && $t->events->count())
  <div class="section">
    <h3>Transaction Timeline</h3>
    <table>
      <thead>
        <tr>
          <th>Event</th>
          <th>Date</th>
          <th>Details</th>
        </tr>
      </thead>
      <tbody>
        @foreach($t->events as $e)
          <tr>
            <td>{{ ucfirst($e->event) }}</td>
            <td>{{ $e->created_at->format('Y-m-d H:i') }}</td>
            <td>{{ $e->meta ?: '—' }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @endif

  {{-- NOTES --}}
  <div class="section">
    <h3>Notes</h3>
    <p>{{ optional($t->events->where('event','created')->last())->meta ?? '—' }}</p>
  </div>

  {{-- FOOTER --}}
  <div class="footer">
    <strong>Masref</strong> · Secure Money Transfers · support@masref.example<br>
    Please reference <strong>{{ $t->reference }}</strong> for any inquiries.<br>
    Generated on {{ now()->format('Y-m-d H:i') }}.
  </div>
</body>
</html>
