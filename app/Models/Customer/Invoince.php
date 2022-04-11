<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoince extends Model
{
    use HasFactory;
    protected $primaryKey = "invId";
    public $incrementing = false;
    protected $fillable  = ['invId','customer_Id','invQty','invPrice','invState'];
    
}
