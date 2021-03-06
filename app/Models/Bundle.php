<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'string'
    ];

    private $toSelect = ['product_id', 'name', 'quantity'];

    protected $hidden = ['pivot'];

    protected $keyType = 'string';

    public function products()
    {
        return $this->belongsToMany(Product::class, 'bundle_product')->select($this->toSelect);
    }
}
