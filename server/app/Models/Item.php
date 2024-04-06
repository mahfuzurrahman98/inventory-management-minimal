<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model {
    use HasFactory;

    // define accessor for image
    public function getImageAttribute($value) {
        return $value ? '/api/storage/' . $value : null;
    }

    // define relationship with inventory
    public function inventory() {
        return $this->belongsTo(Inventory::class);
    }
}
