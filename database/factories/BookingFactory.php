<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Service;
use App\Models\BookingStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get a random user with customer role for the booking
        $user = User::factory()->create();
        $user->assignRole('customer');
        
        // Get a random service or create one if none exists
        $service = Service::inRandomOrder()->first() ?? 
                  Service::create([
                      'provider_id' => User::factory()->create()->id,
                      'title_service' => $this->faker->words(3, true),
                      'description' => $this->faker->paragraph(),
                      'category_id' => 1,
                      'base_price' => $this->faker->numberBetween(100000, 1000000),
                      'label_unit' => 'Jasa',
                      'availbility' => true,
                      'rating_avg' => $this->faker->randomFloat(1, 3, 5),
                      'image_url' => 'services/default.jpg'
                  ]);
        
        // Get a random booking status or create one if none exists
        $status = BookingStatus::inRandomOrder()->first() ?? 
                 BookingStatus::create([
                     'status_code' => 'PENDING',
                     'display_name' => 'Menunggu',
                     'color_code' => '#FFA500',
                     'requires_action' => true
                 ]);
        
        // Generate booking date (today to 30 days in future)
        $bookingDate = Carbon::now()->addDays($this->faker->numberBetween(1, 30))->format('Y-m-d');
        
        // Generate booking time (8 AM to 5 PM)
        $bookingTime = Carbon::createFromTime($this->faker->numberBetween(8, 17), 0, 0)->format('H:i:s');
        
        // Calculate amounts
        $finalAmount = $this->faker->numberBetween(100000, 1000000);
        $dpAmount = $finalAmount * 0.3; // 30% down payment
        
        return [
            'user_id' => $user->id,
            'service_id' => $service->service_id,
            'nama_pemesan' => $user->name,
            'service_name' => $service->title_service,
            'tanggal_booking' => $bookingDate,
            'waktu_booking' => $bookingTime,
            'catatan_perbaikan' => $this->faker->optional(0.7)->paragraph(),
            'status_id' => $status->id,
            'status_code' => $status->status_code,
            'dp_amount' => $dpAmount,
            'final_amount' => $finalAmount,
            'assigned_worker_id' => null, // Will be set in specific states
            'assigned_at' => null,
            'accepted_at' => null,
            'dp_paid_at' => null,
            'final_paid_at' => null,
            'completed_at' => null,
            'rating' => null,
            'feedback' => null,
        ];
    }
    
    /**
     * State for a booking with assigned worker
     */
    public function withAssignedWorker()
    {
        return $this->state(function (array $attributes) {
            // Create a worker/tukang user
            $worker = User::factory()->create();
            $worker->assignRole('tukang');
            
            return [
                'assigned_worker_id' => $worker->id,
                'assigned_at' => Carbon::now(),
                'status_id' => BookingStatus::where('status_code', 'WAITING_WORKER_CONFIRMATION')->first()?->id ?? 
                               BookingStatus::create([
                                   'status_code' => 'WAITING_WORKER_CONFIRMATION',
                                   'display_name' => 'Menunggu Konfirmasi Tukang',
                                   'color_code' => '#0000FF',
                                   'requires_action' => true
                               ])->id,
                'status_code' => 'WAITING_WORKER_CONFIRMATION',
            ];
        });
    }
    
    /**
     * State for a booking with DP payment validated
     */
    public function withDpValidated()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => BookingStatus::where('status_code', 'DP_VALIDATED')->first()?->id ?? 
                               BookingStatus::create([
                                   'status_code' => 'DP_VALIDATED',
                                   'display_name' => 'DP Tervalidasi',
                                   'color_code' => '#FFA500',
                                   'requires_action' => true
                               ])->id,
                'status_code' => 'DP_VALIDATED',
                'dp_paid_at' => Carbon::now()->subHours(rand(1, 24)),
            ];
        });
    }
    
    /**
     * State for a booking with final payment validation in progress
     */
    public function withFinalPaymentValidation()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => BookingStatus::where('status_code', 'VALIDATING_FINAL_PAYMENT')->first()?->id ?? 
                               BookingStatus::create([
                                   'status_code' => 'VALIDATING_FINAL_PAYMENT',
                                   'display_name' => 'Validasi Pembayaran Akhir',
                                   'color_code' => '#FFA500',
                                   'requires_action' => true
                               ])->id,
                'status_code' => 'VALIDATING_FINAL_PAYMENT',
            ];
        });
    }
    
    /**
     * State for a completed booking
     */
    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => BookingStatus::where('status_code', 'COMPLETED')->first()?->id ?? 
                               BookingStatus::create([
                                   'status_code' => 'COMPLETED',
                                   'display_name' => 'Selesai',
                                   'color_code' => '#00FF00',
                                   'requires_action' => false
                               ])->id,
                'status_code' => 'COMPLETED',
                'dp_paid_at' => Carbon::now()->subDays(rand(3, 7)),
                'final_paid_at' => Carbon::now()->subHours(rand(1, 24)),
                'completed_at' => Carbon::now(),
                'rating' => $this->faker->numberBetween(3, 5),
                'feedback' => $this->faker->optional(0.7)->paragraph(),
            ];
        });
    }
}
