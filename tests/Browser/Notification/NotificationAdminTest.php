<?php

namespace Tests\Browser\Notification;

use App\Models\Booking;
use App\Models\Notification;
use App\Models\Service;
use App\Models\StatusBooking;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class NotificationAdminTest extends DuskTestCase
{
    /**
     * Test admin receives payment notification
     */
    public function test_admin_receives_payment_notification()
    {
        $this->browse(function (Browser $browser) {
            // Create a notification for admin
            $admin = User::where('email', 'admin@tatafix.com')->first();
            if (!$admin) {
                $admin = User::factory()->create([
                    'email' => 'admin@tatafix.com',
                    'password' => bcrypt('admin123'),
                    'name' => 'admin'
                ]);
                $admin->assignRole('admin');
            }

            // Create a customer
            $customer = User::where('email', 'customer@tatafix.com')->first();
            if (!$customer) {
                $customer = User::factory()->create([
                    'email' => 'customer@tatafix.com',
                    'password' => bcrypt('customer123'),
                    'name' => 'customer'
                ]);
                $customer->assignRole('customer');
            }

            // Create a service
            $service = Service::first() ?? Service::factory()->create();

            // Create a booking
            $booking = Booking::factory()->create([
                'user_id' => $customer->id,
                'service_id' => $service->id,
                'status_id' => StatusBooking::where('name', 'Menunggu Pembayaran DP')->first()->id
            ]);

            // Create a payment notification for admin
            $notification = new Notification();
            $notification->user_id = $admin->id;
            $notification->title = 'Pembayaran DP Diterima';
            $notification->message = "Customer {$customer->name} telah melakukan pembayaran DP untuk booking #{$booking->id}";
            $notification->type = 'warning';
            $notification->link = route('admin.bookings.index');
            $notification->read_at = null;
            $notification->save();

            // Login as admin
            $browser->visit('/login')
                    ->type('email', 'admin@tatafix.com')
                    ->type('password', 'admin123')
                    ->press('Login')
                    ->waitForLocation('/admin/dashboard');

            // Check if notification appears in dropdown
            $browser->click('#notification-button')
                    ->waitFor('#notification-dropdown')
                    ->assertSee('Pembayaran DP Diterima')
                    ->assertSee($customer->name)
                    ->assertVisible('.notification-item');

            // Clean up
            $notification->delete();
            $booking->delete();
        });
    }

    /**
     * Test admin can delete notification
     */
    public function test_admin_can_delete_notification()
    {
        $this->browse(function (Browser $browser) {
            // Get admin user
            $admin = User::where('email', 'admin@tatafix.com')->first();
            if (!$admin) {
                $admin = User::factory()->create([
                    'email' => 'admin@tatafix.com',
                    'password' => bcrypt('admin123'),
                    'name' => 'admin'
                ]);
                $admin->assignRole('admin');
            }

            // Create a notification for admin
            $notification = new Notification();
            $notification->user_id = $admin->id;
            $notification->title = 'Test Delete Notification';
            $notification->message = "This notification should be deleted";
            $notification->type = 'info';
            $notification->link = '/admin/dashboard';
            $notification->read_at = null;
            $notification->save();

            // Login as admin
            $browser->visit('/login')
                    ->type('email', 'admin@tatafix.com')
                    ->type('password', 'admin123')
                    ->press('Login')
                    ->waitForLocation('/admin/dashboard');

            // Check notification badge shows count
            $browser->assertPresent('#notification-badge')
                    ->click('#notification-button')
                    ->waitFor('#notification-dropdown')
                    ->assertSee('Test Delete Notification');

            // Delete the notification
            $browser->click('.delete-notification')
                    ->waitFor('div.swal2-popup', 5)  // Wait for confirmation dialog
                    ->click('button.swal2-confirm')  // Confirm deletion
                    ->pause(1000)  // Wait for animation
                    ->assertDontSee('Test Delete Notification');

            // Verify notification badge is gone
            $browser->assertMissing('#notification-badge');
        });
    }

    /**
     * Test multiple admins receive notifications
     */
    public function test_multiple_admins_receive_notifications()
    {
        $this->browse(function (Browser $browser) {
            // Get admin user
            $admin = User::where('email', 'admin@tatafix.com')->first();
            if (!$admin) {
                $admin = User::factory()->create([
                    'email' => 'admin@tatafix.com',
                    'password' => bcrypt('admin123'),
                    'name' => 'admin'
                ]);
                $admin->assignRole('admin');
            }

            // Get customer user
            $customer = User::where('email', 'customer@tatafix.com')->first();
            if (!$customer) {
                $customer = User::factory()->create([
                    'email' => 'customer@tatafix.com',
                    'password' => bcrypt('customer123'),
                    'name' => 'customer'
                ]);
                $customer->assignRole('customer');
            }

            // Create a service
            $service = Service::first() ?? Service::factory()->create();

            // Create a booking
            $booking = Booking::factory()->create([
                'user_id' => $customer->id,
                'service_id' => $service->id,
                'status_id' => StatusBooking::where('name', 'Menunggu Pembayaran DP')->first()->id
            ]);

            // Create a notification for admin
            $notification = new Notification();
            $notification->user_id = $admin->id;
            $notification->title = 'Pembayaran Pelunasan Diterima';
            $notification->message = "Customer {$customer->name} telah melakukan pembayaran pelunasan untuk booking #{$booking->id}";
            $notification->type = 'warning';
            $notification->link = route('admin.bookings.index');
            $notification->read_at = null;
            $notification->save();

            // Login as admin
            $browser->visit('/login')
                    ->type('email', 'admin@tatafix.com')
                    ->type('password', 'admin123')
                    ->press('Login')
                    ->waitForLocation('/admin/dashboard');

            // Check if notification appears in dropdown
            $browser->click('#notification-button')
                    ->waitFor('#notification-dropdown')
                    ->assertSee('Pembayaran Pelunasan Diterima')
                    ->assertSee($customer->name)
                    ->assertVisible('.notification-item');

            // Clean up
            $notification->delete();
            $booking->delete();
        });
    }
}