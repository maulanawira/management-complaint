<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Ringkasan Komplain</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            font-size: 12px;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #169976;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        
        .header h1 {
            color: #169976;
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        
        .header .subtitle {
            color: #666;
            margin: 5px 0;
            font-size: 14px;
        }
        
        .info-section {
            margin-bottom: 25px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #169976;
        }
        
        .info-row {
            display: inline-block;
            width: 48%;
            margin-bottom: 8px;
        }
        
        .info-label {
            font-weight: bold;
            color: #169976;
        }
        
        .statistics-section {
            margin-bottom: 30px;
        }
        
        .section-title {
            background-color: #169976;
            color: white;
            padding: 10px 15px;
            margin: 0 0 15px 0;
            font-weight: bold;
            font-size: 16px;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        
        .stats-item {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 20px 10px;
            border: 1px solid #ddd;
            vertical-align: middle;
        }
        
        .stats-number {
            font-size: 28px;
            font-weight: bold;
            color: #169976;
            display: block;
            margin-bottom: 5px;
        }
        
        .stats-label {
            font-size: 12px;
            color: #666;
            font-weight: 500;
        }
        
        .complaint-types {
            margin-bottom: 30px;
        }
        
        .types-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ddd;
        }
        
        .types-table th {
            background-color: #1DCD9F;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        
        .types-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        
        .types-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .count-badge {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            min-width: 20px;
            text-align: center;
        }
        
        .summary-box {
            background-color: #e8f5f1;
            border: 1px solid #1DCD9F;
            border-radius: 8px;
            padding: 20px;
            margin-top: 25px;
        }
        
        .summary-title {
            color: #169976;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .summary-text {
            line-height: 1.6;
            color: #333;
        }
        
        .footer {
            margin-top: 40px;
            border-top: 2px solid #169976;
            padding-top: 15px;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        @media print {
            body { margin: 0; }
            .page-break { page-break-after: always; }
        }
    </style>
</head>
<body>
    {{-- Header Section --}}
    <div class="header">
        <h1>LAPORAN RINGKASAN KOMPLAIN</h1>
        <div class="subtitle">Sistem Manajemen Komplain Karyawan</div>
        <div class="subtitle">PT. Maju Bersama</div>
    </div>

    {{-- Report Information --}}
    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Tanggal Laporan:</span> {{ now()->format('d-m-Y') }}
        </div>
        <div class="info-row">
            <span class="info-label">Periode Data:</span> {{ $startDate ?? 'Semua Waktu' }} - {{ $endDate ?? now()->format('d-m-Y') }}
        </div>
        <div class="info-row">
            <span class="info-label">Total Data:</span> {{ $totalComplaints }} Komplain
        </div>
    </div>

    {{-- Statistics Section --}}
    <div class="statistics-section">
        <h2 class="section-title">STATISTIK KOMPLAIN</h2>
        <div class="stats-grid">
            <div class="stats-item">
                <span class="stats-number">{{ $totalComplaints ?? 0 }}</span>
                <span class="stats-label">Total Komplain</span>
            </div>
            <div class="stats-item">
                <span class="stats-number">{{ $pendingComplaints ?? 0 }}</span>
                <span class="stats-label">Belum Diproses</span>
            </div>
            <div class="stats-item">
                <span class="stats-number">{{ $processingComplaints ?? 0 }}</span>
                <span class="stats-label">Sedang Diproses</span>
            </div>
            <div class="stats-item">
                <span class="stats-number">{{ $completedComplaints ?? 0 }}</span>
                <span class="stats-label">Selesai</span>
            </div>
        </div>
    </div>

    {{-- Complaint Types Section --}}
    <div class="complaint-types">
        <h2 class="section-title">DISTRIBUSI JENIS KOMPLAIN</h2>
        <table class="types-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis Komplain</th>
                    <th style="text-align: center;">Jumlah</th>
                    <th style="text-align: center;">Persentase</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $types = [
                        'Gaji dan Tunjangan',
                        'Fasilitas Kerja',
                        'Diskriminasi atau Pelecehan',
                        'Waktu kerja tidak fleksibel',
                        'Kurangnya penghargaan/kepedulian',
                        'Masalah komunikasi'
                    ];
                    $total = $totalComplaints > 0 ? $totalComplaints : 1;
                @endphp
                
                @foreach($types as $index => $type)
                    @php
                        $count = $complaintsByType[$type] ?? 0;
                        $percentage = round(($count / $total) * 100, 1);
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $type }}</td>
                        <td style="text-align: center;">
                            <span class="count-badge">{{ $count }}</span>
                        </td>
                        <td style="text-align: center;">{{ $percentage }}%</td>
                    </tr>
                @endforeach
                
                <tr style="background-color: #169976; color: white; font-weight: bold;">
                    <td colspan="2" style="text-align: center;">TOTAL</td>
                    <td style="text-align: center;">{{ $totalComplaints ?? 0 }}</td>
                    <td style="text-align: center;">100%</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Summary Analysis --}}
    <div class="summary-box">
        <div class="summary-title">RINGKASAN ANALISIS</div>
        <div class="summary-text">
            <p><strong>Status Penanganan:</strong></p>
            <p>
                Dari total {{ $totalComplaints ?? 0 }} komplain yang tercatat, 
                {{ $completedComplaints ?? 0 }} komplain ({{ $totalComplaints > 0 ? round(($completedComplaints / $totalComplaints) * 100, 1) : 0 }}%) 
                telah diselesaikan, {{ $processingComplaints ?? 0 }} komplain sedang dalam proses penanganan, 
                dan {{ $pendingComplaints ?? 0 }} komplain masih menunggu untuk diproses.
            </p>
            
            <p><strong>Jenis Komplain Dominan:</strong></p>
            @php
                $maxType = '';
                $maxCount = 0;
                foreach($complaintsByType ?? [] as $type => $count) {
                    if($count > $maxCount) {
                        $maxCount = $count;
                        $maxType = $type;
                    }
                }
            @endphp
            <p>
                Jenis komplain yang paling sering dilaporkan adalah "{{ $maxType }}" 
                dengan {{ $maxCount }} kasus ({{ $totalComplaints > 0 ? round(($maxCount / $totalComplaints) * 100, 1) : 0 }}% 
                dari total komplain).
            </p>
            
            <p><strong>Rekomendasi:</strong></p>
            <p>
                @if($pendingComplaints > 0)
                    Diperlukan penanganan segera untuk {{ $pendingComplaints }} komplain yang belum diproses. 
                @endif
                @if($maxCount > 0)
                    Perhatian khusus perlu diberikan pada kategori "{{ $maxType }}" karena merupakan sumber komplain terbanyak.
                @endif
                Monitoring berkelanjutan diperlukan untuk memastikan tingkat kepuasan karyawan.
            </p>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh Sistem Manajemen Komplain Karyawan</p>
        <p>© {{ date('Y') }} PT. Maju Bersama</p>
    </div>
</body>
</html>