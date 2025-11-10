<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user'; // clé primaire personnalisée
    public $incrementing = true;       // auto-incrément
    protected $keyType = 'int';        // type entier

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // 🔹 Relation avec les projets via member_project
    public function projects()
    {
        return $this->belongsToMany(
            Project::class,
            'member_project',   // table pivot
            'user_id',          // FK dans member_project vers users.id_user
            'project_id'        // FK dans member_project vers projects.id_projet
        )
        ->withPivot('role')
        ->withTimestamps();
    }

    public function notifications()
{
    return $this->hasMany(Notification::class, 'user_id');
}

}
