<?php

namespace App\Models\Emplyee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $primaryKey = 'pdId';
    public $incrementing = false;
    protected $fillable = ['pdId','brand_Id','categories_Id','colour_Id','supplier_Id',
                           'pdname','pdfullname','pdcost','pdrate','pdprice',
                           'pdprogression','pdphotos_main','pdphotos_sub'];
    
    public function brands()
    {
        return $this->hasMany(Brand::class);
    }
    public function categories()
    {
        return $this->hasMany(Categories::class);
    }
    public function colours()
    {
        return $this->hasMany(Colour::class);
    }
    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }
}
