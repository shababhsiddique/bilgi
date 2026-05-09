<?php

namespace App\Livewire;

use Livewire\Component;

class TestTailwind extends Component
{

    public string $name = '';

    public function render()
    {
        return view('livewire.test-tailwind');
    }
}
