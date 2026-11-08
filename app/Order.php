<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['customer_id', 'user_id', 'tgl_order', 'tgl_kirim', 'status', 'metode_bayar', 'total'];
    protected $casts = ['tgl_order' => 'date', 'tgl_kirim' => 'date'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function totalDibayar()
    {
        return $this->payments()->sum('jumlah');
    }
    public function sisaPiutang()
    {
        return $this->total - $this->totalDibayar();
    }
}