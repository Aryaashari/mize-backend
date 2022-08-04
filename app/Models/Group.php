<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Size;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ["name_group"];


    // Relation to group_size pivot table
    public function size() {
        return $this->belongsToMany(Size::class);
    }
}
