<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Agreement;
use App\Models\ParkingLocation;
use Carbon\Carbon; // Pastikan ini diimpor

class UpdateAgreementStatuses extends Command
{
    protected $signature = 'agreements:update-statuses';
    protected $description = 'Updates agreement statuses based on their end dates and manages parking location availability.';

    public function handle()
    {
        $this->info('Updating agreement statuses...');

        $today = Carbon::today();
        $tenDaysFromNow = $today->copy()->addDays(10);
        $fifteenDaysAgo = $today->copy()->subDays(15);

        // 1. Update to 'pending_renewal'
        // Perjanjian aktif yang akan berakhir dalam 10 hari ke depan
        Agreement::where('status', 'active')
            ->where('end_date', '>=', $today)
            ->where('end_date', '<=', $tenDaysFromNow)
            ->each(function (Agreement $agreement) {
                $agreement->update(['status' => 'pending_renewal']);
                $this->info("Agreement #{$agreement->agreement_number} status updated to pending_renewal.");
            });

        // 2. Update to 'expired'
        // Perjanjian aktif atau pending_renewal yang end_date-nya sudah lewat hari ini
        Agreement::whereIn('status', ['active', 'pending_renewal'])
            ->where('end_date', '<', $today)
            ->each(function (Agreement $agreement) {
                $agreement->update(['status' => 'expired']);
                $this->info("Agreement #{$agreement->agreement_number} status updated to expired.");

                // Update status lokasi parkir terkait menjadi 'tersedia'
                // Hanya lokasi yang aktif terikat dengan perjanjian ini
                foreach ($agreement->parkingLocations()->where('agreement_parking_locations.status', 'active')->get() as $parkingLocation) {
                    $parkingLocation->update(['status' => 'tersedia']);
                    $this->info("  - Parking Location {$parkingLocation->name} (ID: {$parkingLocation->id}) status updated to tersedia.");
                }
                // Juga set status di tabel pivot menjadi 'expired'
                $agreement->parkingLocations()->updateExistingPivot($agreement->parkingLocations->pluck('id')->toArray(), ['status' => 'expired']);
            });

        // 3. Update to 'terminated'
        // Perjanjian yang sudah expired lebih dari 15 hari
        Agreement::where('status', 'expired')
            ->where('end_date', '<=', $fifteenDaysAgo)
            ->each(function (Agreement $agreement) {
                $agreement->update(['status' => 'terminated']);
                $this->info("Agreement #{$agreement->agreement_number} status updated to terminated.");
                // Lokasi parkir seharusnya sudah 'tersedia' dari langkah 'expired'
                // Status pivot juga sudah 'expired'
            });

        $this->info('Agreement status update complete.');
    }
}
