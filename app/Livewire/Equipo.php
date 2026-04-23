<?php

namespace App\Livewire;

use App\Models\Employee;
use App\Models\User;
use App\Models\Task;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class Equipo extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $editingEmployeeId = null;

    public $name;
    public $email;
    public $username;
    public $password;
    public $role = 'employee';
    public $department = 'Infraestructura y TI';
    public $position;
    public $color = '#6366f1';
    public $avatar;
    public $isActive = true;

    protected $rules = [
        'name' => 'required',
        'email' => 'required|email',
    ];

    public function openModal($id = null)
    {
        $this->resetForm();
        if ($id) {
            $this->editingEmployeeId = $id;
            $employee = Employee::findOrFail($id);
            $this->name = $employee->name;
            $this->email = $employee->email;
            $this->role = $employee->role === 'Administrador' ? 'admin' : 'employee';
            $this->color = $employee->color;
            $this->isActive = $employee->is_active;
            
            // Get data from linked user
            if ($employee->user_id) {
                $user = User::find($employee->user_id);
                if ($user) {
                    $this->username = $user->username;
                    $this->department = $user->department;
                    $this->position = $user->position;
                }
            }
        }
        $this->showModal = true;
    }

    public function resetForm()
    {
        $this->editingEmployeeId = null;
        $this->name = '';
        $this->email = '';
        $this->username = '';
        $this->password = '';
        $this->role = 'employee';
        $this->department = 'Infraestructura y TI';
        $this->position = '';
        $this->color = '#6366f1';
        $this->avatar = null;
        $this->isActive = true;
    }

    public function save()
    {
        if (!$this->editingEmployeeId) {
            $this->rules['password'] = 'required|min:6';
        }
        
        $this->validate();

        // 1. Create or Update User (Only if data exists)
        $userId = $this->editingEmployeeId ? Employee::find($this->editingEmployeeId)->user_id : null;
        
        if ($this->username) {
            $userData = [
                'name' => $this->name,
                'email' => $this->email,
                'username' => $this->username,
                'role' => $this->role,
                'department' => $this->department,
                'position' => $this->position,
                'is_active' => $this->isActive,
            ];

            if ($this->password) {
                $userData['password'] = $this->password;
            }

            if ($userId) {
                User::find($userId)?->update($userData);
            } else {
                $user = User::create($userData);
                $userId = $user->id;
            }
        }

        // 2. Create or Update Employee
        $employeeData = [
            'user_id' => $userId,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role === 'admin' ? 'Administrador' : 'Empleado',
            'color' => $this->color,
            'is_active' => $this->isActive,
        ];

        if ($this->avatar) {
            $employeeData['avatar'] = '/storage/' . $this->avatar->store('avatars', 'public');
        }

        if ($this->editingEmployeeId) {
            $employee->update($employeeData);
        } else {
            Employee::create($employeeData);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function deleteEmployee($id)
    {
        $employee = Employee::findOrFail($id);
        if ($employee->user_id) {
            User::find($employee->user_id)?->delete();
        }
        $employee->delete();
    }

    public function render()
    {
        $employees = Employee::all();
        $tasks = Task::all();

        return view('livewire.equipo', [
            'employees' => $employees,
            'tasks' => $tasks,
        ])->layout('layouts.app');
    }
}
