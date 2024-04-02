<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model {
    use HasFactory;

    // fillable fields
    protected $fillable = [
        'name',
        'description'
    ];

    // each inventory belongs to a user
    public function user() {
        return $this->belongsTo(User::class);
    }

    // each inventory has many items
    public function items() {
        return $this->hasMany(Item::class);
    }
}
