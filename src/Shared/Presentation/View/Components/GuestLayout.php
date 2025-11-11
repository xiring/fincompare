<?php
namespace Src\Shared\Presentation\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * GuestLayout class.
 *
 * @package Src\Shared\Presentation\View\Components
 */
class GuestLayout extends Component
{
    public function render(): View
    {
        return view('layouts.guest');
    }
}
