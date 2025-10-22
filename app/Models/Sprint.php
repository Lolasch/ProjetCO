<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sprint extends Model
{
    protected $table = 'sprints';
    protected $primaryKey = 'id_sprint';
    protected $fillable = ['project_id', 'name', 'start_date', 'end_date'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
