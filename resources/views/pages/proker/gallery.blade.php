<x-app-layout>
    <x-slot name="header">
        {{ __('Galeri Proker') }}
    </x-slot>

    <div class="p-4 bg-white rounded-lg shadow-xs">
        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800"
                        >
                        <th class="px-4 py-3">Galeri</th>
                        <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody
                        class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800"
                    >
                        @foreach ($galleries as $gallery)
                            <tr class="text-gray-700 dark:text-gray-400">
                                <td class="px-4 py-3">
                                    <div
                                        class="relative w-[150px] mr-3 rounded-full block"
                                    >
                                        <img
                                        class="object-cover w-full h-full rounded-full"
                                        src="{{ $gallery->getUrl() }}"
                                        alt=""
                                        loading="lazy"
                                        />
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center space-x-4 text-sm">
                                        <form id="hapus-konten" action="{{ route("admin.proker.galeri.destroy", $gallery->id) }}" method="post">
                                            @csrf
                                            @method("DELETE")
                                            <button type="button"
                                                class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                                                aria-label="Delete"
                                                onclick="deleteGaleri({{ $gallery->id }})"
                                            >
                                                <svg
                                                class="w-5 h-5"
                                                aria-hidden="true"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                                >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"
                                                ></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('script')
        @if (session("success"))
            <script>
                Swal.fire(
                    "Berhasil",
                    `{{ session("success") }}`,
                    "success"
                );
            </script>
        @elseif (session("error"))
            <script>
                Swal.fire(
                    "Gagal",
                    `{{ session("error") }}`,
                    "error"
                );
            </script>
        @endif     
    @endpush

    @push('script')
        <script>
            function deleteGaleri(id)
            {
                Swal.fire({
                    title: `Apakah anda yakin menghapus galeri ini?`,
                    showDenyButton: true,
                    icon: "question",
                    confirmButtonText: 'Simpan',
                    denyButtonText: `Batal`,
                    }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        $("form#hapus-konten").submit();
                    }
                })
            }
        </script>
    @endpush
</x-app-layout>
