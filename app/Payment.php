<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['order_id', 'tgl_bayar', 'jumlah', 'keterangan'];
    protected $casts = ['tgl_bayar' => 'date'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}