<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Message;
use App\Models\AuditLog;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // ============================================
    // GET /admin/login — Show login page
    // ============================================
    public function showLogin()
    {
        if (session('user_id')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    // ============================================
    // POST /admin/login — Handle login
    // ============================================
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Invalid username or password.');
        }

        session([
            'user_id' => $user->id,
            'role'    => $user->role,
        ]);

        AuditLog::log('User Login', "{$user->username} ({$user->role}) logged in.", $user->id);

        return redirect()->route('admin.dashboard');
    }

    // ============================================
    // POST /admin/logout — Handle logout
    // ============================================
    public function logout(Request $request)
    {
        $user = $request->attributes->get('auth_user');
        if ($user) {
            AuditLog::log('User Logout', "{$user->username} logged out.", $user->id);
        }

        session()->forget(['user_id', 'role']);
        return redirect()->route('admin.login');
    }

    // ============================================
    // GET /admin/dashboard — Main dashboard
    // ============================================
    public function dashboard(Request $request)
    {
        $user = $request->attributes->get('auth_user');

        // ── Stats ─────────────────────────────────────────────────────
        $totalOrders   = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalRevenue  = Order::where('status', 'completed')->sum('total_price');
        $totalGallons  = Order::where('status', 'completed')->sum('gallons');
        $orderTypes    = Order::select('order_type',
                            DB::raw('COUNT(*) as count'),
                            DB::raw('SUM(total_price) as revenue'))
                            ->groupBy('order_type')
                            ->get();

        $orders      = Order::latest()->get();
        $messages    = Message::latest()->get();
        $unreadCount = Message::where('status', 'unread')->count();
        $auditLogs   = AuditLog::latest('created_at')->take(20)->get();

        // ── Reports data (for Reports tab) ────────────────────────────
        $period = $request->query('period', 'all');
        $start  = $request->query('start');
        $end    = $request->query('end');

        $reportQuery = Order::query();

        match ($period) {
            'daily'   => $reportQuery->whereDate('created_at', today()),
            'weekly'  => $reportQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
            'monthly' => $reportQuery->whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year),
            'custom'  => $start && $end
                            ? $reportQuery->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
                            : null,
            default   => null,
        };

        $reportStats = [
            'totalOrders'   => (clone $reportQuery)->count(),
            'totalRevenue'  => (clone $reportQuery)->sum('total_price'),
            'totalGallons'  => (clone $reportQuery)->sum('gallons'),
            'avgOrderValue' => (clone $reportQuery)->avg('total_price') ?? 0,
        ];

        $reportBreakdown = (clone $reportQuery)
            ->select('order_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_price) as revenue'))
            ->groupBy('order_type')
            ->get();

        $reportOrders = (clone $reportQuery)
            ->select('id','customer_name','phone','order_type','gallons','total_price','status','created_at')
            ->latest()
            ->get();

        // ── Pricing data ────────────────────────────────────────────
        $settings = null;
        if ($request->query('tab') === 'pricing') {
            $settings = [
                'base_price_per_gallon'      => Setting::getValue('base_price_per_gallon', 25.00),
                'delivery_small_order_price' => Setting::getValue('delivery_small_order_price', 30.00),
                'delivery_bulk_threshold'    => (int) Setting::getValue('delivery_bulk_threshold', 5.00),
            ];
        }

        // ── Users list (admin only) ─────────────────────────────────
        $cashiers = collect();
        if ($user->isAdmin() && $request->query('tab') === 'users') {
            $cashiers = User::where('role', 'cashier')->latest()->get();
        }

        return view('admin.dashboard', compact(
            'totalOrders', 'pendingOrders', 'totalRevenue',
            'totalGallons', 'orderTypes', 'orders',
            'messages', 'unreadCount', 'auditLogs',
            'reportStats', 'reportBreakdown', 'reportOrders',
            'period', 'start', 'end', 'settings',
            'user', 'cashiers'
        ));
    }

    // ============================================
    // PUT /admin/orders/{id} — Update order status
    // ============================================
    public function updateOrder(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;

        if ($request->filled('notes')) {
            $order->notes = $request->notes;
        }

        $order->save();

        $user = $request->attributes->get('auth_user');
        AuditLog::log('Order Update', "Order #{$id} status changed to {$request->status}.", $user->id);

        return back()->with('success', 'Order updated successfully!');
    }

    // ============================================
    // DELETE /admin/orders/{id} — Archive order
    // ============================================
    public function deleteOrder(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        if ($request->password !== env('BOSS_PASSWORD')) {
            return back()->with('error', 'Invalid password. Order not deleted.');
        }

        $order = Order::findOrFail($id);
        $order->delete();

        $user = $request->attributes->get('auth_user');
        AuditLog::log('Order Archived', "Order #{$id} was archived.", $user->id);

        return back()->with('success', 'Order archived successfully!');
    }

    // ============================================
    // POST /admin/pricing — Update pricing settings
    // ============================================
    public function updatePricing(Request $request)
    {
        $request->validate([
            'base_price_per_gallon'      => 'required|numeric|min:0',
            'delivery_small_order_price' => 'required|numeric|min:0',
            'delivery_bulk_threshold'    => 'required|integer|min:1',
        ]);

        $oldBasePrice      = Setting::getValue('base_price_per_gallon', 25);
        $oldDeliveryPrice  = Setting::getValue('delivery_small_order_price', 30);
        $oldThreshold      = (int) Setting::getValue('delivery_bulk_threshold', 5);

        Setting::setValue('base_price_per_gallon',      $request->base_price_per_gallon);
        Setting::setValue('delivery_small_order_price', $request->delivery_small_order_price);
        Setting::setValue('delivery_bulk_threshold',    $request->delivery_bulk_threshold);

        $changes = [];
        if ($oldBasePrice != $request->base_price_per_gallon) {
            $changes[] = "base price ₱{$oldBasePrice} → ₱{$request->base_price_per_gallon}";
        }
        if ($oldDeliveryPrice != $request->delivery_small_order_price) {
            $changes[] = "delivery small order price ₱{$oldDeliveryPrice} → ₱{$request->delivery_small_order_price}";
        }
        if ($oldThreshold != $request->delivery_bulk_threshold) {
            $changes[] = "bulk threshold {$oldThreshold} → {$request->delivery_bulk_threshold} gal";
        }

        $description = empty($changes) ? 'No changes made.' : 'Pricing updated: ' . implode(', ', $changes) . '.';

        $user = $request->attributes->get('auth_user');
        AuditLog::log('Pricing Update', $description, $user->id);

        return redirect()->route('admin.dashboard', ['tab' => 'pricing'])
            ->with('success', 'Pricing settings updated successfully!');
    }

    // ============================================
    // GET /admin/users — Show user management (admin only)
    // (rendered via dashboard tab, but this method could also be used directly)
    // ============================================
    public function showUsers(Request $request)
    {
        $user = $request->attributes->get('auth_user');
        $cashiers = User::where('role', 'cashier')->latest()->get();
        return view('admin.dashboard', compact('cashiers', 'user'));
    }

    // ============================================
    // POST /admin/users — Create new cashier account
    // ============================================
    public function storeUser(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => $request->password, // auto-hashed by model cast
            'role'     => 'cashier',
        ]);

        $admin = $request->attributes->get('auth_user');
        AuditLog::log('Cashier Created', "Cashier account '{$user->username}' created by {$admin->username}.", $admin->id);

        return redirect()->route('admin.dashboard', ['tab' => 'users'])
            ->with('success', "Cashier '{$user->username}' created successfully!");
    }

    // ============================================
    // DELETE /admin/users/{id} — Delete a cashier account
    // ============================================
    public function deleteUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Prevent admin from deleting themselves
        if ($user->isAdmin()) {
            return back()->with('error', 'Cannot delete admin account.');
        }

        $username = $user->username;
        $user->delete();

        $admin = $request->attributes->get('auth_user');
        AuditLog::log('Cashier Deleted', "Cashier '{$username}' deleted by {$admin->username}.", $admin->id);

        return redirect()->route('admin.dashboard', ['tab' => 'users'])
            ->with('success', "Cashier '{$username}' deleted.");
    }
}