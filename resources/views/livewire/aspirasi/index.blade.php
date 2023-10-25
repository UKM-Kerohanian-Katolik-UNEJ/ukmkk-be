<div>
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
            <input type="month" wire:model="bulanTahun" class="input input-bordered">
            <div class="dropdown dropdown-end">
                <label tabindex="0" class="btn bg-white border border-gray-300 m-1">...</label>
                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                    <li class="hover:bg-yellow-300 font-semibold rounded-lg group"><a class="group-hover:text-white" wire:click="resetTanggal()">Reset Filter</a></li>
                    <li class="hover:bg-green-500 font-semibold rounded-lg group"><a class="group-hover:text-white">Export Excel</a></li>
                    <li class="hover:bg-red-600 rounded-lg font-semibold group"><a class="group-hover:text-white">Export PDF</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                    class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800"
                    >
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Aspirasi</th>
                    </tr>
                </thead>
                <tbody
                    class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800"
                >
                    @php $no = 1; @endphp
                    @foreach ($aspirasis as $aspirasi)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-sm">
                                {{ $aspirasi->nama ?? "Anonim-" . $no++ }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $member->created_at }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $member->aspirasi }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div
            class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800"
        >
           {{ $aspirasis->links() }}
        </div>
    </div>
</div>
