<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $fillable = [
        'clients_id',
        'car_id',
        'date_start_route',
        'date_final_undefined_route',
        'date_final_defined_route',
        'price_daily',
        'km_start',
        'km_final'
    ];
    public function rules()
    {
        return [];
    }
}
