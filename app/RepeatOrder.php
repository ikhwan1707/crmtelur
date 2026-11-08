<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RepeatOrder extends Model
{
    protected $fillable = ['customer_id', 'product_id', 'interval_hari', 'jumlah_rata2', 'last_order_date', 'next_order_date', 'status'];
    protected $casts = ['last_order_date' => 'date', 'next_order_date' => 'date'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}