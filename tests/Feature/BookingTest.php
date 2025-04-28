<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Booking;

class BookingTest extends TestCase
{
    // use DatabaseMigrations, WithFaker;

    

    public function test_booking_form_can_be_accessed()
    {
        $response = $this->get(route('booking.index'));

        $response->assertStatus(200);
        $response->assertViewIs('booking');
    }

    public function test_booking_validation_fails_with_empty_data()
    {
        $response = $this->post(route('booking.store'), []);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['nama_pemesan', 'no_handphone', 'alamat', 'catatan_perbaikan']);
    }

    public function test_booking_can_be_created()
    {
        $data = [
            'nama_pemesan' => $this->faker->name,
            'no_handphone' => $this->faker->phoneNumber,
            'alamat' => $this->faker->address,
            'catatan_perbaikan' => $this->faker->sentence,
        ];

        $response = $this->post(route('booking.store'), $data);

        $response->assertRedirect(route('booking.success'));

        $this->assertDatabaseHas('bookings', [
            'nama_pemesan' => $data['nama_pemesan'],
            'no_handphone' => $data['no_handphone'],
            'alamat' => $data['alamat'],
            'catatan_perbaikan' => $data['catatan_perbaikan'],
        ]);
    }
}
