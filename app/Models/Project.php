<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';
    protected $primaryKey = 'id_project';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['name'];

    public function members()
    {
        return $this->belongsToMany(
            User::class,
            'member_project',
            'project_id',
            'user_id'
        )
        ->withPivot('role')
        ->withTimestamps();
    }

    public function sprints()
    {
        return $this->hasMany(Sprint::class, 'project_id');
    }
}
