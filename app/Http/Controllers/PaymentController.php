<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // POST /api/payments/{order}/create  => buat QR string
    public function create(Order $order)
    {
        if ($order->status !== 'pending') {
            return response()->json(['message' => 'Order is not in pending state'], 400);
        }

        // (opsional) jika sudah kadaluarsa
        if ($order->isExpired()) {
            $order->markExpired();
            return response()->json(['message' => 'Order expired'], 410);
        }

        // QR payload sederhana (ganti dgn gateway)

        $payload = "COBEK|ORDER={$order->order_code}|TOTAL={$order->total}";
        $order->update([
            'qr_string'      => $payload,
        ]);

        return response()->json([
            'order_id'   => $order->id,
            'order_code' => $order->order_code,
            'amount'     => (int)$order->total,
            'qr_string'  => $payload,
            'expires_at' => $order->expires_at,
        ], 201);
    }

    // POST /api/payments/webhook   (dipanggil gateway saat bayar sukses)
    // Payload contoh: { "order_code":"ABCD1234", "status":"PAID", "reference":"GW-001" }
    public function webhook(Request $r)
    {
        $r->validate([
            'order_code' => 'required|string',
            'status'     => 'required|string',
            'reference'  => 'nullable|string',
        ]);

        $order = Order::where('order_code', $r->order_code)->firstOrFail();

        if (strtoupper($r->status) === 'PAID') {
            $order->markPaid($r->reference);
        } elseif (strtoupper($r->status) === 'EXPIRED') {
            $order->markExpired();
        }

        return response()->json(['ok' => true]);
    }
}
