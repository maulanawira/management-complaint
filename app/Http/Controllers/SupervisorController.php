<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class SupervisorController extends Controller
{
    /**
     * Generate custom ID untuk komplain.
     */
    private function generateComplaintId($complaint)
    {
        $jenis = $complaint->jenis ?? 'Umum';

        $typeCode = match($jenis) {
            'Gaji dan Tunjangan' => 'GDT',
            'Fasilitas Kerja' => 'FAK',
            'Diskriminasi atau Pelecehan' => 'DAP',
            'Waktu kerja tidak fleksibel' => 'WKT',
            'Kurangnya penghargaan/kepedulian' => 'KPK',
            'Masalah komunikasi' => 'KOM',
            default => 'UMU',
        };

        $dateCode = $complaint->created_at->format('dmy');
        $sequence = str_pad($complaint->id, 3, '0', STR_PAD_LEFT);

        return $typeCode . '-' . $dateCode . '-' . $sequence;
    }

    /**
     * Tampilkan halaman dashboard supervisor.
     */
    public function dashboard()
    {
        // Statistik
        $totalComplaints = Complaint::count();
        $pendingComplaints = Complaint::where('status', 'baru')->count();
        $processingComplaints = Complaint::where('status', 'diproses')->count();
        $completedComplaints = Complaint::where('status', 'selesai')->count();

        // Semua komplain
        $allComplaints = Complaint::with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($c) => $this->formatComplaint($c));

        // Komplain urgent (>3 hari belum selesai)
        $urgentComplaints = Complaint::with('user')
            ->where('created_at', '<=', Carbon::now()->subDays(3))
            ->whereIn('status', ['baru', 'diproses'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn($c) => $this->formatComplaint($c));

        // Ringkasan berdasarkan jenis
        $complaintsByType = $this->getComplaintTypeSummary();

        // Aktivitas terbaru
        $recentActivity = Complaint::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(fn($c) => $this->formatComplaint($c));

        return view('dashboards.supervisor', compact(
            'totalComplaints',
            'pendingComplaints',
            'processingComplaints',
            'completedComplaints',
            'allComplaints',
            'urgentComplaints',
            'complaintsByType',
            'recentActivity'
        ));
    }

    /**
     * Format data komplain.
     */
    private function formatComplaint($complaint)
    {
        $complaint->id_komplain = $this->generateComplaintId($complaint);
        $complaint->jenis = $complaint->jenis ?? 'Umum';
        return $complaint;
    }

    /**
     * Ringkasan jumlah komplain berdasarkan jenis.
     */
    private function getComplaintTypeSummary()
    {
        return [
            'Gaji dan Tunjangan' => Complaint::where('jenis', 'Gaji dan Tunjangan')->count(),
            'Fasilitas Kerja' => Complaint::where('jenis', 'Fasilitas Kerja')->count(),
            'Diskriminasi atau Pelecehan' => Complaint::where('jenis', 'Diskriminasi atau Pelecehan')->count(),
            'Waktu kerja tidak fleksibel' => Complaint::where('jenis', 'Waktu kerja tidak fleksibel')->count(),
            'Kurangnya penghargaan/kepedulian' => Complaint::where('jenis', 'Kurangnya penghargaan/kepedulian')->count(),
            'Masalah komunikasi' => Complaint::where('jenis', 'Masalah komunikasi')->count(),
        ];
    }

    /**
     * Halaman edit profil supervisor.
     */
    public function editProfile()
    {
        return view('dashboards.edit-profil-supervisor');
    }

    /**
     * Update data profil supervisor.
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user = auth()->user();
        $user->name = $request->name;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('supervisor.editProfile')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Data ringkasan dashboard.
     */
    private function getDashboardData()
    {
        return [
            'totalComplaints' => Complaint::count(),
            'pendingComplaints' => Complaint::where('status', 'baru')->count(),
            'processingComplaints' => Complaint::where('status', 'diproses')->count(),
            'completedComplaints' => Complaint::where('status', 'selesai')->count(),
            'complaintsByType' => $this->getComplaintTypeSummary(),
        ];
    }

    /**
     * Export laporan ringkasan ke PDF.
     */
    public function exportSummary()
    {
        try {
            $data = $this->getDashboardData();

            $allComplaints = Complaint::with('user')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(fn($c) => $this->formatComplaint($c));

            $pdf = PDF::loadView('dashboards.export.summary', [
                ...$data,
                'allComplaints' => $allComplaints,
                'exportDate' => now()->format('d-m-Y H:i:s'),
                'exportedBy' => auth()->user()->name,
                'filterPeriod' => 'Semua Data',
                'isFiltered' => false
            ])->setPaper('A4', 'landscape');

            $filename = 'laporan-ringkasan-' . now()->format('Y-m-d') . '.pdf';
            return $pdf->download($filename);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengexport laporan: ' . $e->getMessage());
        }
    }

    /**
     * Export laporan berdasarkan filter tanggal/status/jenis.
     */
    public function exportFiltered(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'nullable|string',
            'jenis' => 'nullable|string',
        ]);

        try {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();

            $query = Complaint::with('user')->whereBetween('created_at', [$start, $end]);

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('jenis')) {
                $query->where('jenis', $request->jenis);
            }

            $filteredComplaints = $query->orderBy('created_at', 'desc')
                ->get()
                ->map(fn($c) => $this->formatComplaint($c));

            $pdf = PDF::loadView('dashboards.export.summary', [
                'totalComplaints' => $filteredComplaints->count(),
                'pendingComplaints' => $filteredComplaints->where('status', 'baru')->count(),
                'processingComplaints' => $filteredComplaints->where('status', 'diproses')->count(),
                'completedComplaints' => $filteredComplaints->where('status', 'selesai')->count(),
                'rejectedComplaints' => $filteredComplaints->where('status', 'ditolak')->count(),
                'complaintsByType' => $this->getComplaintTypeSummaryFromCollection($filteredComplaints),
                'allComplaints' => $filteredComplaints,
                'exportDate' => now()->format('d-m-Y H:i:s'),
                'exportedBy' => auth()->user()->name,
                'filterPeriod' => $start->format('d-m-Y') . ' - ' . $end->format('d-m-Y'),
                'filterInfo' => [
                    'start_date' => $start->format('d-m-Y'),
                    'end_date' => $end->format('d-m-Y'),
                    'status' => $request->status ? ucfirst($request->status) : 'Semua Status',
                    'jenis' => $request->jenis ?: 'Semua Jenis',
                ],
                'isFiltered' => true
            ])->setPaper('A4', 'landscape');

            $filename = 'laporan-komplain-filtered-' . $start->format('d-m-Y') . '-to-' . $end->format('d-m-Y') . '.pdf';
            return $pdf->download($filename);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengexport laporan: ' . $e->getMessage());
        }
    }

    /**
     * Hitung jumlah komplain berdasarkan jenis dari koleksi.
     */
    private function getComplaintTypeSummaryFromCollection($collection)
    {
        return [
            'Gaji dan Tunjangan' => $collection->where('jenis', 'Gaji dan Tunjangan')->count(),
            'Fasilitas Kerja' => $collection->where('jenis', 'Fasilitas Kerja')->count(),
            'Diskriminasi atau Pelecehan' => $collection->where('jenis', 'Diskriminasi atau Pelecehan')->count(),
            'Waktu kerja tidak fleksibel' => $collection->where('jenis', 'Waktu kerja tidak fleksibel')->count(),
            'Kurangnya penghargaan/kepedulian' => $collection->where('jenis', 'Kurangnya penghargaan/kepedulian')->count(),
            'Masalah komunikasi' => $collection->where('jenis', 'Masalah komunikasi')->count(),
        ];
    }

    /**
     * Statistik bulanan untuk grafik.
     */
    public function getMonthlyStatistics()
    {
        $monthlyData = Complaint::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as total')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('year', 'month')
            ->orderBy('month')
            ->get();

        return response()->json($monthlyData);
    }

    /**
     * Statistik harian 30 hari terakhir.
     */
    public function getDailyStatistics()
    {
        $dailyData = Complaint::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json($dailyData);
    }
}