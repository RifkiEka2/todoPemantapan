<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

    class SubTask extends Model
    {
        use HasFactory;
        
        protected $fillable = [
            'task_id', 'title', 'status', 'priority', 'deadline'
        ];
        protected $casts = [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deadline' => 'datetime',
        ];


    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
