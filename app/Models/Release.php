<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Release extends Model
{
    use HasFactory;

    protected $table = 'releases';
    protected $primaryKey = 'id';
    protected $fillable = ['project_id', 'name', 'release_date', 'color'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
