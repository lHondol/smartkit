<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'string'
    ];

    protected $keyType = 'string';

    protected $hidden = ['pivot'];

    public function bundles()
    {
        return $this->belongsToMany(Bundle::class, 'bundle_product');
    }
}
