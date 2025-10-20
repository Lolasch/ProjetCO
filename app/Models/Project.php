<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';
    protected $primaryKey = 'id_project';  // nom exact de la colonne dans ta table
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['name'];

    public function members()
    {
        return $this->belongsToMany(
            User::class,
            'member_project',
            'project_id', // colonne dans member_project
            'user_id'     // colonne dans member_project
        )
        ->withPivot('role')
        ->withTimestamps();
    }
}
