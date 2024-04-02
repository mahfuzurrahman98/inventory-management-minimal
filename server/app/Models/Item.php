<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model {
    use HasFactory;

    // define relationship with inventory
    public function inventory() {
        return $this->belongsTo(Inventory::class);
    }
}
