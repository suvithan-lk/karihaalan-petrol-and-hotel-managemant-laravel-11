<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Orders extends Component
{
    public $orders;

    public function mount(){
        $this->orders = Order::all();
    }

    public function render()
    {
        return view('livewire.orders')->extends('layouts.dashboard');
    }
}
