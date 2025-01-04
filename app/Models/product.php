<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\journal;

class product extends Model
{
    public function journal() {
        return $this->hasMany(journal::class);
    }
}
