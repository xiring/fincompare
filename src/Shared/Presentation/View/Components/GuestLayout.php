<?php

namespace Src\Shared\Presentation\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * GuestLayout class.
 */
class GuestLayout extends Component
{
    public function render(): View
    {
        return view('layouts.guest');
    }
}
