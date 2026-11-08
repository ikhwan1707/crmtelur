<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['nama', 'kategori', 'no_wa', 'alamat', 'status', 'tgl_daftar'];
    protected $casts = ['tgl_daftar' => 'date'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function repeatOrders()
    {
        return $this->hasMany(RepeatOrder::class);
    }
    public function followups()
    {
        return $this->hasMany(Followup::class);
    }
}