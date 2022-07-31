<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    use HasFactory;

    protected $fillable = ['brand_id', 'name', 'image', 'num_doors', 'places', 'airbag', 'abs'];

    public function rules()
    {
        return [
            'brand_id' => 'exists:brands,id',
            'name' => 'required|unique:car_models,name,' . $this->id . '|min:3',
            'image' => 'required|file|mimes:png,jpg,jpeg',
            'num_doors' => 'required|integer|digits_between:1,5',
            'places' => 'required|integer|digits_between:1,8',
            'airbag' => 'required|boolean',
            'abs' => 'required|boolean',
        ];
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
