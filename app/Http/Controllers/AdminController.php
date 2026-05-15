<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Message;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // ============================================
    // GET /admin/login — Show login page
    // ============================================
    public function showLogin()
    {
        if (session('admin')) {
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
            'password' => 'required|string',
        ]);

        if ($request->password !== env('ADMIN_PASSWORD')) {
            return back()->with('error', 'Invalid admin password.');
        }

        session(['admin' => true]);

        AuditLog::log('Admin Login', 'Admin logged in successfully.');

        return redirect()->route('admin.dashboard');
    }

    // ============================================
    // POST /admin/logout — Handle logout
    // ============================================
    public function logout()
    {
        session()->forget('admin');
        return redirect()->route('admin.login');
    }

    // ============================================
    // GET /admin/dashboard — Main dashboard
    // ============================================
    public function dashboard(Request $request)
    {
        // ── Stats ─────────────────────────────────────────────────────
        $totalOrders   = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalRevenue  = Order::sum('total_price');
        $totalGallons  = Order::sum('gallons');
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

        return view('admin.dashboard', compact(
            'totalOrders', 'pendingOrders', 'totalRevenue',
            'totalGallons', 'orderTypes', 'orders',
            'messages', 'unreadCount', 'auditLogs',
            'reportStats', 'reportBreakdown', 'reportOrders',
            'period', 'start', 'end'
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

        AuditLog::log('Order Update', "Order #{$id} status changed to {$request->status}.");

        return back()->with('success', 'Order updated successfully!');
    }

    // ============================================
    // DELETE /admin/orders/{id} — Delete order
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

        AuditLog::log('Order Deletion', "Order #{$id} was deleted.");

        return back()->with('success', 'Order deleted successfully!');
    }

}