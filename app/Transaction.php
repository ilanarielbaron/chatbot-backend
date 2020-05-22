<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['user_id', 'transaction_type_id', 'amount', 'currency'];

    protected $hidden = ['created_at','updated_at'];

    /**
     * Get the transaction type that owns the transaction.
     */
    public function TransactionType()
    {
        return $this->belongsTo('App\TransactionType');
    }
    /**
     * Get the transaction type that owns the transaction.
     */
    public function User()
    {
        return $this->belongsTo('App\User');
    }
}
