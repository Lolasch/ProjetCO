<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use App\Models\Project;
use App\Models\Sprint;

class Epic extends Model
{
    use HasFactory;

    protected $table = 'epics';
    protected $primaryKey = 'id_epic';
    protected $fillable = ['name', 'project_id', 'sprint_id'];

    public function sprint()
    {
        return $this->belongsTo(Sprint::class, 'sprint_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'epic_id');
    }
}
