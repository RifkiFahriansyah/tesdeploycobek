<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id','menu_id','menu_name','unit_price','qty','line_total',
    ];

    protected $casts = [
        'unit_price' => 'decimal:0',
        'line_total' => 'decimal:0',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function computeLineTotal(): int
    {
        return (int)$this->unit_price * (int)$this->qty;
    }
}
