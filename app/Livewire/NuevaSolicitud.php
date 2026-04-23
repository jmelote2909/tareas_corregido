<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\TaskAttachment;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class NuevaSolicitud extends Component
{
    use WithFileUploads;

    public $title = '';
    public $description = '';
    public $category = '';
    public $priority = '';
    public $dueDate = '';
    public $requesterName = '';
    public $requesterDepartment = '';
    
    public $photos = [];
    public $audio = null;

    public $success = false;
    public $error = '';

    public function mount()
    {
        $this->requesterName = auth()->user()->name;
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|min:5',
            'description' => 'required|min:10',
            'category' => 'required',
            'priority' => 'required',
            'requesterDepartment' => 'required',
        ]);

        try {
            $task = Task::create([
                'title' => $this->title,
                'description' => $this->description,
                'status' => 'pendiente',
                'priority' => $this->priority,
                'due_date' => $this->dueDate ?: null,
                'requested_by_id' => auth()->id(),
                // 'category' => $this->category, // Add category to tasks table if needed
            ]);

            // Handle photos
            foreach ($this->photos as $photo) {
                $path = $photo->store('attachments', 'public');
                TaskAttachment::create([
                    'task_id' => $task->id,
                    'type' => 'image',
                    'url' => '/storage/' . $path,
                    'name' => $photo->getClientOriginalName(),
                ]);
            }

            $this->success = true;
            
            return redirect()->to('/dashboard');
        } catch (\Exception $e) {
            $this->error = 'Error al crear la solicitud: ' . $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.nueva-solicitud')->layout('layouts.app');
    }
}
