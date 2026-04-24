<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Models\User;

#[Fillable(['user_id', 'order_id', 'amount', 'status', 'snap_token'])]
class Topup extends Model
{

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
