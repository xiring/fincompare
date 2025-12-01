<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
            <p class="mt-1 text-sm text-gray-600">Welcome back, {{ Auth::user()->name }}!</p>
        </div>
    </x-slot>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <a href="{{ route('admin.products.index') }}" class="group relative bg-white rounded-lg shadow-soft border border-gray-200 p-6 hover:shadow-lg hover:border-primary-200 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Manage</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">Products</p>
                </div>
                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-primary-100 text-primary-600 group-hover:bg-primary-200 transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.leads.index') }}" class="group relative bg-white rounded-lg shadow-soft border border-gray-200 p-6 hover:shadow-lg hover:border-primary-200 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">View</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">Leads</p>
                </div>
                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-primary-100 text-primary-600 group-hover:bg-primary-200 transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.partners.index') }}" class="group relative bg-white rounded-lg shadow-soft border border-gray-200 p-6 hover:shadow-lg hover:border-primary-200 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Manage</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">Partners</p>
                </div>
                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-primary-100 text-primary-600 group-hover:bg-primary-200 transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </a>
    </div>
</x-app-layout>
