<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $primaryKey = 'transaction_id';

    public $timestamps = false;

    protected $fillable = [
        'wallet_id',
        'amount',
        'type',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id');
    }
}