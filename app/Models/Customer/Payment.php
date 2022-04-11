<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $primaryKey = "pmId";
    public $incrementing = false;
    protected $fillable = ['pmId','invoince_Id','bank_Id','pmImage','pmNumbersix','pmPrice','pmState'];
}
