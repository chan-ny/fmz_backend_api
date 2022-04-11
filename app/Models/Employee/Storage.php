<?php

namespace App\Models\Employee;

use App\Models\Emplyee\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    use HasFactory;
    protected $primaryKey = "srId";
    public $incrementing = false;
    protected $fillable = ['srId', 'product_Id', 'size_Id', 'srqty','srstate'];

    public $timestamps = false;
    
    public function products()
    {
        return $this->hasMany(Product::class);
        
    }
}
