<?php

namespace App\Models\Emplyee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;
    protected $primaryKey = 'ctId';
    public $incrementing = false;
    protected $fillable = ['ctId','ctname','ctstate'];
}
