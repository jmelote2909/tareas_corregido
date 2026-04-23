<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class Usuarios extends Component
{
    public $showModal = false;
    public $searchQuery = '';

    // Form fields
    public $username;
    public $password;
    public $name;
    public $email;
    public $department = 'Infraestructura y TI';
    public $role = 'employee';

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function resetForm()
    {
        $this->username = '';
        $this->password = '';
        $this->name = '';
        $this->email = '';
        $this->department = 'Infraestructura y TI';
        $this->role = 'employee';
    }

    public function save()
    {
        $this->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'department' => 'required',
            'role' => 'required',
        ]);

        User::create([
            'username' => $this->username,
            'password' => $this->password, // Mutator hashes it to password_hash
            'name' => $this->name,
            'email' => $this->email,
            'department' => $this->department,
            'role' => $this->role,
        ]);

        $this->showModal = false;
        $this->resetForm();
    }

    public function render()
    {
        $usersQuery = User::query();

        if ($this->searchQuery) {
            $usersQuery->where(function($q) {
                $q->where('name', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('username', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('email', 'like', '%' . $this->searchQuery . '%');
            });
        }

        $users = $usersQuery->get();

        $stats = [
            'total' => $users->count(),
            'activos' => $users->where('is_active', true)->count(),
            'admins' => $users->where('role', 'admin')->count(),
            'empleados' => $users->where('role', 'employee')->count(),
            'solicitantes' => $users->where('role', 'requester')->count(),
        ];

        return view('livewire.usuarios', [
            'users' => $users,
            'stats' => $stats
        ])->layout('layouts.app');
    }
}
