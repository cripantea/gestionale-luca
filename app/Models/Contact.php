<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'notes',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function isCliente(): bool
    {
        return $this->status === 'cliente';
    }

    public function promoteToCliente(): void
    {
        $this->update(['status' => 'cliente']);
    }
}
