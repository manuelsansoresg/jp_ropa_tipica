<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = ['name', 'text', 'active', 'is_demo'];

    protected function casts(): array
    {
        return ['active' => 'boolean', 'is_demo' => 'boolean'];
    }
}
