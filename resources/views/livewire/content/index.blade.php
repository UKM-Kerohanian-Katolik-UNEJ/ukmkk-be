<div>
    <div class="flex justify-center lg:justify-start">
        <a href="{{ route("admin.konten.create") }}" class="btn btn-primary mb-3 w-full max-w-[170px]">+ Tambah Konten</a>
    </div>
    <div class="flex flex-col lg:flex-row lg:justify-between items-center mb-3 gap-3">
        <div class="flex gap-3">
            <span class="my-auto">Show</span>
            <select wire:model="pagination" class="select select-bordered w-[100px]">
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span class="my-auto">Entries</span>
        </div>
        <div class="flex flex-col gap-3 items-center lg:flex-row">
            <select wire:model="kategori" class="select select-bordered w-[100px]">
                <option value="">Kategori</option>
                <option value="Proker">Proker</option>
                <option value="Artikel">Artikel</option>
            </select>
            <input wire:model="pencarian" type="text" class="input input-bordered w-[350px]" placeholder="Masukkan pencarian"/>
        </div>
    </div>
    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                    class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800"
                    >
                    <th class="px-4 py-3">Judul</th>
                    <th class="px-4 py-3">Kategori</th>
                    <th class="px-4 py-3">Gambar Andalan</th>
                    <th class="px-4 py-3">Pembaca</th>
                    <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody
                    class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800"
                >
                    @foreach ($contents as $content)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3">
                                <div class="flex items-center text-sm">
                                    <div>
                                        <p class="font-semibold">{{ $content->judul }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $content->kategori }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                <div
                                    class="relative w-[100px] mr-3 rounded-full block"
                                >
                                    <img
                                    class="object-cover w-full h-full rounded-full"
                                    src="{{ $content->getFirstMediaUrl("gambar_andalan_konten") }}"
                                    alt=""
                                    loading="lazy"
                                    />
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if (count($content->ContentViews) > 0)
                                    {{ $content->ContentViews->viewers ?? 0 }}
                                @else
                                    0
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-4 text-sm">
                                    <a href="{{ route("admin.konten.kometar", $content->slug) }}">
                                        <button
                                            class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-blue-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                                            aria-label="Edit"
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
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                                ></path>
                                            </svg>
                                        </button>
                                    </a>
                                    <a href="{{ route("admin.konten.edit", $content->slug) }}">
                                        <button
                                            class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-yellow-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                                            aria-label="Edit"
                                        >
                                            <svg
                                            class="w-5 h-5"
                                            aria-hidden="true"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                            >
                                            <path
                                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"
                                            ></path>
                                            </svg>
                                        </button>
                                    </a>
                                    <form id="hapus-konten" action="{{ route("admin.konten.destroy", $content->slug) }}" method="post">
                                        @csrf
                                        @method("DELETE")
                                        <button type="button"
                                            class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                                            aria-label="Delete"
                                            onclick="deleteKonten('{{ $content->slug }}')"
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
        <div
            class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800"
        >
            {{-- Pagination --}}
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
            function deleteKonten(nama)
            {
                Swal.fire({
                    title: `Apakah anda yakin menghapus konten ini? Menghapus konten akan menghapus data, galeri, dan komentar`,
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
</div>
