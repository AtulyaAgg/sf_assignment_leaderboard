<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Winner extends Model {
    use HasFactory, HasUuids;

    protected $fillable = ['user_id', 'points', 'timestamp'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}

