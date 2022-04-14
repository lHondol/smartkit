<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BundleProduct extends Model
{
    use HasFactory;

    protected $table = 'bundle_product';

    protected $keyType = 'string';

    protected $casts = [
        'product_id' => 'string',
        'bundle_id' => 'string'
    ];
}
