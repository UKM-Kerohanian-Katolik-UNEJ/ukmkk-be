<x-app-layout>
    @push('style')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
        <!-- Card -->
        <div
            class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800"
        >
            <div
            class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full dark:text-orange-100 dark:bg-orange-500"
            >
            <svg
                class="w-5 h-5"
                aria-hidden="true"
                fill="none"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                viewBox="0 0 24 24"
                stroke="currentColor"
                >
                <path
                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
                ></path>
            </div>
            <div>
            <p
                class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400"
            >
                Total Divisi
            </p>
            <p
                class="text-lg font-semibold text-gray-700 dark:text-gray-200"
            >
                {{ $pengurus }}
            </p>
            </div>
        </div>
        <!-- Card -->
        <div
            class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800"
        >
            <div
            class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500"
            >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path
                    d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"
                    ></path>
                </svg>
            </div>
            <div>
            <p
                class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400"
            >
                Total Anggota
            </p>
            <p
                class="text-lg font-semibold text-gray-700 dark:text-gray-200"
            >
                {{ $anggota }}
            </p>
            </div>
        </div>
        <!-- Card -->
        <div
            class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800"
        >
            <div
            class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500"
            >
                <svg
                    class="w-5 h-5"
                    aria-hidden="true"
                    fill="none"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    >
                    <path
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
                    ></path>
                </svg>
            </div>
            <div>
            <p
                class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400"
            >
                Total Artikel
            </p>
            <p
                class="text-lg font-semibold text-gray-700 dark:text-gray-200"
            >
                {{ $articles }}
            </p>
            </div>
        </div>
        <!-- Card -->
        <div
            class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800"
        >
            <div
            class="p-3 mr-4 text-teal-500 bg-teal-100 rounded-full dark:text-teal-100 dark:bg-teal-500"
            >
            <svg
                class="w-5 h-5"
                aria-hidden="true"
                fill="none"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                viewBox="0 0 24 24"
                stroke="currentColor"
                >
                <path d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
            </svg>
            </div>
            <div>
            <p
                class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400"
            >
                Total Proker
            </p>
            <p
                class="text-lg font-semibold text-gray-700 dark:text-gray-200"
            >
                {{ $prokers }}
            </p>
            </div>
        </div>
    </div>

    <div class="p-4 bg-white rounded-lg shadow-xs">
        <!-- Charts -->
        <div class="grid gap-6 mb-8 md:grid-cols-2">
            <div
                class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800"
            >
                <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                Anggota Terdaftar
                </h4>
                <canvas id="bar"></canvas>
            </div>
            <div
                class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800"
            >
                <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                Pembaca
                </h4>
                <canvas id="line"></canvas>
            </div>
        </div>
    </div>
    @push('script')
    <script>
        const bar = document.getElementById('bar');
    
        new Chart(bar, {
            type: 'bar',
            data: {
            labels: {!! json_encode($bar["tahun"] ?? []) !!},
            datasets: [{
                label: 'Anggota',
                data: {{ json_encode($bar["jumlah"] ?? []) }},
                borderWidth: 1
            }]
            },
            options: {
            scales: {
                y: {
                beginAtZero: true
                }
            }
            }
        });
    
        const line = document.getElementById('line');
    
        new Chart(line, {
            type: 'line',
            data: {
            labels: {!! json_encode($line["bulan"]) !!},
            datasets: [
                {
                    label: 'Artikel',
                    data: {!! json_encode($line["jumlah_pembaca_proker"] ?? []) !!},
                    borderWidth: 1
                },
                {
                    label: 'Proker',
                    data: {!! json_encode($line["jumlah_pembaca_artikel"] ?? []) !!},
                    borderWidth: 1
                },
            ]
            },
            options: {
            scales: {
                y: {
                beginAtZero: true
                }
            }
            }
        });
    </script>
    @endpush
</x-app-layout>
