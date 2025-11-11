<?php
namespace Src\Shared\Presentation\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * AppLayout class.
 *
 * @package Src\Shared\Presentation\View\Components
 */
class AppLayout extends Component
{
    public function render(): View
    {
        return view('layouts.app');
    }
}
