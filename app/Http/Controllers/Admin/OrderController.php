<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderShippedMail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['items', 'user'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(20)->withQueryString();

        $statusLabels  = Order::STATUS_LABELS;
        $statusCounts  = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('admin.orders.index', compact('orders', 'statusLabels', 'statusCounts'));
    }

    public function show(Order $order)
    {
        $order->load(['items', 'user', 'coupon']);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status'        => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,refunded',
            'tracking_code' => 'nullable|string|max:100',
            'admin_notes'   => 'nullable|string|max:1000',
        ]);

        $previousStatus = $order->status;
        $order->update($data);

        // Si se marcó como enviado, actualizar fecha y enviar email
        if ($data['status'] === 'shipped' && $previousStatus !== 'shipped') {
            $order->update(['shipped_at' => now()]);

            try {
                Mail::to($order->customer_email)->queue(new OrderShippedMail($order));
            } catch (\Exception $e) {
                // No interrumpir
            }
        }

        if ($data['status'] === 'delivered' && $previousStatus !== 'delivered') {
            $order->update(['delivered_at' => now()]);
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Pedido actualizado correctamente.');
    }
}
