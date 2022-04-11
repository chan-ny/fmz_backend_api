<?php

namespace App\Models\Emplyee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colour extends Model
{
    use HasFactory;
    protected $primaryKey = 'cId';
    public $incrementing = false;
    protected $fillable = ['cId','cname','cstate'];
}
