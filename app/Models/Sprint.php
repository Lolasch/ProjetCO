<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sprint extends Model
{
    use HasFactory;

    protected $table = 'sprints';
    protected $primaryKey = 'id_sprint';
    protected $fillable = ['name', 'start_date', 'end_date', 'project_id', 'color'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function epics()
    {
        return $this->hasMany(Epic::class, 'sprint_id');

    }
    // Une sprint peut avoir des tâches (hors epic)
public function tasks()
{
    return $this->hasMany(Task::class, 'sprint_id')->whereNull('epic_id');
}

}
