<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;
    protected $primaryKey = "bkId";
    public $incrementing = false;
    protected $fillable = ['bkId','bkName','bkAccount','bkNumberic','bkImage','bkState'];
}
