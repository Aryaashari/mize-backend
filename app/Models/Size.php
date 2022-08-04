<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Priority;
use App\Models\Group;

class Size extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    // Relation to User
    public function user() {
        return $this->belongsTo(User::class);
    }


     // Relation to Priority
     public function priority() {
        return $this->hasOne(Priority::class);
    }


    // Relation to group_size pivot table
    public function groups() {
        return $this->belongsToMany(Group::class);
    }
}
