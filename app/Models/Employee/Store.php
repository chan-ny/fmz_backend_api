<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    protected $primaryKey = 'storeId';
    public $incrementing = false;
    protected $fillable = [
        'storeId', 'store_name', 'store_phone', 'store_email', 'store_website',
        'store_address', 'store_image','store_state'
    ];
}
