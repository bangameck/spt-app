<?php

namespace App\Http\Controllers\MasterData; // Namespace yang sudah kita sepakati

use App\Http\Controllers\Controller;
use App\Models\AgreementHistory; // Pastikan model AgreementHistory diimpor
use App\Models\Agreement; // Diperlukan untuk relasi jika tidak di-eager load di history
use App\Models\FieldCoordinator; // Diperlukan untuk detail korlap di PDF
use App\Models\Leader; // Diperlukan untuk detail leader di PDF
use App\Models\ParkingLocation; // Diperlukan untuk detail lokasi parkir di PDF
use App\Models\RoadSection; // Diperlukan untuk detail ruas jalan di PDF
use Illuminate\Http\Request;
use Carbon\Carbon; // Untuk logika tanggal
use Barryvdh\DomPDF\Facade\Pdf; // Pastikan ini diimpor
use App\Helpers\NumberToWords; // Pastikan ini diimpor

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
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Mulai query AgreementHistory dengan eager load relasi agreement dan changer
        $query = AgreementHistory::with(['agreement', 'changer']);

        // Terapkan filter pencarian umum jika ada
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('event_type', 'like', '%' . $search . '%')
                    ->orWhere('notes', 'like', '%' . $search . '%')
                    ->orWhereHas('agreement', function ($agreementQuery) use ($search) {
                        $agreementQuery->where('agreement_number', 'like', '%' . $search . '%');
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

        // Kirimkan semua query pencarian ke view agar input search tetap terisi
        return view('masterdata.agreement_histories.index', compact(
            'agreementHistories',
            'search',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Generate PDF for a historical agreement snapshot.
     *
     * @param  \App\Models\AgreementHistory  $history
     * @return \Illuminate\Http\Response
     */
    public function generatePdf(AgreementHistory $history)
    {
        // Pastikan ada data lama atau baru yang bisa dirender
        if (empty($history->old_value) && empty($history->new_value)) {
            abort(404, 'No historical data available for this PDF.');
        }

        // Tentukan snapshot mana yang akan digunakan:
        // Jika event adalah 'created', gunakan new_value.
        // Jika event adalah 'updated' atau 'removed', gunakan old_value.
        $agreementData = [];
        $parkingLocationsSnapshot = [];

        if ($history->event_type === 'agreement_created' && isset($history->new_value['parking_locations_snapshot'])) {
            $agreementData = $history->new_value;
            $parkingLocationsSnapshot = $history->new_value['parking_locations_snapshot'];
        } elseif (isset($history->old_value['parking_locations_snapshot'])) {
            $agreementData = $history->old_value;
            $parkingLocationsSnapshot = $history->old_value['parking_locations_snapshot'];
        } else {
            // Fallback: Jika tidak ada snapshot lokasi parkir, gunakan data perjanjian utama
            // Ini akan mengambil data perjanjian saat ini, BUKAN data historis yang diinginkan
            // Ini adalah skenario yang perlu dihindari jika old_value/new_value tidak lengkap
            $agreementData = $history->agreement->toArray();
            $parkingLocationsSnapshot = []; // Tidak ada lokasi spesifik di snapshot ini
        }

        // Reconstruct Agreement object for PDF template compatibility
        // This is a simplified object, not a full Eloquent model
        $agreement = (object) $agreementData;

        // Load related data from current database for display in PDF if not in snapshot
        // This is important for leader/fieldCoordinator data if not fully snapshotted
        // and if it's not present in the historical snapshot.
        $agreement->leader = Leader::with('user')->find($agreement->leader_id);
        $agreement->fieldCoordinator = FieldCoordinator::with('user')->find($agreement->field_coordinator_id);

        // Convert array of locations to a collection of objects for template loop
        // Ensure roadSection is properly handled if it's just a name in the snapshot
        $agreement->parkingLocations = collect($parkingLocationsSnapshot)->map(function ($loc) {
            $locObject = (object) $loc;
            // If road_section is just a name string in snapshot, convert to object for consistency
            if (is_string($locObject->road_section)) {
                $locObject->roadSection = (object) ['name' => $locObject->road_section];
            }
            return $locObject;
        });


        // Menggunakan library PDF untuk merender view ke PDF
        $pdf = Pdf::loadView('pdf.agreement', compact('agreement'));

        // Mengembalikan PDF sebagai download atau langsung di browser
        return $pdf->stream('Perjanjian_Historis_' . ($history->agreement->agreement_number ?? 'N/A') . '_' . $history->event_type . '.pdf');
    }
}
