<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckTransfer extends Model
{
    use HasFactory;
    protected $fillable = ['chId', 'payment_id', 'chconfirm'];
}
