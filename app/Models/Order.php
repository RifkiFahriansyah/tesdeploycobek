<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code','table_number','customer_token',
        'customer_name','customer_phone','customer_email', 'customer_note',
        'subtotal','other_fees','total',
        'status','paid_at','expires_at',
        'payment_ref','qr_string','qr_image_url',
    ];

    protected $casts = [
        'subtotal'   => 'decimal:0',
        'other_fees' => 'decimal:0',
        'total'      => 'decimal:0',
        'paid_at'    => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(PurchaseHistory::class);
    }

    // ---- Helpers bisnis ----
    public function addItem(Menu $menu, int $qty, ?string $notes = null): PurchaseHistory
    {
        $snap   = $menu->toSnapshot();
        $line   = (int)$snap['unit_price'] * $qty;

        return $this->items()->create([
            'menu_id'    => $menu->id,
            'menu_name'  => $snap['menu_name'],
            'unit_price' => $snap['unit_price'],
            'qty'        => $qty,
            'line_total' => $line,
            'notes'      => $notes,
        ]);
    }

    public function recalcTotals(): void
    {
        $sub = (int) $this->items()->sum('line_total');
        $this->subtotal = $sub;
        $this->total    = $sub + (int) $this->other_fees;
        $this->save();
    }

    public function markPaid(string $ref = null): void
    {
        $this->status   = 'paid';
        $this->paid_at  = now();
        if ($ref) $this->payment_ref = $ref;
        $this->save();
    }

    public function markExpired(): void
    {
        $this->status = 'expired';
        $this->save();
    }

    public function isExpired(?Carbon $now = null): bool
    {
        $now = $now ?? now();
        return $this->status === 'pending'
            && $this->expires_at !== null
            && $now->greaterThan($this->expires_at);
    }
}
