<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'status', 'project_id', 'epic_id', 'sprint_id', 'user_id'];

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function epic() {
        return $this->belongsTo(Epic::class);
    }

    public function sprint() {
        return $this->belongsTo(Sprint::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
