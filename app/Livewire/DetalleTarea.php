<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\Employee;
use Livewire\Component;
use Livewire\WithFileUploads;

class DetalleTarea extends Component
{
    use WithFileUploads;

    public $taskId;
    public $newComment = '';
    public $isEditing = false;
    
    public $editTitle;
    public $editDescription;
    public $editDueDate;
    public $editStatus;
    public $editPriority;
    public $editAssignedToId;

    public $newPhotos = [];
    public $newAudio = null;
    public $newDocuments = [];

    public function mount($id)
    {
        $this->taskId = $id;
        $task = Task::findOrFail($id);
        
        $this->editTitle = $task->title;
        $this->editDescription = $task->description;
        $this->editDueDate = $task->due_date ? $task->due_date->format('Y-m-d') : '';
        $this->editStatus = $task->status;
        $this->editPriority = $task->priority;
        $this->editAssignedToId = $task->assigned_to_id;
    }

    public function startEditing()
    {
        $task = Task::findOrFail($this->taskId);
        $this->editTitle = $task->title;
        $this->editDescription = $task->description;
        $this->editDueDate = $task->due_date ? $task->due_date->format('Y-m-d') : '';
        $this->editStatus = $task->status;
        $this->editPriority = $task->priority;
        $this->editAssignedToId = $task->assigned_to_id;
        $this->isEditing = true;
    }

    public function cancelEditing()
    {
        $this->isEditing = false;
    }

    public function addComment()
    {
        $this->validate(['newComment' => 'required|min:1']);
        
        TaskComment::create([
            'task_id' => $this->taskId,
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'text' => $this->newComment,
            'type' => 'comment',
        ]);

        $this->newComment = '';
    }

    public function saveEdit()
    {
        $this->validate([
            'editTitle' => 'required|min:5',
            'editDescription' => 'required|min:10',
            'editDueDate' => 'nullable|date',
        ]);

        $task = Task::findOrFail($this->taskId);
        
        $changes = [];
        if ($task->title !== $this->editTitle) $changes[] = "El título cambió.";
        if ($task->description !== $this->editDescription) $changes[] = "La descripción cambió.";
        
        $oldDate = $task->due_date ? $task->due_date->format('Y-m-d') : '';
        $newDate = $this->editDueDate ?: '';
        if ($oldDate !== $newDate) $changes[] = "La fecha límite cambió de '" . ($oldDate ?: 'N/A') . "' a '" . ($newDate ?: 'N/A') . "'.";
        
        $updateData = [
            'title' => $this->editTitle,
            'description' => $this->editDescription,
            'due_date' => $this->editDueDate ?: null,
        ];

        if (auth()->user()->role === 'admin') {
            if ($task->status !== $this->editStatus) $changes[] = "El estado cambió de '{$task->status}' a '{$this->editStatus}'.";
            if ($task->priority !== $this->editPriority) $changes[] = "La prioridad cambió de '{$task->priority}' a '{$this->editPriority}'.";
            if ($task->assigned_to_id != $this->editAssignedToId) {
                $oldName = $task->assignedTo ? $task->assignedTo->name : 'Nadie';
                $newEmp = \App\Models\Employee::find($this->editAssignedToId);
                $newName = $newEmp ? $newEmp->name : 'Nadie';
                $changes[] = "La asignación cambió de '{$oldName}' a '{$newName}'.";
            }

            $updateData['status'] = $this->editStatus;
            $updateData['priority'] = $this->editPriority;
            $updateData['assigned_to_id'] = $this->editAssignedToId ?: null;
        }

        $task->update($updateData);

        foreach ($changes as $changeText) {
            TaskComment::create([
                'task_id' => $this->taskId,
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name,
                'text' => $changeText,
                'type' => 'system',
            ]);
        }

        $this->isEditing = false;
        session()->flash('success_message', 'Tarea guardada con éxito.');
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

    public function addAttachments()
    {
        $task = Task::findOrFail($this->taskId);

        // Handle photos
        foreach ($this->newPhotos as $photo) {
            $path = $photo->store('attachments', 'public');
            \App\Models\TaskAttachment::create([
                'task_id' => $task->id,
                'type' => 'image',
                'url' => '/storage/' . $path,
                'name' => $photo->getClientOriginalName(),
            ]);
        }

        // Handle audio
        if ($this->newAudio) {
            $path = $this->newAudio->store('attachments', 'public');
            \App\Models\TaskAttachment::create([
                'task_id' => $task->id,
                'type' => 'audio',
                'url' => '/storage/' . $path,
                'name' => $this->newAudio->getClientOriginalName(),
            ]);
        }

        // Handle documents
        foreach ($this->newDocuments as $doc) {
            $path = $doc->store('attachments', 'public');
            \App\Models\TaskAttachment::create([
                'task_id' => $task->id,
                'type' => 'document',
                'url' => '/storage/' . $path,
                'name' => $doc->getClientOriginalName(),
            ]);
        }

        // Reset
        $this->newPhotos = [];
        $this->newAudio = null;
        $this->newDocuments = [];

        session()->flash('success_attachments', 'Archivos adjuntados con éxito.');
    }

    public function deleteAttachment($attachmentId)
    {
        $attachment = \App\Models\TaskAttachment::findOrFail($attachmentId);
        
        $relativePath = str_replace('/storage/', '', $attachment->url);
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($relativePath)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($relativePath);
        }

        $attachment->delete();
        session()->flash('success_attachments', 'Archivo eliminado con éxito.');
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
