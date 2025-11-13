<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Masref — Completed Transactions</title>
  <style>
    @page { margin: 25px 35px; }
    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 12px;
      color: #1e293b;
      background: #ffffff;
    }

    /* ===== HEADER ===== */
    .header {
      background: #ffffff;
      border-bottom: 3px solid #2563eb;
      padding: 10px 0 8px;
      margin-bottom: 20px;
    }
    .header h1 {
      margin: 0;
      font-size: 20px;
      font-weight: 700;
      color: #1e3a8a;
    }
    .header small {
      font-size: 11px;
      color: #64748b;
    }

    /* ===== TABLE ===== */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      border: 1px solid #e2e8f0;
      border-radius: 6px;
      overflow: hidden;
    }
    thead {
      background: #f8fafc;
    }
    th {
      text-align: left;
      text-transform: uppercase;
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 0.3px;
      color: #1e3a8a;
      padding: 8px 10px;
      border-bottom: 2px solid #2563eb;
    }
    td {
      padding: 8px 10px;
      border-bottom: 1px solid #e5e7eb;
      font-size: 12px;
      color: #334155;
    }
    tr:nth-child(even) td {
      background: #f9fafb;
    }

    /* ===== STATUS CHIPS ===== */
    .status {
      text-transform: capitalize;
      font-weight: 600;
      font-size: 11px;
      border-radius: 12px;
      padding: 3px 8px;
      display: inline-block;
    }
    .completed { background: #dcfce7; color: #166534; }
    .pending   { background: #e0f2fe; color: #1e40af; }
    .failed    { background: #fee2e2; color: #991b1b; }

    /* ===== SUMMARY ===== */
    .summary {
      margin-top: 15px;
      padding: 10px 14px;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 6px;
    }
    .summary strong {
      color: #1e3a8a;
    }

    /* ===== FOOTER ===== */
    .footer {
      text-align: center;
      margin-top: 25px;
      padding-top: 10px;
      font-size: 11px;
      color: #475569;
      border-top: 1px solid #e2e8f0;
    }
  </style>
</head>

<body>
  {{-- HEADER --}}
  <div class="header">
    <h1>Masref — Completed Transactions</h1>
    <small>User: {{ $user->name }} • Generated: {{ now()->format('Y-m-d H:i') }}</small>
  </div>

  {{-- TABLE --}}
  <table>
    <thead>
      <tr>
        <th>Reference</th>
        <th>Method</th>
        <th>Service</th>
        <th>Receiver</th>
        <th>Amount</th>
        <th>Fee</th>
        <th>Total</th>
        <th>Status</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      @foreach($items as $r)
      <tr>
        <td>{{ $r->reference }}</td>
        <td>{{ ucfirst($r->method) }}</td>
        <td>{{ $r->service->name ?? '—' }}</td>
        <td>{{ $r->beneficiary->name ?? $r->destination }}</td>
        <td>{{ strtoupper($r->src_currency) }} {{ number_format($r->amount_dst, 2) }}</td>
        <td>{{ number_format($r->fee, 2) }}</td>
        <td><strong>{{ strtoupper($r->src_currency) }} {{ number_format($r->amount_dst + $r->fee, 2) }}</strong></td>
        <td><span class="status {{ $r->status }}">{{ ucfirst($r->status) }}</span></td>
        <td>{{ $r->created_at->format('Y-m-d H:i') }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  {{-- SUMMARY --}}
  @php
    $totalTransactions = count($items);
    $sumAmount = $items->sum('amount_dst');
    $sumFees   = $items->sum('fee');
  @endphp
  <div class="summary">
    <strong>Total Transactions:</strong> {{ $totalTransactions }} &nbsp;|&nbsp;
    <strong>Total Sent:</strong> {{ strtoupper($items->first()->src_currency ?? 'USD') }} {{ number_format($sumAmount, 2) }} &nbsp;|&nbsp;
    <strong>Total Fees:</strong> {{ number_format($sumFees, 2) }}
  </div>

  {{-- FOOTER --}}
  <div class="footer">
    Masref © {{ date('Y') }} — Secure • Transparent • Reliable
  </div>
</body>
</html>
