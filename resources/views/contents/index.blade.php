<x-app-layout>
    <x-slot name="header">
        {{ __('Konten') }}
    </x-slot>

    <div class="p-4 bg-white rounded-lg shadow-xs">
        <livewire:content.index /> 
    </div>
</x-app-layout>
