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
                Rule::exists('agreements', 'id')->where(function ($query) {
                    $query->where('status', 'active');
                }),
                Rule::unique('deposit_transactions')->where(function ($query) use ($request) {
                    return $query->where('deposit_date', $request->deposit_date);
                }),
            ],
            'deposit_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:255',
        ]);

        Log::info('DepositTransactionController@store: Validation successful.', $validatedData);

        DepositTransaction::create([
            'agreement_id' => $validatedData['agreement_id'],
            'deposit_date' => $validatedData['deposit_date'],
            'amount' => $validatedData['amount'],
            'is_validated' => false,
            'validation_date' => null,
            'notes' => $validatedData['notes'],
            'created_by_user_id' => Auth::id(),
        ]);

        return redirect()->route('masterdata.deposit-transactions.index')
            ->with('success', 'Setoran berhasil dicatat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(DepositTransaction $depositTransaction)
    {
        $depositTransaction->load(['agreement.fieldCoordinator.user', 'agreement.leader.user', 'creator']);
        return view('masterdata.deposit_transactions.show', compact('depositTransaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DepositTransaction $depositTransaction)
    {
        // Untuk Select2, kita tidak perlu mengirim semua perjanjian di awal
        // Mereka akan dimuat via AJAX
        $activeAgreements = collect(); // Kirim koleksi kosong atau null

        return view('masterdata.deposit_transactions.edit', compact('depositTransaction', 'activeAgreements'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DepositTransaction $depositTransaction)
    {
        Log::info('DepositTransactionController@update: Request received.', $request->all());

        $validatedData = $request->validate([
            'agreement_id' => [
                'required',
                'exists:agreements,id', // Pastikan ID perjanjian ada
                // Perbaikan di sini: Izinkan ID perjanjian saat ini, atau perjanjian baru yang aktif
                Rule::exists('agreements', 'id')->where(function ($query) use ($depositTransaction) {
                    $query->where('status', 'active') // Harus aktif
                        ->orWhere('id', $depositTransaction->agreement_id); // ATAU ID perjanjian yang sedang terikat
                }),
                Rule::unique('deposit_transactions')->where(function ($query) use ($request) {
                    return $query->where('deposit_date', $request->deposit_date);
                })->ignore($depositTransaction->id),
            ],
            'deposit_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:255',
        ]);

        Log::info('DepositTransactionController@update: Validation successful.', $validatedData);

        $depositTransaction->update([
            'agreement_id' => $validatedData['agreement_id'],
            'deposit_date' => $validatedData['deposit_date'],
            'amount' => $validatedData['amount'],
            'notes' => $validatedData['notes'],
        ]);

        return redirect()->route('masterdata.deposit-transactions.index')
            ->with('success', 'Setoran berhasil diperbarui!');
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
}
