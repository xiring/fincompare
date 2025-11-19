<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800 leading-tight">Admin Dashboard</h1>
    </x-slot>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <a href="{{ route('admin.products.index') }}" class="p-4 bg-white shadow rounded-lg hover:shadow-md transition">
                <div class="text-sm text-gray-500">Manage</div>
                <div class="mt-2 text-2xl font-semibold text-gray-900">Products</div>
            </a>
            <a href="{{ route('admin.leads.index') }}" class="p-4 bg-white shadow rounded-lg hover:shadow-md transition">
                <div class="text-sm text-gray-500">View</div>
                <div class="mt-2 text-2xl font-semibold text-gray-900">Leads</div>
            </a>
            <a href="{{ route('admin.partners.index') }}" class="p-4 bg-white shadow rounded-lg hover:shadow-md transition">
                <div class="text-sm text-gray-500">Manage</div>
                <div class="mt-2 text-2xl font-semibold text-gray-900">Partners</div>
            </a>
        </div>
    </div>
</x-app-layout>
