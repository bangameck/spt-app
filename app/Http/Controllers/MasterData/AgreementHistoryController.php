<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\AgreementHistory;
use App\Models\Agreement; // Diperlukan untuk eager load dan filter
use App\Models\FieldCoordinator; // Untuk menampilkan nama korlap di Select2
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Leader;
use Barryvdh\DomPDF\Facade\Pdf;

class AgreementHistoryController extends Controller
{
    /**
     * Display a listing of the resource (Agreement History).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->input('search'); // Pencarian umum
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $agreementId = $request->input('agreement_id'); // Filter berdasarkan ID Perjanjian

        // Mulai query AgreementHistory dengan eager load relasi agreement dan changer
        $query = AgreementHistory::with(['agreement.fieldCoordinator.user', 'agreement.leader.user', 'changer']);

        // Terapkan filter berdasarkan ID Perjanjian jika dipilih
        if ($agreementId) {
            $query->where('agreement_id', $agreementId);
        }

        // Terapkan filter pencarian umum jika ada
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('event_type', 'like', '%' . $search . '%')
                    ->orWhere('notes', 'like', '%' . $search . '%')
                    ->orWhereHas('agreement', function ($agreementQuery) use ($search) {
                        $agreementQuery->where('agreement_number', 'like', '%' . $search . '%')
                            ->orWhereHas('fieldCoordinator.user', function ($fcUserQuery) use ($search) {
                                $fcUserQuery->where('name', 'like', '%' . $search . '%');
                            });
                    })
                    ->orWhereHas('changer', function ($changerQuery) use ($search) {
                        $changerQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Terapkan filter rentang waktu
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, Carbon::parse($endDate)->endOfDay()]);
        } elseif ($startDate) {
            $query->where('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('created_at', '<=', Carbon::parse($endDate)->endOfDay());
        }

        // Ambil data riwayat dengan paginasi
        $agreementHistories = $query->latest('created_at')->paginate(10);

        // Jika agreementId dipilih, ambil juga detail perjanjian tersebut untuk ditampilkan
        $selectedAgreement = null;
        if ($agreementId) {
            $selectedAgreement = Agreement::with(['fieldCoordinator.user', 'leader.user'])->find($agreementId);
        }

        // Kirimkan semua query pencarian ke view agar input search tetap terisi
        return view('masterdata.agreement_histories.index', compact(
            'agreementHistories',
            'search',
            'startDate',
            'endDate',
            'agreementId', // Kirimkan ID perjanjian yang dipilih
            'selectedAgreement' // Kirimkan objek perjanjian yang dipilih
        ));
    }

    /**
     * Generate PDF for a historical agreement snapshot.
     * (This method remains largely the same as before, as it's called with a specific history record)
     *
     * @param  \App\Models\AgreementHistory  $history
     * @return \Illuminate\Http\Response
     */
    public function generatePdf(AgreementHistory $history)
    {
        if (empty($history->old_value) && empty($history->new_value)) {
            abort(404, 'No historical data available for this PDF.');
        }

        $agreementData = [];
        $parkingLocationsSnapshot = [];

        if ($history->event_type === 'agreement_created' && isset($history->new_value['parking_locations_snapshot'])) {
            $agreementData = $history->new_value;
            $parkingLocationsSnapshot = $history->new_value['parking_locations_snapshot'];
        } elseif (isset($history->old_value['parking_locations_snapshot'])) {
            $agreementData = $history->old_value;
            $parkingLocationsSnapshot = $history->old_value['parking_locations_snapshot'];
        } else {
            $agreementData = $history->agreement->toArray();
            $parkingLocationsSnapshot = [];
        }

        $agreement = (object) $agreementData;

        $agreement->leader = Leader::with('user')->find($agreement->leader_id);
        $agreement->fieldCoordinator = FieldCoordinator::with('user')->find($agreement->field_coordinator_id);

        $agreement->parkingLocations = collect($parkingLocationsSnapshot)->map(function ($loc) {
            $locObject = (object) $loc;
            if (is_string($locObject->road_section)) {
                $locObject->roadSection = (object) ['name' => $locObject->road_section];
            }
            return $locObject;
        });

        $pdf = Pdf::loadView('pdf.agreement', compact('agreement'));
        return $pdf->stream('Perjanjian_Historis_' . ($history->agreement->agreement_number ?? 'N/A') . '_' . $history->event_type . '.pdf');
    }

    /**
     * AJAX endpoint to search for agreements for history filter (Select2).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchAgreementsForHistory(Request $request)
    {
        $search = $request->input('term'); // Select2 sends the search term as 'term'

        $query = Agreement::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('agreement_number', 'like', '%' . $search . '%')
                    ->orWhereHas('fieldCoordinator.user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Eager load relations needed for display in Select2 options
        $agreements = $query->with(['fieldCoordinator.user'])
            ->limit(10) // Limit results for performance
            ->get();

        $results = [];
        foreach ($agreements as $agreement) {
            $text = $agreement->agreement_number . ' (Korlap: ' . ($agreement->fieldCoordinator->user->name ?? 'N/A') . ')';
            $results[] = [
                'id' => $agreement->id,
                'text' => $text,
            ];
        }

        return response()->json(['results' => $results]);
    }
}
