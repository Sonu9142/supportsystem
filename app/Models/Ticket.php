<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'issue_category',
        'services',
        'title',
        'description',
        'transaction_id',
        'file_path',
        'assigned_to',
        'acknowledged',
        'acknowledged_at',
        'assigned_at',
        'status'
    ];

    public function developer()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
