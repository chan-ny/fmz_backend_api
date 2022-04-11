<?php

namespace App\Models\Emplyee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $primaryKey = 'bId';
    public $incrementing = false;
    protected $fillable = ['bId','bname','bstate'];
}
