<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Order;
use App\Models\Message;
use App\Models\AuditLog;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the database fresh by running raw table truncations.
     */
    public function run(): void
    {
        // ── 1. RAW TRUNCATE ON ALL APP TABLES ───────────────────────
        Schema::disableForeignKeyConstraints();
        
        DB::table('messages')->truncate();
        DB::table('orders')->truncate();
        DB::table('audit_logs')->truncate();
        
        Schema::enableForeignKeyConstraints();

        // ── 2. SEED CLEAN CUSTOMER MESSAGES ──────────────────────────────
        $messages = [
            [
                'full_name' => 'Maria Santos',
                'phone_number' => '09229876543',
                'email_address' => 'maria.santos@email.com',
                'subject' => 'Delivery Inquiry',
                'message' => 'Do you offer delivery services outside Lapasan area?',
                'status' => 'unread',
                'created_at' => Carbon::now()->subHours(4),
                'updated_at' => Carbon::now()->subHours(4),
            ],
            [
                'full_name' => 'Juan Dela Cruz',
                'phone_number' => '09171234567',
                'email_address' => 'juan.d@email.com',
                'subject' => 'Refill Inquiry',
                'message' => 'Are there custom discounts if we order more than 10 gallons?',
                'status' => 'unread',
                'created_at' => Carbon::now()->subMinutes(30),
                'updated_at' => Carbon::now()->subMinutes(30),
            ]
        ];

        foreach ($messages as $msg) {
            Message::create($msg);
        }

        // ── 3. SEED CLEAN CUSTOMER ORDERS ────────────────────────────────
        $orders = [
            [
                'customer_name' => 'Carlos Garcia',
                'phone' => '09187778899',
                'address' => 'Nazareth, CDO',
                'gallons' => 3,
                'order_type' => 'delivery',
                'price_per_gallon' => 25.00,
                'total_price' => 75.00,
                'status' => 'pending',
                'delivery_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
                'delivery_time' => '10:00 AM - 12:00 PM',
                'notes' => 'Please bring change for 500 pesos.',
                'created_at' => Carbon::now()->subHours(2),
                'updated_at' => Carbon::now()->subHours(2),
            ],
            [
                'customer_name' => null,
                'phone' => null,
                'address' => null,
                'gallons' => 2,
                'order_type' => 'walk-in',
                'price_per_gallon' => 25.00,
                'total_price' => 50.00,
                'status' => 'confirmed',
                'delivery_date' => null,
                'delivery_time' => null,
                'notes' => null,
                'created_at' => Carbon::now()->subHours(6),
                'updated_at' => Carbon::now()->subHours(3),
            ],
            [
                'customer_name' => 'Ana Lopez',
                'phone' => '09221234567',
                'address' => 'Kauswagan, CDO',
                'gallons' => 5,
                'order_type' => 'delivery',
                'price_per_gallon' => 25.00,
                'total_price' => 125.00,
                'status' => 'completed',
                'delivery_date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'delivery_time' => '2:00 PM - 4:00 PM',
                'notes' => 'Thank you for the prompt delivery!',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(1),
            ]
        ];

        foreach ($orders as $order) {
            Order::create($order);
        }

        // ── 4. SEED CLEAN SYSTEM AUDIT LOGS ──────────────────────────────
        $auditLogs = [
            [
                'action' => 'Database Raw Truncate',
                'description' => 'Cleared all table histories and reset primary indices back to 1.',
                'admin_user' => 'admin',
                'created_at' => Carbon::now(),
            ],
        ];

        foreach ($auditLogs as $log) {
            AuditLog::create($log);
        }
    }
}
