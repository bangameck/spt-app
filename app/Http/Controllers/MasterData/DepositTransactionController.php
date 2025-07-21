<?php

namespace App\Http\Controllers\MasterData; // Namespace yang sudah kita sepakati

use App\Http\Controllers\Controller;
use App\Models\DepositTransaction;
use App\Models\Agreement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Barryvdh\DomPDF\Facade\Pdf;

class DepositTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $searchDate = $request->input('search_date');
        $searchMonth = $request->input('search_month');
        $searchYear = $request->input('search_year');
        $startDateRange = $request->input('start_date_range');
        $endDateRange = $request->input('end_date_range');

        $query = DepositTransaction::with(['agreement.fieldCoordinator.user', 'agreement.leader.user', 'creator']);

        // Filter hanya untuk perjanjian yang aktif di tahun berjalan
        $currentYear = Carbon::now()->year;
        $query->whereHas('agreement', function ($agreementQuery) use ($currentYear) {
            $agreementQuery->where('status', 'active')
                ->whereYear('start_date', '<=', $currentYear)
                ->whereYear('end_date', '>=', $currentYear);
        });

        // Terapkan filter pencarian umum jika ada
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('deposit_date', 'like', '%' . $search . '%')
                    ->orWhere('amount', 'like', '%' . $search . '%')
                    ->orWhere('notes', 'like', '%' . $search . '%')
                    ->orWhereHas('agreement', function ($agreementQuery) use ($search) {
                        $agreementQuery->where('agreement_number', 'like', '%' . $search . '%')
                            ->orWhereHas('fieldCoordinator.user', function ($fcUserQuery) use ($search) {
                                $fcUserQuery->where('name', 'like', '%' . $search . '%');
                            })
                            ->orWhereHas('leader.user', function ($leaderUserQuery) use ($search) {
                                $leaderUserQuery->where('name', 'like', '%' . $search . '%');
                            });
                    })
                    ->orWhereHas('creator', function ($creatorQuery) use ($search) {
                        $creatorQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Terapkan filter pencarian tanggal spesifik
        if ($searchDate) {
            $query->whereDate('deposit_date', $searchDate);
        }

        // Terapkan filter pencarian bulan
        if ($searchMonth) {
            $query->whereMonth('deposit_date', $searchMonth);
        }

        // Terapkan filter pencarian tahun
        if ($searchYear) {
            $query->whereYear('deposit_date', $searchYear);
        }

        // Terapkan filter pencarian rentang waktu
        if ($startDateRange && $endDateRange) {
            $query->whereBetween('deposit_date', [$startDateRange, $endDateRange]);
        } elseif ($startDateRange) {
            $query->whereDate('deposit_date', '>=', $startDateRange);
        } elseif ($endDateRange) {
            $query->whereDate('deposit_date', '<=', $endDateRange);
        }

        $depositTransactions = $query->latest('deposit_date')->paginate(10);

        return view('masterdata.deposit_transactions.index', compact(
            'depositTransactions',
            'search',
            'searchDate',
            'searchMonth',
            'searchYear',
            'startDateRange',
            'endDateRange'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Untuk Select2, kita tidak perlu mengirim semua perjanjian di awal
        // Mereka akan dimuat via AJAX
        $activeAgreements = collect(); // Kirim koleksi kosong atau null

        return view('masterdata.deposit_transactions.create', compact('activeAgreements'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('DepositTransactionController@store: Request received.', $request->all());

        $validatedData = $request->validate([
            'agreement_id' => [
                'required',
                'exists:agreements,id',
                Rule::exists('agreements', 'id')->where('status', 'active'),
                Rule::unique('deposit_transactions')->where(function ($query) use ($request) {
                    return $query->where('deposit_date', $request->deposit_date);
                }),
            ],
            'deposit_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:255',
            // Tambahkan validasi untuk bukti transfer
            'proof_of_transfer' => 'nullable|image|mimes:jpeg,png,jpg|max:300', // Maks 300KB
        ]);

        Log::info('DepositTransactionController@store: Validation successful.', $validatedData);

        $transactionData = Arr::except($validatedData, ['proof_of_transfer']);
        $transactionData['created_by_user_id'] = Auth::id();
        $transactionData['is_validated'] = false;

        // ✅ Logika untuk handle file upload
        if ($request->hasFile('proof_of_transfer')) {
            $imageName = time() . '_proof.' . $request->proof_of_transfer->extension();
            $request->proof_of_transfer->move(public_path('uploads/proofs'), $imageName);
            $transactionData['proof_of_transfer'] = 'uploads/proofs/' . $imageName;
        }

        DepositTransaction::create($transactionData);

        return redirect()->route('masterdata.deposit-transactions.index')
            ->with('success', 'Setoran berhasil dicatat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(DepositTransaction $depositTransaction)
    {
        // Eager load semua relasi yang dibutuhkan untuk halaman detail
        $depositTransaction->load([
            'agreement.fieldCoordinator.user',
            'agreement.leader.user',
            'creator'
        ]);

        return view('masterdata.deposit_transactions.show', compact('depositTransaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DepositTransaction $depositTransaction)
    {
        // Kode edit() Anda sudah benar, tidak perlu diubah.
        return view('masterdata.deposit_transactions.edit', compact('depositTransaction'));
    }

    /**
     * Mengupdate transaksi di database.
     */
    public function update(Request $request, DepositTransaction $depositTransaction)
    {
        // 1. Validasi, termasuk untuk file gambar baru
        $validatedData = $request->validate([
            'agreement_id' => ['required', 'exists:agreements,id'],
            'deposit_date' => ['required', 'date', Rule::unique('deposit_transactions')->where('agreement_id', $request->agreement_id)->ignore($depositTransaction->id)],
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:255',
            'proof_of_transfer' => 'nullable|image|mimes:jpeg,png,jpg|max:1024', // Maks 1MB
        ]);

        // 2. Pisahkan data untuk diupdate
        $transactionData = Arr::except($validatedData, ['proof_of_transfer']);

        // 3. ✅ Logika untuk handle file upload baru
        if ($request->hasFile('proof_of_transfer')) {
            // Hapus bukti transfer lama jika ada
            if ($depositTransaction->proof_of_transfer && file_exists(public_path($depositTransaction->proof_of_transfer))) {
                unlink(public_path($depositTransaction->proof_of_transfer));
            }

            // Simpan bukti transfer yang baru
            $imageName = time() . '_proof.' . $request->proof_of_transfer->extension();
            $request->proof_of_transfer->move(public_path('uploads/proofs'), $imageName);
            $transactionData['proof_of_transfer'] = 'uploads/proofs/' . $imageName;
        }

        $depositTransaction->update($transactionData);

        return redirect()->route('masterdata.deposit-transactions.index')
            ->with('success', 'Setoran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DepositTransaction $depositTransaction)
    {
        try {
            $depositTransaction->delete();
        } catch (\Exception $e) {
            Log::error('DepositTransactionController@destroy: Error deleting deposit transaction: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus setoran: ' . $e->getMessage());
        }

        return redirect()->route('masterdata.deposit-transactions.index')->with('success', 'Setoran berhasil dihapus!');
    }

    /**
     * Mark a deposit transaction as validated. (For Admin/Leader)
     */
    public function validateDeposit(DepositTransaction $depositTransaction)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isLeader()) {
            abort(403, 'Unauthorized action.');
        }

        $depositTransaction->update([
            'is_validated' => true,
            'validation_date' => now(),
        ]);

        return redirect()->back()->with('success', 'Setoran berhasil divalidasi!');
    }

    /**
     * AJAX endpoint to search for active agreements for Select2.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchActiveAgreements(Request $request)
    {
        $search = $request->input('term'); // Select2 sends the search term as 'term'
        Log::info('DepositTransactionController@searchActiveAgreements: Search term received: ' . $search);

        // Filter perjanjian yang berstatus 'active'
        $query = Agreement::where('status', 'active');

        // Terapkan pencarian berdasarkan nomor perjanjian atau nama koordinator lapangan
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('agreement_number', 'like', '%' . $search . '%')
                    ->orWhereHas('fieldCoordinator.user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Eager load relasi yang dibutuhkan untuk menampilkan teks di Select2
        $agreements = $query->with(['fieldCoordinator.user', 'parkingLocations.roadSection'])
            ->limit(10) // Batasi hasil untuk performa
            ->get();

        $results = [];
        foreach ($agreements as $agreement) {
            $text = $agreement->agreement_number . ' (Korlap: ' . ($agreement->fieldCoordinator->user->name ?? 'N/A') . ')';
            if ($agreement->parkingLocations->isNotEmpty()) {
                $text .= ' - Lokasi: ' . $agreement->parkingLocations->pluck('name')->join(', ');
            }
            $results[] = [
                'id' => $agreement->id,
                'text' => $text,
            ];
        }

        Log::info('DepositTransactionController@searchActiveAgreements: Returning ' . count($results) . ' results.');
        return response()->json(['results' => $results]);
    }

    public function generatePdf(DepositTransaction $depositTransaction)
    {
        // Eager load semua relasi yang dibutuhkan
        $depositTransaction->load(['agreement.fieldCoordinator.user', 'creator']);

        // Generate PDF
        $pdf = Pdf::loadView('pdf.deposit_receipt', compact('depositTransaction'));

        // Tampilkan PDF di browser
        return $pdf->stream('bukti_setor_' . $depositTransaction->id . '.pdf');
    }
}
