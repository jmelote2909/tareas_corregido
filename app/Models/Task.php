<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Task extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'due_time',
        'assigned_to_id',
        'requested_by_id',
        'project_id',
        'category_id',
        'requester_name',
        'requester_department',
        'is_archived',
        'completed_at',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });

        static::updating(function ($task) {
            if ($task->isDirty('status')) {
                if ($task->status === 'completada' && is_null($task->completed_at)) {
                    $task->completed_at = now();
                } elseif ($task->status !== 'completada') {
                    $task->completed_at = null;
                }
            }
        });

        static::updated(function ($task) {
            if ($task->wasChanged('assigned_to_id') && $task->assigned_to_id) {
                try {
                    $employee = $task->assignedTo;
                    if ($employee && $employee->email) {
                        if (auth()->check() && auth()->user()->email_password) {
                            config([
                                'mail.default' => 'smtp',
                                'mail.mailers.smtp.host' => 'smtp.gmail.com',
                                'mail.mailers.smtp.port' => 587,
                                'mail.mailers.smtp.encryption' => 'tls',
                                'mail.mailers.smtp.username' => auth()->user()->email,
                                'mail.mailers.smtp.password' => auth()->user()->email_password,
                                'mail.from.address' => auth()->user()->email,
                                'mail.from.name' => auth()->user()->name,
                            ]);
                        }
                        \Illuminate\Support\Facades\Mail::to($employee->email)->send(new \App\Mail\TaskAssignedMail($task));
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Error al enviar correo de asignacion de tarea: ' . $e->getMessage());
                }
            }

            if ($task->wasChanged('status') && $task->status === 'completada') {
                try {
                    $receiver = \App\Models\Setting::get('mail_receiver', 'infraestructura@cimacableados.com');
                    \Illuminate\Support\Facades\Mail::to($receiver)->send(new \App\Mail\TaskCompletedMail($task));
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Error al enviar correo de tarea completada: ' . $e->getMessage());
                }
            }
        });
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(Employee::class, 'assigned_to_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }

    public function attachments()
    {
        return $this->hasMany(TaskAttachment::class);
    }

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'is_archived' => 'boolean',
            'completed_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
