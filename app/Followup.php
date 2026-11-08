<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Followup extends Model
{
    protected $fillable = ['customer_id', 'user_id', 'tgl_followup', 'jenis', 'catatan', 'hasil'];
    protected $casts = ['tgl_followup' => 'date'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}