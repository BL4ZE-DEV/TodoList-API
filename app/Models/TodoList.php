<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TodoList extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'todoId';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'todo',
        'userId'
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at'
    ];


    protected static function boot(){
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
