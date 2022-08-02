<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    protected $fillable = ['car_model_id', 'license_plate', 'available', 'km'];

    public function rules()
    {
        return [
            'car_model_id' => 'required|exists:car_models,id',
            'license_plate' => 'required|unique',
            'available' => 'required|boolean',
            'km' => 'required|integer',
        ];
    }

    public function carModel()
    {
        return $this->belongsTo(Car::class);
    }
}
