<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Epic;
use App\Models\Sprint;
use App\Models\User;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';
    protected $primaryKey = 'id_task';
    protected $fillable = [
        'title',
        'description',
        'status',
        'assigned_to',
        'due_date',
        'epic_id',
        'sprint_id',
    ];

    // Relation avec Epic
    public function epic()
    {
        return $this->belongsTo(Epic::class, 'epic_id');
    }

    // Relation avec Sprint
    public function sprint()
    {
        return $this->belongsTo(Sprint::class, 'sprint_id');
    }

    // Relation avec l'utilisateur assigné
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
