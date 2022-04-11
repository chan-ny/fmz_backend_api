<?php

namespace App\Models\Emplyee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    use HasFactory;
    protected $primaryKey = 'stId';
    public $incrementing = false;
    protected $fillable = ['stId','import_Id','storage_Id','stQty'];

}
