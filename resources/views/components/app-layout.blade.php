@if (request()->is('admin*') || request()->routeIs('dashboard') || request()->routeIs('profile.*'))
    @include('layouts.admin', ['slot' => $slot])
@else
@include('layouts.app', ['slot' => $slot])
@endif


