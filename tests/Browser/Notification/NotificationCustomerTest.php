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

class NotificationCustomerTest extends DuskTestCase
{
    /**
     * Test customer can see notifications
     */
    public function test_customer_can_see_notifications()
    {
        $this->browse(function (Browser $browser) {
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

            // Create a notification for the customer
            $notification = new Notification();
            $notification->user_id = $customer->id;
            $notification->title = 'Test Notification';
            $notification->message = 'This is a test notification';
            $notification->type = 'info';
            $notification->link = '/';
            $notification->read_at = null;
            $notification->save();

            // Login as customer
            $browser->visit('/login')
                    ->type('email', 'customer@tatafix.com')
                    ->type('password', 'customer123')
                    ->press('Login')
                    ->waitForLocation('/')
                    ->assertPathIs('/');

            // Check if notification badge is visible
            $browser->assertPresent('#notification-badge')
                    ->click('#notification-button');

            // Check if notification is visible in dropdown
            $browser->waitFor('#notification-dropdown')
                    ->assertSee('Test Notification')
                    ->assertSee('This is a test notification');

            // Clean up
            $notification->delete();
        });
    }

    /**
     * Test customer can mark notification as read
     */
    public function test_customer_can_mark_notification_as_read()
    {
        $this->browse(function (Browser $browser) {
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

            // Create a notification for the customer
            $notification = new Notification();
            $notification->user_id = $customer->id;
            $notification->title = 'Read Test Notification';
            $notification->message = 'This notification should be marked as read';
            $notification->type = 'info';
            $notification->link = '/';
            $notification->read_at = null;
            $notification->save();

            // Login as customer
            $browser->visit('/login')
                    ->type('email', 'customer@tatafix.com')
                    ->type('password', 'customer123')
                    ->press('Login')
                    ->waitForLocation('/')
                    ->assertPathIs('/');

            // Check notification badge shows count
            $browser->assertPresent('#notification-badge')
                    ->click('#notification-button')
                    ->waitFor('#notification-dropdown');

            // Click on notification to mark as read
            $browser->click('.notification-item a')
                    ->pause(1000); // Wait for AJAX request to complete

            // Go back and check if notification badge is gone
            $browser->visit('/')
                    ->assertMissing('#notification-badge');

            // Clean up
            $notification->delete();
        });
    }

    /**
     * Test customer can mark all notifications as read
     */
    public function test_customer_can_mark_all_notifications_as_read()
    {
        $this->browse(function (Browser $browser) {
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

            // Create multiple notifications
            for ($i = 1; $i <= 3; $i++) {
                $notification = new Notification();
                $notification->user_id = $customer->id;
                $notification->title = "Test Notification {$i}";
                $notification->message = "This is test notification {$i}";
                $notification->type = 'info';
                $notification->link = '/';
                $notification->read_at = null;
                $notification->save();
            }

            // Login as customer
            $browser->visit('/login')
                    ->type('email', 'customer@tatafix.com')
                    ->type('password', 'customer123')
                    ->press('Login')
                    ->waitForLocation('/')
                    ->assertPathIs('/');

            // Check notification badge shows count
            $browser->assertPresent('#notification-badge')
                    ->click('#notification-button')
                    ->waitFor('#notification-dropdown');

            // Click "Mark all as read" button
            $browser->click('#mark-all-read')
                    ->pause(1000); // Wait for AJAX request to complete

            // Check notification badge is gone
            $browser->assertMissing('#notification-badge');

            // Clean up
            Notification::where('user_id', $customer->id)->delete();
        });
    }

    /**
     * Test customer can delete notification
     */
    public function test_customer_can_delete_notification()
    {
        $this->browse(function (Browser $browser) {
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

            // Create a notification for customer
            $notification = new Notification();
            $notification->user_id = $customer->id;
            $notification->title = 'Delete Test Notification';
            $notification->message = 'This notification should be deleted';
            $notification->type = 'info';
            $notification->link = '/';
            $notification->read_at = null;
            $notification->save();

            // Login as customer
            $browser->visit('/login')
                    ->type('email', 'customer@tatafix.com')
                    ->type('password', 'customer123')
                    ->press('Login')
                    ->waitForLocation('/')
                    ->assertPathIs('/');

            // Check notification badge shows count
            $browser->assertPresent('#notification-badge')
                    ->click('#notification-button')
                    ->waitFor('#notification-dropdown')
                    ->assertSee('Delete Test Notification');

            // Delete the notification
            $browser->click('.delete-notification')
                    ->waitFor('div.swal2-popup', 5)  // Wait for confirmation dialog
                    ->click('button.swal2-confirm')  // Confirm deletion
                    ->pause(1000)  // Wait for animation
                    ->assertDontSee('Delete Test Notification');

            // Verify notification badge is gone
            $browser->assertMissing('#notification-badge');

            // Clean up
            Notification::where('user_id', $customer->id)->delete();
        });
    }
}