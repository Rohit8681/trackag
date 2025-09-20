<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class TripSeeder extends Seeder
{
    public function run(): void
    {
        // Get all existing companies
        $companies = Company::with('users')->get(); // Make sure each company has related users

        foreach ($companies as $company) {
            // If the company has users, seed trips
            if ($company->users->count() === 0) {
                continue; // Skip if no users
            }

            foreach (range(1, 5) as $i) {
                $user = $company->users->random(); // Pick a random user from this company
                $startTime = Carbon::now()->subDays(rand(1, 10))->setTime(rand(7, 10), rand(0, 59));
                $endTime = (clone $startTime)->addMinutes(rand(30, 180));

                $approvalStatus = collect(['pending', 'approved', 'denied'])->random();
                $approvedBy = ($approvalStatus !== 'pending') ? User::inRandomOrder()->first()->id : null;
                $approvalReason = $approvalStatus === 'denied' ? 'Insufficient details provided' : null;
                $approvedAt = ($approvalStatus !== 'pending') ? Carbon::now()->subDays(rand(0, 5)) : null;

                DB::table('trips')->insert([
                    'user_id'           => $user->id,
                    'company_id'        => $company->id,
                    'trip_date'         => $startTime->toDateString(),
                    'start_time'        => $startTime,
                    'end_time'          => $endTime,
                    'start_lat'         => 23.0225 + rand(-50, 50) / 1000,
                    'start_lng'         => 72.5714 + rand(-50, 50) / 1000,
                    'end_lat'           => 23.0225 + rand(-50, 50) / 1000,
                    'end_lng'           => 72.5714 + rand(-50, 50) / 1000,
                    'total_distance_km' => rand(1, 50) + rand(0, 99) / 100,
                    'travel_mode'       => 1,
                    'purpose'           => 1,
                    'tour_type'         => 1,
                    'status'            => 'completed',
                    'approval_status'   => $approvalStatus,
                    'approval_reason'   => $approvalReason,
                    'approved_by'       => $approvedBy,
                    'approved_at'       => $approvedAt,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
            }
        }
    }
}
