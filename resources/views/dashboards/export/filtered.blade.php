<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Komplain - {{ $title ?? 'Export Data' }}</title>
    <style>
        @page {
            margin: 2cm 1.5cm;
            size: A4;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #169976;
        }
        
        .header h1 {
            color: #169976;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .header h2 {
            color: #666;
            font-size: 14px;
            font-weight: normal;
        }
        
        .info-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #169976;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        
        .info-row:last-child {
            margin-bottom: 0;
        }
        
        .info-label {
            font-weight: bold;
            color: #169976;
            width: 150px;
        }
        
        .info-value {
            color: #333;
            flex: 1;
        }
        
        .summary-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            gap: 15px;
        }
        
        .summary-card {
            flex: 1;
            text-align: center;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
        }
        
        .summary-card.total {
            border-left: 4px solid #169976;
        }
        
        .summary-card.pending {
            border-left: 4px solid #dc3545;
        }
        
        .summary-card.processing {
            border-left: 4px solid #17a2b8;
        }
        
        .summary-card.completed {
            border-left: 4px solid #28a745;
        }
        
        .summary-card h3 {
            font-size: 12px;
            margin-bottom: 8px;
            color: #666;
        }
        
        .summary-card .number {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        
        .table-container {
            margin-bottom: 20px;
        }
        
        .table-title {
            background-color: #169976;
            color: white;
            padding: 10px 15px;
            margin-bottom: 0;
            font-size: 14px;
            font-weight: bold;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: white;
        }
        
        th {
            background-color: #1DCD9F;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
            border: 1px solid #ddd;
        }
        
        td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 10px;
            vertical-align: top;
        }
        
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .status-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            color: white;
        }
        
        .status-baru {
            background-color: #ffc107;
            color: #000;
        }
        
        .status-diproses {
            background-color: #17a2b8;
        }
        
        .status-selesai {
            background-color: #28a745;
        }
        
        .status-ditolak {
            background-color: #dc3545;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-truncate {
            max-width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 30px;
        }
        
        .summary-section {
            margin-top: 25px;
            padding-top: 15px;
            border-top: 2px solid #169976;
        }
        
        .summary-title {
            font-size: 14px;
            font-weight: bold;
            color: #169976;
            margin-bottom: 15px;
        }
        
        .type-summary {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .type-item {
            background-color: #f8f9fa;
            padding: 8px 12px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 10px;
        }
        
        .type-item strong {
            color: #169976;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <h1>LAPORAN DATA KOMPLAIN</h1>
        <h2>Dashboard Supervisor - Sistem Manajemen Komplain</h2>
    </div>

    {{-- Report Information --}}
    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Tanggal Export:</div>
            <div class="info-value">{{ now()->format('d F Y, H:i') }} WIB</div>
        </div>
        <div class="info-row">
            <div class="info-label">Periode Data:</div>
            <div class="info-value">
                @if($startDate && $endDate)
                    {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}
                @else
                    Semua Data
                @endif
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">Filter Status:</div>
            <div class="info-value">{{ $statusFilter ? ucfirst($statusFilter) : 'Semua Status' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Filter Jenis:</div>
            <div class="info-value">{{ $jenisFilter ?? 'Semua Jenis' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Total Data:</div>
            <div class="info-value">{{ count($complaints) }} komplain</div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="summary-cards">
        <div class="summary-card total">
            <h3>Total Komplain</h3>
            <div class="number">{{ $totalComplaints ?? count($complaints) }}</div>
        </div>
        <div class="summary-card pending">
            <h3>Belum Diproses</h3>
            <div class="number">{{ $pendingComplaints ?? 0 }}</div>
        </div>
        <div class="summary-card processing">
            <h3>Sedang Diproses</h3>
            <div class="number">{{ $processingComplaints ?? 0 }}</div>
        </div>
        <div class="summary-card completed">
            <h3>Selesai</h3>
            <div class="number">{{ $completedComplaints ?? 0 }}</div>
        </div>
    </div>

    {{-- Main Data Table --}}
    <div class="table-container">
        <div class="table-title">Data Komplain</div>
        
        @if(count($complaints) > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 12%;">ID Komplain</th>
                        <th style="width: 15%;">Karyawan</th>
                        <th style="width: 18%;">Jenis</th>
                        <th style="width: 25%;">Judul</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 15%;">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($complaints as $index => $complaint)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $complaint->id_komplain }}</td>
                            <td>{{ $complaint->user->name ?? '—' }}</td>
                            <td>{{ $complaint->jenis }}</td>
                            <td>
                                <div class="text-truncate" title="{{ $complaint->judul }}">
                                    {{ $complaint->judul }}
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $complaint->status }}">
                                    {{ ucfirst($complaint->status) }}
                                </span>
                            </td>
                            <td>{{ $complaint->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        
                        {{-- Page break every 25 rows --}}
                        @if(($index + 1) % 25 == 0 && !$loop->last)
                            </tbody>
                        </table>
                        <div class="page-break"></div>
                        <div class="table-title">Data Komplain (Lanjutan)</div>
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th style="width: 12%;">ID Komplain</th>
                                    <th style="width: 15%;">Karyawan</th>
                                    <th style="width: 18%;">Jenis</th>
                                    <th style="width: 25%;">Judul</th>
                                    <th style="width: 10%;">Status</th>
                                    <th style="width: 15%;">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                        @endif
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">
                Tidak ada data komplain yang sesuai dengan filter yang dipilih.
            </div>
        @endif
    </div>

    {{-- Summary by Type and Status --}}
    @if(count($complaints) > 0)
        <div class="summary-section">
            <div class="summary-title">Ringkasan Berdasarkan Jenis Komplain</div>
            <div class="type-summary">
                @php
                    $complaintsByType = $complaints->groupBy('jenis')->map->count()->sortDesc();
                @endphp
                @foreach($complaintsByType as $type => $count)
                    <div class="type-item">
                        <strong>{{ $count }}</strong> - {{ $type }}
                    </div>
                @endforeach
            </div>

            <div class="summary-title">Ringkasan Berdasarkan Status</div>
            <div class="type-summary">
                @php
                    $complaintsByStatus = $complaints->groupBy('status')->map->count()->sortDesc();
                @endphp
                @foreach($complaintsByStatus as $status => $count)
                    <div class="type-item">
                        <strong>{{ $count }}</strong> - {{ ucfirst($status) }}
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <p>
            Laporan ini dibuat secara otomatis oleh Sistem Manajemen Komplain<br>
            Dicetak pada: {{ now()->format('d F Y, H:i:s') }} WIB
        </p>
    </div>
</body>
</html>