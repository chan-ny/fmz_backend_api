<?php

namespace App\Models\Emplyee\Imports;

use App\Models\Emplyee\Supplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    use HasFactory;
    protected $primaryKey = "impId";
    public $incrementing = false;
    protected $fillable = ['impId','supplier_Id', 'impstate'];


    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }
}
