<?php

namespace App\Models\Emplyee\Imports;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import_list extends Model
{
    use HasFactory;
    protected $primaryKey = "imlId";
    public $incrementing = false;
    protected $fillable = ['import_Id','storage_Id','order_qty','reciev_qty','imlprice'];

    public $timestamps = false;
}
