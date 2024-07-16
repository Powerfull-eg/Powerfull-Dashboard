<?php

namespace App\View\Layouts;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Website extends Component
{
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('layouts.website.base');
    }
}
