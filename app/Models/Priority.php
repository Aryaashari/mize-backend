<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Size;

class Priority extends Model
{
    use HasFactory;

    protected $guarded = ["id"];


    // Relation to Size
    public function size() {
        return $this->belongsTo(Size::class);
    }
}
