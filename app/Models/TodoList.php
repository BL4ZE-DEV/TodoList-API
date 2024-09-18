<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TodoList extends Model
{
    use HasFactory;

    protected $fillable = [
        'todo',
        'userId'
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at'
    ];


    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
