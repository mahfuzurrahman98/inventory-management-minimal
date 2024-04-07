<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model {
    use HasFactory;
    use SoftDeletes;

    // fillable fields
    protected $fillable = [
        'name',
        'description',
        'user_id'
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
