<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                {{ __('Custom Product Builder') }}
            </h2>
            <a href="{{ route('menu') }}" class="inline-flex items-center text-xs font-bold text-slate-500 hover:text-slate-800 underline transition">
                &larr; Back to Menu
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-[calc(100vh-160px)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:product-builder :product="$product" />
        </div>
    </div>
</x-app-layout>