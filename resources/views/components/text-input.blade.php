@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'focus-brand border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm disabled:bg-gray-50 disabled:text-gray-500 disabled:cursor-not-allowed transition-colors']) !!}>
