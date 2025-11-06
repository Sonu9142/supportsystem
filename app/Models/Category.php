<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ticket;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // Relationship: Category has many tickets
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
