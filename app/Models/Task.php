<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';
    protected $primaryKey = 'id_task';
    protected $fillable = [
        'title',
        'status',
        'assigned_to',
        'due_date',
        'epic_id',
        'sprint_id',
    ];

    // ===============================
    // RELATIONS
    // ===============================

    // Une tâche appartient à un epic
    public function epic()
    {
        return $this->belongsTo(Epic::class, 'epic_id');
    }

    // Une tâche peut appartenir à un sprint
    public function sprint()
    {
        return $this->belongsTo(Sprint::class, 'sprint_id');
    }

    // Une tâche peut être assignée à un utilisateur
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // On supprime complètement la relation avec KanbanList
}
