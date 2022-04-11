<?php

namespace App\Models\Emplyee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;
    protected $primaryKey = 'sId';
    public $incrementing = false;
    protected $fillable =['sId','sname','sdetail','state'];
}
