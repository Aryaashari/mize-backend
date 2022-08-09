<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Size;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "name_group", "due_dates"];


    // Relation to group_size pivot table
    public function sizes() {
        return $this->belongsToMany(Size::class);
    }
}
