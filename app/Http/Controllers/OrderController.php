<?php
namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\PurchaseHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Http\Request;


class OrderController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'table_number'    => ['required', 'regex:/^[1-8]$/'],
            'customer_token'  => ['required', 'string', 'max:100'],
            'customer_name'   => ['required', 'string', 'max:40'],
            'customer_phone'  => ['required', 'string', 'max:20'],
            'customer_email'  => ['required', 'email:rfc', 'max:255'], // gunakan email:rfc,dns di prod
            'customer_note'   => ['nullable', 'string', 'max:255'],
            'other_fees'      => ['nullable', 'integer', 'min:0'],
            'items'           => ['required', 'array', 'min:1'],
            'items.*.menu_id' => ['required', 'integer', 'exists:menus,id'],
            'items.*.qty'     => ['required', 'integer', 'min:1'],
        ]);

        // hitung subtotal aman dari DB
        $menuMap = Menu::whereIn('id', collect($data['items'])->pluck('menu_id'))
            ->get()
            ->keyBy('id');

        $subtotal = 0;
        foreach ($data['items'] as $it) {
            $m = $menuMap[$it['menu_id']] ?? null;
            if (! $m) {
                return response()->json(['message' => 'Menu not found.'], 422);
            }
            $subtotal += (int) $m->price * (int) $it['qty'];
        }
        if ($subtotal <= 0) {
            return response()->json(['message' => 'Invalid order amount.'], 422);
        }

        $ppnRate   = 0.10;
        $otherFees = (int) round($subtotal * $ppnRate);
        $total     = $subtotal + $otherFees;

        $order = DB::transaction(function () use ($data, $subtotal, $otherFees, $total, $menuMap) {
            $order = Order::create([
                'order_code'     => Str::upper(Str::random(10)),
                'table_number'   => $data['table_number'],
                'customer_token' => $data['customer_token'],
                'customer_name'  => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
                'customer_email' => $data['customer_email'],
                'customer_note'  => $data['customer_note'] ?? null,
                'subtotal'       => $subtotal,
                'other_fees'     => $otherFees,
                'total'          => $total,
                'status'         => 'pending',
                'expires_at'     => now()->addMinutes(20),
            ]);

            foreach ($data['items'] as $it) {
                $m = $menuMap[$it['menu_id']];
                PurchaseHistory::create([
                    'order_id'   => $order->id,
                    'menu_id'    => $m->id,
                    'menu_name'  => $m->name, // snapshot
                    'unit_price' => (int) $m->price,
                    'qty'        => (int) $it['qty'],
                    'line_total' => (int) $m->price * (int) $it['qty'],
                ]);
            }

            return $order->fresh(['items']); // sekalian load items
        });

        return response()->json($order, 201);
    }

    // GET /api/orders/{order}
    public function show(Order $order)
    {
        // jika masih pending dan sudah lewat expires_at → ubah jadi expired
        if (
            $order->status === 'pending' &&
            $order->expires_at &&
            now()->greaterThan($order->expires_at)
        ) {
            $order->update(['status' => 'expired']);
        }

        return response()->json($order->fresh('items'));
    }

    // GET /api/customers/history?table=...&token=...
    public function historyByCustomer(Request $r)
    {
        // Auto-expire old pending orders
        Order::where('status', 'pending')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->update(['status' => 'expired']);

        $r->validate([
            'table' => 'required|string',
            'token' => 'required|string'
        ]);

        // Return ONLY paid orders matching current table + token
        $orders = Order::with('items')
            ->where('table_number', $r->table)
            ->where('customer_token', $r->token)
            ->where('status', 'paid')
            ->orderBy('paid_at', 'desc')
            ->get();

        return response()->json($orders);
    }

    // GET /api/customers/history/unpaid?table=...&token=...
    public function unpaidByCustomer(Request $r)
    {
        // Auto-expire old pending orders
        Order::where('status', 'pending')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->update(['status' => 'expired']);

        $r->validate([
            'table' => 'required|string',
            'token' => 'required|string'
        ]);

        // Return ONLY pending orders matching current table + token
        $orders = Order::with('items')
            ->where('table_number', $r->table)
            ->where('customer_token', $r->token)
            ->where('status', 'pending')
            ->whereNotNull('expires_at')
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }

    protected function autoExpireIfNeeded(Order $order): Order
    {
        if (
            $order->status === 'pending' &&
            $order->expires_at &&
            now()->greaterThan($order->expires_at)
        ) {
            $order->update(['status' => 'expired']);
        }

        return $order->fresh();
    }

    public function markPaid(Request $request, Order $order)
    {
        // Validate table and token
        $request->validate([
            'table_number' => 'required|string',
            'customer_token' => 'required|string',
        ]);

        $table = $request->input('table_number');
        $token = $request->input('customer_token');

        // Mark ALL pending orders for this table + token as paid
        $updated = Order::where('table_number', $table)
            ->where('customer_token', $token)
            ->where('status', 'pending')
            ->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

        return response()->json([
            'message' => "Successfully marked {$updated} order(s) as paid.",
            'orders_paid' => $updated
        ]);
    }

    public function markCancelled(Order $order)
    {
        $order = $this->autoExpireIfNeeded($order);

        if ($order->status !== 'pending') {
            return response()->json([
                'message' => "Order tidak dapat dibatalkan. Status sekarang: {$order->status}."
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $order->update([
            'status'  => 'cancelled',
        ]);

        return response()->json($order->fresh('items'));
    }

}
