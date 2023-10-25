<x-app-layout>
    <x-slot name="header">
        {{ __('Anggota') }}
    </x-slot>

    <div class="p-4 bg-white rounded-lg shadow-xs">
        <livewire:members.index />
    </div>
</x-app-layout>
