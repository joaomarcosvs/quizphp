<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Answer extends Component
{
    /**
     * Create a new component instance.
     */
    public string $capital;

    public function __construct($capital)
    {
        $this->capital = $capital;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.answer');
    }
}
