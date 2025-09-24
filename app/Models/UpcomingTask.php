<?php
// app/Models/UpcomingTask.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpcomingTask extends Model
{
    protected $table = 'upcoming_tasks'; // Vista, no tabla
    public $timestamps = false; // Las vistas no tienen timestamps
}
