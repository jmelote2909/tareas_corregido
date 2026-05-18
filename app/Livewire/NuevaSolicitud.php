<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\TaskAttachment;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use App\Models\Category;

class NuevaSolicitud extends Component
{
    use WithFileUploads;

    public $title = '';
    public $description = '';
    public $category_id = '';
    public $newCategoryName = '';
    public $showNewCategoryInput = false;
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
        $allowedRoles = ['admin', 'requester', 'employee'];
        if (!in_array(auth()->user()->role, $allowedRoles)) {
            return redirect()->route('dashboard');
        }
        
        $this->requesterName = auth()->user()->name;
        $this->requesterDepartment = auth()->user()->department ?? '';
    }

    public function deleteCategory($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->delete();
        }
    }

    public function toggleNewCategoryInput()
    {
        $this->showNewCategoryInput = !$this->showNewCategoryInput;
        if (!$this->showNewCategoryInput) {
            $this->newCategoryName = '';
        }
    }

    public function createCategory()
    {
        $this->validate([
            'newCategoryName' => 'required|min:3|unique:categories,name'
        ]);

        $category = \App\Models\Category::create([
            'name' => $this->newCategoryName,
            'color' => '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT),
        ]);

        $this->category_id = $category->id;
        $this->newCategoryName = '';
        $this->showNewCategoryInput = false;
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|min:5',
            'description' => 'required|min:10',
            'category_id' => 'required',
            'priority' => 'required',
            'requesterName' => 'required',
            'requesterDepartment' => 'nullable',
            'dueDate' => 'nullable|date',
        ]);

        try {
            $task = Task::create([
                'title' => $this->title,
                'description' => $this->description,
                'status' => 'pendiente',
                'priority' => $this->priority,
                'due_date' => $this->dueDate ?: null,
                'requested_by_id' => auth()->id(),
                'requester_name' => $this->requesterName,
                'requester_department' => $this->requesterDepartment,
                'category_id' => $this->category_id,
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

            // Handle audio
            if ($this->audio) {
                $path = $this->audio->store('attachments', 'public');
                TaskAttachment::create([
                    'task_id' => $task->id,
                    'type' => 'audio',
                    'url' => '/storage/' . $path,
                    'name' => $this->audio->getClientOriginalName(),
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
        return view('livewire.nueva-solicitud', [
            'users' => \App\Models\User::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get()
        ])->layout('layouts.app');
    }
}
