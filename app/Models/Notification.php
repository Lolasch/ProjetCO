<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    protected $primaryKey = 'id_notification';
    protected $fillable = [
        'user_id','type','task_id','project_id','title','body','is_read'
    ];

    public function user()   { return $this->belongsTo(User::class, 'user_id'); }
    public function task()   { return $this->belongsTo(Task::class, 'task_id'); }
    public function project(){ return $this->belongsTo(Project::class, 'project_id'); }
}

use Carbon\Carbon;

$now = Carbon::today();
foreach ($tasks as $task) {
    if (!$task->due_date) continue;
    $due = Carbon::parse($task->due_date)->startOfDay();
    $diff = $now->diffInDays($due, false);

    if ($diff < 0) {
        $alerte = "Retard ! La tâche est échue depuis " . abs($diff) . " jour(s).";
    } elseif ($diff == 0) {
        $alerte = "Dernier jour ! La tâche atteint son échéance aujourd'hui.";
    } elseif ($diff <= 2) {
        $alerte = "Attention : échéance dans $diff jour(s).";
    } else {
        $alerte = false;
    }
    // $alerte sera affiché sous la tâche ou dans la cloche
}
