<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['nama', 'satuan', 'harga'];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function repeatOrders()
    {
        return $this->hasMany(RepeatOrder::class);
    }
}