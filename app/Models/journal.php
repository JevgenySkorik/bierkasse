<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\product;

class journal extends Model
{
    public function product() {
        return $this->belongsTo(product::class);
    }
}
