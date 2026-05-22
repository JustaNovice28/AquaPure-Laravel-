<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Setting;

class OrderController extends Controller
{
    // ============================================
    // POST /order — Place a new order
    // ============================================
    public function store(Request $request)
    {
        $request->validate([
            'gallons'    => 'required|integer|min:1',
            'order_type' => 'required|in:walk-in,delivery',
        ]);

        $orderType = $request->order_type;

        // Delivery-specific validation
        if ($orderType === 'delivery') {
            $request->validate([
                'address'       => 'required|string',
                'delivery_date' => 'required|date',
                'delivery_time' => 'required|string',
            ]);
        }

        // Calculate price
        $basePrice = Setting::getValue('base_price_per_gallon', 25.00);
        $deliverySmallPrice = Setting::getValue('delivery_small_order_price', 30.00);
        $bulkThreshold = (int) Setting::getValue('delivery_bulk_threshold', 5);

        $gallons = $request->gallons;
        if ($orderType === 'delivery') {
            $pricePerGallon = $gallons >= $bulkThreshold ? $basePrice : $deliverySmallPrice;
        } else {
            $pricePerGallon = $basePrice;
        }

        $totalPrice = $gallons * $pricePerGallon;

        $order = Order::create([
            'customer_name'   => $request->customer_name,
            'phone'           => $request->phone,
            'address'         => $request->address,
            'gallons'         => $gallons,
            'order_type'      => $orderType,
            'price_per_gallon'=> $pricePerGallon,
            'total_price'     => $totalPrice,
            'delivery_date'   => $request->delivery_date,
            'delivery_time'   => $request->delivery_time,
            'notes'           => $request->notes,
            'status'          => 'pending',
        ]);

        return redirect()->route('receipt', $order->id);
    }
}