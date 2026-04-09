<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'title', 
        'description', 
        'responsible', 
        'requester', 
        'due_date', 
        'status'
    ];

    // Indica que el campo due_date debe ser tratado como fecha
    protected $casts = [
        'due_date' => 'date',
    ];

    /**
     * Relación con el usuario Responsable
     */
    public function responsibleUser(): BelongsTo
    {
        // Conecta el campo 'responsible' de la tabla tasks con el 'id' de la tabla users
        return $this->belongsTo(User::class, 'responsible');
    }

    /**
     * Relación con el usuario Solicitante
     */
    public function requesterUser(): BelongsTo
    {
        // Conecta el campo 'requester' de la tabla tasks con el 'id' de la tabla users
        return $this->belongsTo(User::class, 'requester');
    }
}