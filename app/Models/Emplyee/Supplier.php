<?php

namespace App\Models\Emplyee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $primaryKey = 'supId';
    public $incrementing = false;
    protected $fillable = ['supId','supgender','supfullname','suptell','supaddress','supemail','supstate'];
}
