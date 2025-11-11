<?php

namespace View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * AppLayout class.
 */
class AppLayout extends Component
{
    public function render(): View
    {
        return view('layouts.app');
    }
}
