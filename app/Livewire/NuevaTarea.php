<?php

namespace App\Livewire;

use Livewire\Component;

class NuevaTarea extends Component
{
    public function render()
    {
        return view('livewire.nueva-tarea')->layout('layouts.app');
    }
}
