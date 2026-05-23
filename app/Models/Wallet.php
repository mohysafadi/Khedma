<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $primaryKey = 'wallet_id';

    protected $fillable = ['professional_id', 'balance'];

    public function professional()
    {
        return $this->belongsTo(Professional::class, 'professional_id');
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class, 'wallet_id');
    }
}