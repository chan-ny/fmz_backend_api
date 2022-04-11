<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellDetail extends Model
{
    use HasFactory;
    protected $primaryKey = "sdId ";
    public $incrementing = false;
    protected $fillable = ['sdId', 'invoince_Id', 'storage_Id', 'product_Id', 'sdQty', 'sdPrice'];

    public $timestamps = false;
}
