<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $incrementing = true;
    protected $keyType = 'int';

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

    public function projects()
    {
        return $this->belongsToMany(
            Project::class,
            'member_project',
            'user_id',
            'project_id'       
        )
        ->withPivot('role')
        ->withTimestamps();
    }

    public function notifications()
{
    return $this->hasMany(Notification::class, 'user_id');
}

}
