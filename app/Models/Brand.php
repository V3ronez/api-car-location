<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'image'];

    public function validate()
    {
        return [
            'name' => 'required|unique:brands',
            'image' => 'required',
        ];
    }
}
