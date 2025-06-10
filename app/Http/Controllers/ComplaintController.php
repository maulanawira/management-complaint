<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ComplaintController extends Controller
{
    // Generate ID komplain
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

        $dateCode = ($complaint->created_at ?? now())->format('dmy');
        $sequence = str_pad($complaint->id ?? 1, 3, '0', STR_PAD_LEFT);

        return $typeCode . '-' . $dateCode . '-' . $sequence;
    }

    // Simpan komplain baru
    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|string',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        $complaint = DB::transaction(function () use ($request) {
            $complaint = Complaint::create([
                'user_id' => auth()->id(),
                'jenis' => $request->input('jenis'),
                'judul' => $request->input('judul'),
                'deskripsi' => $request->input('deskripsi'),
                'status' => 'baru',
            ]);

            $complaint->id_komplain = $this->generateComplaintId($complaint);
            $complaint->save();

            return $complaint;
        });

        return redirect()->route('dashboard.karyawan')
            ->with('success', 'Komplain berhasil dikirim dengan ID: ' . $complaint->id_komplain);
    }

    // Riwayat komplain karyawan
    public function karyawanHistory()
    {
        $complaints = Complaint::where('user_id', Auth::id())
            ->latest()
            ->get()
            ->map(function ($complaint) {
                if (empty($complaint->id_komplain)) {
                    $complaint->id_komplain = $this->generateComplaintId($complaint);
                    $complaint->save();
                }
                return $complaint;
            });

        return view('karyawanhistory', compact('complaints'));
    }

    // Tampilkan daftar komplain untuk admin
    public function adminIndex()
    {
        try {
            \Log::info('Test 1: Query sederhana');
            $complaints = Complaint::all();
            \Log::info('Test 1: Berhasil');

            \Log::info('Test 2: Query dengan relasi user');
            $complaints = Complaint::with('user')->get();
            \Log::info('Test 2: Berhasil');

            \Log::info('Test 3: Query lengkap');
            $complaints = Complaint::with('user')
                ->latest()
                ->get()
                ->map(function ($complaint) {
                    if (empty($complaint->id_komplain)) {
                        $complaint->id_komplain = $this->generateComplaintId($complaint);
                        $complaint->save();
                    }
                    return $complaint;
                });
            \Log::info('Test 3: Berhasil');

            return view('dashboards.admin', compact('complaints'));

        } catch (\Exception $e) {
            \Log::error('Error di adminIndex: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            \Log::error('Stack: ' . $e->getTraceAsString());

            $complaints = collect();
            return view('dashboards.admin', compact('complaints'));
        }
    }

    // Perbarui status komplain
    public function updateStatus(Request $request, Complaint $complaint)
    {
        $request->validate([
            'status' => 'required|in:baru,diproses,selesai,ditolak',
        ]);

        $complaint->status = $request->status;
        $complaint->updated_at = now();
        $complaint->save();

        return redirect()->back()->with('success', 'Status komplain berhasil diperbarui.');
    }

    // Simpan feedback komplain
    public function updateFeedback(Request $request, Complaint $complaint)
    {
        $request->validate([
            'feedback' => 'required|string',
        ]);

        $complaint->feedback = $request->feedback;
        $complaint->updated_at = now();
        $complaint->save();

        return redirect()->back()->with('success', 'Feedback berhasil disimpan.');
    }

    // Hapus komplain
    public function destroy($id)
    {
        $complaint = Complaint::where('user_id', Auth::id())->findOrFail($id);
        $complaintId = $complaint->id_komplain;
        $complaint->delete();

        return redirect()->route('dashboard.karyawan.history')
            ->with('success', 'Komplain ' . $complaintId . ' berhasil dihapus.');
    }

    // Detail komplain
    public function show($id)
    {
        $complaint = Complaint::with('user')->findOrFail($id);

        if (empty($complaint->id_komplain)) {
            $complaint->id_komplain = $this->generateComplaintId($complaint);
            $complaint->save();
        }

        return view('complaint.detail', compact('complaint'));
    }

    // Cari komplain berdasarkan ID
    public function searchByComplaintId($complaintId)
    {
        $complaint = Complaint::where('id_komplain', $complaintId)->first();

        if (!$complaint) {
            return redirect()->back()
                ->with('error', 'Komplain dengan ID ' . $complaintId . ' tidak ditemukan.');
        }

        return view('complaint.detail', compact('complaint'));
    }
}