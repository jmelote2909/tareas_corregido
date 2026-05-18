<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\Employee;
use Livewire\Component;

class DetalleTarea extends Component
{
    public $taskId;
    public $newComment = '';
    public $isEditing = false;
    
    public $editTitle;
    public $editDescription;
    public $editDueDate;

    public function mount($id)
    {
        $this->taskId = $id;
        $task = Task::findOrFail($id);
        
        $this->editTitle = $task->title;
        $this->editDescription = $task->description;
        $this->editDueDate = $task->due_date ? $task->due_date->format('Y-m-d') : '';
    }

    public function addComment()
    {
        $this->validate(['newComment' => 'required|min:1']);
        
        TaskComment::create([
            'task_id' => $this->taskId,
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'text' => $this->newComment,
        ]);

        $this->newComment = '';
    }

    public function saveEdit()
    {
        $task = Task::findOrFail($this->taskId);
        $task->update([
            'title' => $this->editTitle,
            'description' => $this->editDescription,
            'due_date' => $this->editDueDate ?: null,
        ]);
        $this->isEditing = false;
    }

    public function updateStatus($status)
    {
        $task = Task::findOrFail($this->taskId);
        $task->update(['status' => $status]);
    }

    public function updatePriority($priority)
    {
        $task = Task::findOrFail($this->taskId);
        $task->update(['priority' => $priority]);
    }

    public function assignTo($employeeId)
    {
        $task = Task::findOrFail($this->taskId);
        $task->update(['assigned_to_id' => $employeeId ?: null]);
    }

    public function deleteTask()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Acción no autorizada.');
        }

        $task = Task::findOrFail($this->taskId);
        
        // Safety cascade deletion
        $task->comments()->delete();
        $task->attachments()->delete();
        $task->delete();

        return redirect()->route('dashboard');
    }

    public function render()
    {
        $task = Task::with(['requestedBy', 'assignedTo', 'comments', 'attachments'])->findOrFail($this->taskId);
        $employees = Employee::where('is_active', true)->get();
        $isAdmin = auth()->user()->role === 'admin';

        return view('livewire.detalle-tarea', [
            'task' => $task,
            'employees' => $employees,
            'isAdmin' => $isAdmin,
        ])->layout('layouts.app');
    }
}
