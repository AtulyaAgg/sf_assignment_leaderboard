<?php
 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class User extends Model {
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'age', 'address', 'points'];

    public function winners() {
        return $this->hasMany(Winner::class);
    }
}
